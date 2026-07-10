<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OfferResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'offer_id'        => $this->offer_id,
            'description'     => $this->description,
            'duration'        => $this->duration,
            'price'           => $this->price,
            'status'          => $this->status,
            'created_at'      => $this->created_at->format('Y-m-d H:i'),

            // معلومات المهني
            'professional' => [
                'professional_id' => $this->professional->professional_id,
                'name'            => $this->professional->user->name,
                'category'        => $this->professional->category->name,
                'governorate'     => $this->professional->governorate->name,
            ],

            // معلومات الطلب
            'request' => [
                'request_id'   => $this->request->request_id,
                'category'     => $this->request->category->name,
                'description'  => $this->request->description,
                'customer_name' => $this->request->customer->user->name,
            ]
        ];
    }
}
