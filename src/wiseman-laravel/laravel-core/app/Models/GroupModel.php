<?php

namespace App\Models;

use App\Repository\CrudInterface;
use App\Http\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class GroupModel extends Model implements CrudInterface
{
    use HasFactory;
    use Uuid;
    use SoftDeletes;

    public $timestamps = true;

    protected $fillable = [
        'name', 'description'
    ];

    protected $table = 'm_groups';

    public function getAll(array $filter, int $itemPerPage = 0, string $sort = '')
    {
        $query = $this->query();
        
		if (!empty($filter['name'])) {
			$query->where('name', 'LIKE', '%' . $filter['name'] . '%');
		}

		if (!empty($filter['description'])) {
			$query->where('description', 'LIKE', '%' . $filter['description'] . '%');
		}

        $sort = $sort ?: 'id DESC';
        $query->orderByRaw($sort);
        $itemPerPage = ($itemPerPage > 0) ? $itemPerPage : false;

        return $query->paginate($itemPerPage)->appends('sort', $sort);
    }

    public function getById(string $id)
    {
        return $this->find($id);
    }

    public function store(array $payload)
    {
        return $this->create($payload);
    }

    public function edit(array $payload, string $id)
    {
        $query = $this->find($id);

        if (empty($query)) {
            return false;
        }

        return $query->update($payload);
    }

    public function drop(string $id)
    {
        return $this->find($id)->delete();
    }
    

	public function groupUsers()
	{
		return $this->hasMany(GroupUserModel::class, 'group_id', 'id');
	}

	public function activities()
	{
		return $this->hasMany(ActivityModel::class, 'group_id', 'id');
	}
}