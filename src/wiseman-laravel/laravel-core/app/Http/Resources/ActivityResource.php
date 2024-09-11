<?php

namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class ActivityResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'group_id' => $this->group_id,
			'user_id' => $this->user_id,
			'description' => $this->description,
			'start_time' => $this->start_time,
			'end_time' => $this->end_time,
			'is_priority' => $this->is_priority,
			'is_finished' => $this->is_finished,
        ];
    }
}
