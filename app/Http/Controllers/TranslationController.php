<?php

namespace App\Http\Controllers;

use App\Models\Translation;
use Illuminate\Http\Request;

use App\Repositories\Contracts\TranslationRepositoryInterface;

class TranslationController extends Controller
{
    /**
     * @var TranslationRepositoryInterface
     */
    protected TranslationRepositoryInterface $repository;

    public function __construct(TranslationRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function index(Request $request)
    {
        $translations = $this->repository->all($request->only(['tag', 'key', 'content']));
        return response()->json($translations);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'locale' => 'required|string',
            'key' => 'required|string',
            'content' => 'required|string',
            'tags' => 'array',
            'tags.*' => 'string'
        ]);

        if ($this->repository->exists($validated['locale'], $validated['key'])) {
            return response()->json(['message' => 'Translation key already exists for this locale.'], 422);
        }

        $translation = $this->repository->create($validated);
        return response()->json($translation);
    }

    /**
     * Display the specified resource.
     */
    public function show(Translation $translation)
    {
        return response()->json($translation->load('tags'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Translation $translation)
    {
        $validated = $request->validate([
            'content' => 'string',
            'tags' => 'array',
            'tags.*' => 'string',
        ]);

        $updated = $this->repository->update($translation, $validated);
        return response()->json($updated);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Translation $translation)
    {
        $this->repository->delete($translation);
        return response()->json(['message' => 'Translation deleted successfully.']);
    }

    /**
     * Export translations as JSON.
     */
    public function export(Request $request)
    {
        $locale = $request->query('locale', 'en');
        $export = $this->repository->export($locale);

        return $this->sendResponse(true, $export, 'Translations exported successfully.');
    }
}
