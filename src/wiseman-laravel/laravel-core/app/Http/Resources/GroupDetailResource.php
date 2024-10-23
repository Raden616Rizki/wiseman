<?php

namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class GroupDetailResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
			'isAdmin' => $this->is_admin,
            'user' => new GroupUserDetailResource($this->user),
        ];
    }
}
