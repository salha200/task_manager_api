<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommentRequest extends FormRequest
{
    public function authorize()
    {
        return true; // أو يمكنك تخصيص التفويض إذا كنت تحتاج إلى صلاحيات معينة
    }

    public function rules()
    {
        return [
            'content' => 'required|string',
        ];
    }
}
