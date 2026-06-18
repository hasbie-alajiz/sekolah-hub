<?php

declare(strict_types=1);

namespace App\Modules\Media\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadMediaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'file' => [
                'required',
                'file',
                'max:5120', // 5MB limit
                'mimes:jpeg,png,webp,gif,pdf,doc,docx,xls,xlsx,txt',
            ],
            'folder_id' => [
                'nullable',
                'integer',
                'exists:media_folders,id',
            ],
            'caption' => [
                'nullable',
                'string',
                'max:255',
            ],
            'alt_text' => [
                'nullable',
                'string',
                'max:255',
            ],
        ];
    }
}
