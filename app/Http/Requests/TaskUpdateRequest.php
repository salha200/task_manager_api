<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true; 
    }

    public function rules()
    {
        return [
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|nullable|string',
            'type' => 'sometimes|required|in:Bug,Feature,Improvement',
            'status' => 'sometimes|required|in:Open,In Progress,Completed,Blocked',
            'priority' => 'sometimes|required|in:Low,Medium,High',
            'due_date' => 'sometimes|required|date',
            'assigned_to' => 'sometimes|required|exists:users,id',
        ];
    }
}
