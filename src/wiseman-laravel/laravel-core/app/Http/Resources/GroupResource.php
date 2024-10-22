<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GroupResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
			'description' => $this->description,
        	// 'groupDetails' => GroupUserDetailResource::collection($this->groupUsers),
			// 'activities' => ActivityResource::collection($this->activities),
			// 'votings' => VotingResource::collection($this->votings),
			// 'archives' => ArchiveResource::collection($this->archives),
		];
    }
}
