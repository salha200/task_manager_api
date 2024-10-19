<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DailyTaskReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Ensure the user has permission to view reports
        return true;
    }

    public function rules(): array
    {
        return [];
    }
}
