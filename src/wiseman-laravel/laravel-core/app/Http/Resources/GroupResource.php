<?php

namespace App\Http\Resources;

use App\Http\Resources\MemoResource;
use Illuminate\Http\Resources\Json\JsonResource;

class GroupResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
			'description' => $this->description,
			'memos' => MemoResource::collection($this->memos),
		];
    }
}
