<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ServiceRequestResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'request_id' => $this->request_id,
            'category' => [
                'id' => $this->category?->category_id,
                'name' => $this->category?->name,
            ],
            'description' => $this->description,
            'address' => $this->address,
            'photo_url' => $this->photo ? asset('storage/'.$this->photo) : null,
            'status' => $this->status,
            'offers_count' => $this->offers_count ?? $this->offers()->count(),
            'created_at' => $this->created_at->format('Y-m-d'),
        ];
    }
}