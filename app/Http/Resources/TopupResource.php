<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TopupResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'order_id' => $this->order_id,
            'amount' => $this->amount,
            'status' => $this->status,
            'snap_token' => $this->snap_token,
            'date_topup' => $this->created_at->format('d M Y H:i'),
            'expire_at' => $this->created_at->copy()->addDay()->format('d M Y H:i'),
        ];
    }
}
