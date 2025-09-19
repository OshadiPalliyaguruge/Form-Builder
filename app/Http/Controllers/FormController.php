<?php

namespace App\Http\Controllers;

use App\Models\Form;
use Illuminate\Http\Request;

class FormController extends Controller
{
    public function index()
    {
        return Form::withCount('submissions')->with('fields')->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'fields' => 'array',
            'fields.*.label' => 'required|string',
            'fields.*.type' => 'required|in:text,textarea,checkbox,radio',
            'fields.*.required' => 'boolean',
            'fields.*.options' => 'nullable|array',
        ]);

        $form = Form::create($request->only('title'));

        if ($request->has('fields')) {
            foreach ($request->fields as $index => $fieldData) {
                $form->fields()->create([
                    'label' => $fieldData['label'],
                    'type' => $fieldData['type'],
                    'options' => $fieldData['options'] ?? null,
                    'required' => $fieldData['required'] ?? false,
                    'order' => $index,
                ]);
            }
        }

        return $form->load('fields')->loadCount('submissions');
    }

    public function show(Form $form)
    {
        return $form->load('fields');
    }

    public function update(Request $request, Form $form)
    {
        $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'fields' => 'array',
            'fields.*.label' => 'required|string',
            'fields.*.type' => 'required|in:text,textarea,checkbox,radio',
            'fields.*.required' => 'boolean',
            'fields.*.options' => 'nullable|array',
        ]);

        $form->update($request->only('title'));

        if ($request->has('fields')) {
            $form->fields()->delete();
            
            foreach ($request->fields as $index => $fieldData) {
                $form->fields()->create([
                    'label' => $fieldData['label'],
                    'type' => $fieldData['type'],
                    'options' => $fieldData['options'] ?? null,
                    'required' => $fieldData['required'] ?? false,
                    'order' => $index,
                ]);
            }
        }

        return $form->load('fields')->loadCount('submissions');
    }

    public function destroy(Form $form)
    {
        $form->delete();
        return response()->noContent();
    }
}