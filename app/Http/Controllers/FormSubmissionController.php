<?php

namespace App\Http\Controllers;

use App\Models\Form;
use App\Models\FormSubmission;
use Illuminate\Http\Request;

class FormSubmissionController extends Controller
{
    public function index(Form $form)
    {
        return $form->submissions()->with(['form:id,title', 'answers.field'])->get();
    }

    public function store(Request $request, Form $form)
    {
        $request->validate([
            'answers' => 'required|array',
        ]);

        $submission = $form->submissions()->create();

        foreach ($request->answers as $fieldId => $value) {
            $field = $form->fields()->find($fieldId);
            
            if (!$field) {
                Log::warning("Field ID $fieldId not found for form ID {$form->id}");
                continue;
            }
            
            if ($field->required && empty($value)) {
                return response()->json([
                    'message' => 'The given data was invalid.',
                    'errors' => ["answers.$fieldId" => ['This field is required.']]
                ], 422);
            }

            $submission->answers()->create([
                'field_id' => $fieldId,
                'value' => is_array($value) ? json_encode($value) : $value
            ]);
        }

        return $submission->load('answers.field');
    }

    public function show(FormSubmission $submission)
    {
        return $submission->load('answers.field');
    }

    public function allSubmissions()
    {
        return FormSubmission::with(['form:id,title', 'answers.field'])
                            ->latest()
                            ->get();
    }
}