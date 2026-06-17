<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ServiceRequestDetailsResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'request_id' => $this->request_id,
            'description' => $this->description,
            'address' => $this->address,
            'photo_url' => $this->photo ? asset('storage/' . $this->photo) : null,
            'status' => $this->status,
            'created_at' => $this->created_at->format('Y-m-d'),

            'category' => [
                'id' => $this->category?->category_id,
                'name' => $this->category?->name,
            ],

            'offers' => $this->offers->map(function ($offer) {
                return [
                    'offer_id' => $offer->offer_id,
                    'price' => $offer->price,
                    'details' => $offer->details,
                    'eta' => $offer->eta,
                    'status' => $offer->status,
                    'professional' => [
                        'id' => $offer->professional->professional_id,
                        'name' => $offer->professional->user->name,
                        'rating' => $offer->professional->rating,
                    ]
                ];
            }),
        ];
    }
}
