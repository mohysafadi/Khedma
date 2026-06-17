<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OfferResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'offer_id' => $this->offer_id,
            'price' => $this->price,
            'details' => $this->details,
            'eta' => $this->eta,
            'status' => $this->status,

            'professional' => [
                'id' => $this->professional->professional_id,
                'name' => $this->professional->user->name,
                'rating' => $this->professional->rating,
            ],

            'request' => [
                'id' => $this->request->request_id,
                'description' => $this->request->description,
                'photo_url' => $this->request->photo ? asset('storage/'.$this->request->photo) : null,
                'category' => [
                    'id' => $this->request->category->category_id,
                    'name' => $this->request->category->name,
                ]
            ]
        ];
    }
}