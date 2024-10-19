<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AttachmentRequest extends FormRequest
{
    public function authorize()
    {
        return true; // يمكنك تخصيص التفويض إذا كنت تحتاج إلى صلاحيات معينة
    }

    public function rules()
    {
        return [
            'file' => 'required|file|mimes:jpg,png,pdf,docx,zip',
        ];
    }
}
