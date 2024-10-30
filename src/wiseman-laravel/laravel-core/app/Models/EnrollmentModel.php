<?php

namespace App\Models;

use App\Repository\CrudInterface;
use App\Http\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class EnrollmentModel extends Model implements CrudInterface
{
    use HasFactory;
    use Uuid;
    use SoftDeletes;

    public $timestamps = true;

    protected $fillable = [
        'user_id', 'group_id'
    ];

    protected $table = 't_enrollments';

    public function getAll(array $filter, int $itemPerPage = 0, string $sort = '')
    {
        $query = $this->query();
        
		if (!empty($filter['user_id'])) {
			$query->where('user_id', 'LIKE', '%' . $filter['user_id'] . '%');
		}

		if (!empty($filter['group_id'])) {
			$query->where('group_id', 'LIKE', '%' . $filter['group_id'] . '%');
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
    
	public function user()
	{
		return $this->belongsTo(UserModel::class, 'user_id', 'id');
	}

	public function group()
	{
		return $this->belongsTo(GroupModel::class, 'group_id', 'id');
	}

}
