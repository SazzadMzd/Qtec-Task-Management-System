<?php

namespace App\Http\Requests;

use App\Models\Task;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'assigned_to' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', Rule::in(Task::statuses())],
            'start_time' => ['nullable', 'date'],
            'end_time' => array_filter([
                'nullable',
                'date',
                $this->filled('start_time') ? 'after:start_time' : null,
            ]),
        ];
    }
}
