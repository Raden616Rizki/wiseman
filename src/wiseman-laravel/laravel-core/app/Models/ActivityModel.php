<?php

namespace App\Models;

use App\Repository\CrudInterface;
use App\Http\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class ActivityModel extends Model implements CrudInterface
{
    use HasFactory;
    use Uuid;
    use SoftDeletes;

    public $timestamps = true;

    protected $fillable = [
        'group_id', 'user_id', 'description', 'start_time', 'end_time', 'is_priority', 'is_finished', 'google_calendar_event_id',
    ];

    protected $table = 't_activities';

    public function getAll(array $filter, int $itemPerPage = 0, string $sort = '')
    {
        $query = $this->query();

		if (!empty($filter['group_id'])) {
			$query->where('group_id', 'LIKE', '%' . $filter['group_id'] . '%');
		}

		if (!empty($filter['user_id'])) {
			$query->where('user_id', 'LIKE', '%' . $filter['user_id'] . '%');
		}

		if (!empty($filter['description'])) {
			$query->where('description', 'LIKE', '%' . $filter['description'] . '%');
		}

		if (!empty($filter['start_time'])) {
			$query->where('start_time', 'LIKE', '%' . $filter['start_time'] . '%');
		}

		if (!empty($filter['end_time'])) {
			$query->where('end_time', 'LIKE', '%' . $filter['end_time'] . '%');
		}

		if (!empty($filter['is_priority'])) {
			$query->where('is_priority', 'LIKE', '%' . $filter['is_priority'] . '%');
		}

		if (!empty($filter['is_finished'])) {
			$query->where('is_finished', 'LIKE', '%' . $filter['is_finished'] . '%');
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

	public function group()
	{
		return $this->belongsTo(GroupModel::class, 'group_id', 'id');
	}

	public function user()
	{
		return $this->belongsTo(UserModel::class, 'user_id', 'id');
	}

}
