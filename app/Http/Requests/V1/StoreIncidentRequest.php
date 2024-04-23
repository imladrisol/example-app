<?php

declare(strict_types=1);

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class StoreIncidentRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'courierId' => 'integer',
            'clientId' => 'integer',
            'name' => 'required|string',
            'reason' => 'required|string',
            'occurredAt' => 'required|date',
            'deadline' => 'required|date',
        ];
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'courier_id' => $this->courierId,
            'client_id' => $this->clientId,
            'occurred_at' => $this->occurredAt,
        ]);
    }
}
