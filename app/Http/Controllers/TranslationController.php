<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Translation;

class TranslationController extends Controller
{
    public function create(Request $request)
    {
        $request->validate([
            'key' => 'required|string|max:100',
            'locale' => 'required|string|max:10',
            'content' => 'required|string',
            'tags' => 'nullable|string|max:100',
        ]);

        $translation = Translation::create($request->all());
        return response()->json(['message' => 'Translation created successfully', 'translation' => $translation], 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'key' => 'required|string|max:100',
            'locale' => 'required|string|max:10',
            'content' => 'required|string',
            'tags' => 'nullable|string|max:100',
        ]);

        $translation = Translation::findOrFail($id);
        $translation->update($request->all());

        return response()->json(['message' => 'Translation updated successfully', 'translation' => $translation]);
    }

    public function view($id)
    {
        $translation = Translation::findOrFail($id);
        return response()->json($translation);
    }

    public function search(Request $request)
    {
        $query = Translation::query();

        if ($request->has('key')) {
            $query->where('key', 'like', '%' . $request->key . '%');
        }
        if ($request->has('locale')) {
            $query->where('locale', $request->locale);
        }
        if ($request->has('content')) {
            $query->where('content', 'like', '%' . $request->content . '%');
        }
        if ($request->has('tags')) {
            $query->where('tags', 'like', '%' . $request->tags . '%');
        }

        $translations = $query->get();
        return response()->json($translations);
    }

    public function export()
    {
        $translations = Translation::all();
        $data = [];

        foreach ($translations as $translation) {
            if (!isset($data[$translation->locale])) {
                $data[$translation->locale] = [];
            }
            $data[$translation->locale][$translation->key] = $translation->content;
        }

        return response()->json($data);
    }
}
