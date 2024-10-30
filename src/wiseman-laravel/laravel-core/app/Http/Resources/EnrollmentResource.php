<?php

namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class EnrollmentResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
			'group_id' => $this->group_id,
            'user' => new GroupUserDetailResource($this->user),
        ];
    }
}
