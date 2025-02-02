<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TodoRequest extends FormRequest
{
    public function rules(): array
    {
        if ($this->isMethod('put')) {
            return [
                'title' => 'sometimes|string',
                'description' => 'sometimes|string',
                'is_completed' => 'sometimes|boolean',
            ];
        }

        return [
            'title' => 'required|string',
            'description' => 'required|string',
        ];
    }
}
