<?php

namespace App\Http\Resources;


use App\Http\Resources\GroupUserResource;
use App\Http\Resources\ActivityResource;
use App\Http\Resources\VotingResource;
use App\Http\Resources\ArchiveResource;
use Illuminate\Http\Resources\Json\JsonResource;

class GroupResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
			'description' => $this->description,
        	// 'groupUsers' => GroupUserResource::collection($this->groupUsers),
			// 'activities' => ActivityResource::collection($this->activities),
			// 'votings' => VotingResource::collection($this->votings),
			// 'archives' => ArchiveResource::collection($this->archives),
		];
    }
}
