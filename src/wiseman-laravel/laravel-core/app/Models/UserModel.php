<?php

namespace App\Models;

use App\Http\Traits\Uuid;
use App\Repository\CrudInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class UserModel extends Authenticatable implements CrudInterface, JWTSubject
{
    use HasFactory;
    use Uuid;
    use SoftDeletes;

    public $timestamps = true;

    protected $fillable = [
        'name', 'email', 'password', 'phone_number', 'photo'
    ];

    protected $table = 'm_user';

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [
            'user' => [
                'id' => $this->id,
                'email' => $this->email,
                'updated_security' => $this->updated_security
            ]
        ];
    }

    public function getAll(array $filter, int $itemPerPage = 0, string $sort = '')
    {
        $query = $this->query();

		// if (!empty($filter['m_user_roles_id'])) {
		// 	$query->where('m_user_roles_id', 'LIKE', '%' . $filter['m_user_roles_id'] . '%');
		// }

		if (!empty($filter['name'])) {
			$query->where('name', 'LIKE', '%' . $filter['name'] . '%');
		}

		if (!empty($filter['email'])) {
			$query->where('email', 'LIKE', '%' . $filter['email'] . '%');
		}

		if (!empty($filter['password'])) {
			$query->where('password', 'LIKE', '%' . $filter['password'] . '%');
		}

		if (!empty($filter['phone_number'])) {
			$query->where('phone_number', 'LIKE', '%' . $filter['phone_number'] . '%');
		}

		if (!empty($filter['photo'])) {
			$query->where('photo', 'LIKE', '%' . $filter['photo'] . '%');
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
		return $this->hasMany(GroupUserModel::class, 'user_id', 'id');
	}

	public function activities()
	{
		return $this->hasMany(ActivityModel::class, 'user_id', 'id');
	}
}
