<?php

namespace App\Http\Resources;


use App\Http\Resources\GroupUserResource;
use App\Http\Resources\ActivityResource;
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
			'photo' => $this->photo,
        	'groupUsers' => GroupUserResource::collection($this->groupUsers),
			'activities' => ActivityResource::collection($this->activities),
		];
    }
}
