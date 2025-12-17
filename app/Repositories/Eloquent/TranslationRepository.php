<?php

namespace App\Repositories\Eloquent;

use App\Models\Tag;
use App\Models\Translation;
use App\Repositories\Contracts\TranslationRepositoryInterface;
use Database\Factories\UserFactory;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class TranslationRepository implements TranslationRepositoryInterface
{
    public function all(array $filters): LengthAwarePaginator
    {
        return Translation::with('tags')
            ->filter($filters)
            ->paginate(50);
    }

    public function create(array $data): Translation
    {
        $translation = Translation::create([
            'locale' => $data['locale'],
            'key' => $data['key'],
            'content' => $data['content'],
        ]);

        if (!empty($data['tags'])) {
            $this->syncTags($translation, $data['tags']);
        }

        return $translation->load('tags');
    }

    public function find(int $id): ?Translation
    {
        return Translation::with('tags')->find($id);
    }

    public function update(Translation $translation, array $data): Translation
    {
        if (isset($data['content'])) {
            $translation->update(['content' => $data['content']]);
        }

        if (isset($data['tags'])) {
            $this->syncTags($translation, $data['tags']);
        }

        return $translation->load('tags');
    }

    public function delete(Translation $translation): void
    {
        $translation->delete();
    }

    public function export(string $locale): Collection
    {
        return Translation::where('locale', $locale)->pluck('content', 'key');
    }

    public function exists(string $locale, string $key): bool
    {
        return Translation::where('locale', $locale)
            ->where('key', $key)
            ->exists();
    }

    protected function syncTags(Translation $translation, array $tagNames): void
    {
        $tagIds = [];
        foreach ($tagNames as $tagName) {
            $tag = Tag::firstOrCreate(['name' => $tagName]);
            $tagIds[] = $tag->id;
        }
        $translation->tags()->sync($tagIds);
    }
}
