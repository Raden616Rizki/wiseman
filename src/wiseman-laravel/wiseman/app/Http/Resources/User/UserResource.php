<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'username' => $this->name,
            'email' => $this->email,
            'photo_url' => !empty($this->photo) ? Storage::disk('public')->url($this->photo) : null,
            // 'photo_url' => !empty($this->photo) ? Storage::disk('public'.$this->photo) : null,
            'updated_security' => $this->updated_security,
            'phone' => $this->phone
        ];
    }
}
