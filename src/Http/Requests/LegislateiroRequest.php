<?php

namespace Legislateiro\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LegislateiroRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return true
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return string[]
     *
     * @psalm-return array{value: string}
     */
    public function rules(): array
    {
        return [
            'value'       => 'required'
        ];
    }
}
