<?php

namespace App\Http\Resources;


use App\Http\Resources\GroupUserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class GroupResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
			'description' => $this->description,
        	'groupUsers' => GroupUserResource::collection($this->groupUsers),
		];
    }
}
