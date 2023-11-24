<?php

namespace OwenIt\Auditing\Http\Controllers;

use Illuminate\Foundation\Http\FormRequest;

class AuditSearchIndexRequest extends FormRequest
{
    public function rules(): array
    {
        return config('audit.search_request_validation_rules');
    }

    public function authorize(): bool
    {
        return config('audit.search_request_validation_authorize');
    }
}