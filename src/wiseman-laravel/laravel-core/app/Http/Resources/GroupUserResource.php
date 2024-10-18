<?php

namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class GroupUserResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'group_id' => $this->group_id,
			'user_id' => $this->user_id,
			'is_admin' => $this->is_admin,
            'group' => new GroupResource($this->group),
        ];
    }
}
