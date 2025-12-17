<?php

namespace App\Repositories\Contracts;

use App\Models\Translation;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface TranslationRepositoryInterface
{
    public function all(array $filters): LengthAwarePaginator;
    public function create(array $data): Translation;
    public function find(int $id): ?Translation;
    public function update(Translation $translation, array $data): Translation;
    public function delete(Translation $translation): void;
    public function export(string $locale): \Illuminate\Support\Collection;
    public function exists(string $locale, string $key): bool;
}
