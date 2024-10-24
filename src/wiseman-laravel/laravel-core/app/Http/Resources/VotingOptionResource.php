<?php

namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class VotingOptionResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'option' => $this->option,
			'total' => $this->total,
			'votingId' => $this->voting_id,
        ];
    }
}
