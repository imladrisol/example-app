<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class IncidentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'courierId' => $this->courier_id,
            'clientId' => $this->client_id,
            'name' => $this->name,
            'reason' => $this->reason,
            'occurredAt' => $this->occurred_at,
            'deadline' => $this->deadline,
        ];
    }
}
