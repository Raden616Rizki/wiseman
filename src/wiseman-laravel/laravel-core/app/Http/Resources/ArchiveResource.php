<?php

namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class ArchiveResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
			'file' => $this->file,
			'parent_id' => $this->parent_id,
			'group_id' => $this->group_id,
        ];
    }
}
