<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskCreateRequest extends FormRequest
{
    public function authorize()
    {
        return true; 
    }

    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:Bug,Feature,Improvement',
            'status' => 'required|in:Open,In Progress,Completed,Blocked',
            'priority' => 'required|in:Low,Medium,High',
            'due_date' => 'required|date',
            'assigned_to' => 'required|exists:users,id',
        ];
    }
}
