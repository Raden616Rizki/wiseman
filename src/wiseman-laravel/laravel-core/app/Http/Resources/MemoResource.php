<?php

namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class MemoResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'group_id' => $this->group_id,
			'message' => $this->message,
        ];
    }
}
