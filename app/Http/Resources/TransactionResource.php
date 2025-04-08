<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
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
            "id" => $this->id,
            "payer" => $this->payer_id, // Sugiro usar relação
            "payee" => $this->payee_id,
            "value" => $this->value,     // Adicionar campo importante
            "status" => $this->status,   //
            "notification_status" => $this->notification_status,
            "notified_at" => $this->notified_at?->toDateTimeString(),
            "created_at" => $this->created_at->toDateTimeString()
        ];
    }
}
