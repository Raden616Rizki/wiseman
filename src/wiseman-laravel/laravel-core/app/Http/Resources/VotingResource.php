<?php

namespace App\Http\Resources;


use App\Http\Resources\VotingOptionResource;
use Illuminate\Http\Resources\Json\JsonResource;

class VotingResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'description' => $this->description,
			'limitTime' => $this->limit_time,
			'groupId' => $this->group_id,
        	'votingOptions' => VotingOptionResource::collection($this->votingOptions),
            'group' => new GroupResource($this->group),
		];
    }
}
