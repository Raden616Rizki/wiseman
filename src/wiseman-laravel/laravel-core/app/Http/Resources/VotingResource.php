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
			'limit_time' => $this->limit_time,
        	'votingOptions' => VotingOptionResource::collection($this->votingOptions),
		];
    }
}
