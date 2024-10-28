<?php

namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ArchiveResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
			// 'file' => $this->file,
            'file' => !empty($this->file) ? Storage::disk('public')->url($this->file) : null,
			'parent_id' => $this->parent_id,
			'group_id' => $this->group_id,
        ];
    }
}
