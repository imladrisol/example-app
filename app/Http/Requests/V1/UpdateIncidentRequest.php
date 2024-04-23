<?php

declare(strict_types=1);

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class UpdateIncidentRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'courierId' => 'sometimes|integer',
            'clientId' => 'sometimes|integer',
            'name' => 'sometimes|required|string',
            'reason' => 'sometimes|required|string',
            'occurredAt' => 'sometimes|required|date',
            'deadline' => 'sometimes|required|date',
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
