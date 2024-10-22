<?php

namespace App\Http\Resources;


use Illuminate\Support\Facades\Storage;
use App\Http\Resources\GroupUserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            // 'm_user_roles_id' => $this->m_user_roles_id,
			'name' => $this->name,
			'email' => $this->email,
			'password' => $this->password,
			'phone_number' => $this->phone_number,
			// 'photo' => $this->photo,
            'photo_url' => !empty($this->photo) ? Storage::disk('public')->url($this->photo) : null,
        	'groupUsers' => GroupUserResource::collection($this->groupUsers)
		];
    }
}
