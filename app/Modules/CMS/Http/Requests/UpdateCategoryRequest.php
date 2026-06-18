<?php

declare(strict_types=1);

namespace App\Modules\CMS\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('cms.manage');
    }

    public function rules(): array
    {
        return [
            'parent_id' => [
                'nullable',
                'integer',
                'exists:categories,id',
                function ($attribute, $value, $fail) {
                    $category = $this->route('category');
                    $categoryId = is_object($category) ? $category->id : $category;
                    if ($categoryId && (int) $value === (int) $categoryId) {
                        $fail('Kategori tidak boleh menjadi parent dirinya sendiri.');
                    }
                }
            ],
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ];
    }
}
