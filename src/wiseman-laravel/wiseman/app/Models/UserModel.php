<?php

namespace App\Models;

use App\Http\Traits\Uuid;
use App\Repository\CrudInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserModel extends Model implements CrudInterface
{
    use HasFactory;
    use Uuid;
    use SoftDeletes; // Use SoftDeletes library
    protected $table = "m_user";
    protected $fillable = [
        'username',
        'email',
        'password',
        'photo_profile',
        'phone',
    ];
    public $timestamp = true;

    public function getAll(array $filter, int $itemPerPage = 0, string $sort = '')
    {
        $user = $this->query();

        if (!empty($filter['username'])) {
            $user->where('username', 'LIKE', '%'.$filter['username'].'%');
        }

        if (!empty($filter['email'])) {
            $user->where('email', 'LIKE', '%'.$filter['email'].'%');
        }

        $sort = $sort ?: 'username ASC';
        $user->orderByRaw($sort);
        $itemPerPage = ($itemPerPage > 0) ? $itemPerPage : false ;

        return $user->paginate($itemPerPage)->appends('sort', $sort);
    }

    public function getById(string $id)
    {
        return $this->find($id);
    }

    public function store(array $payload){
        return $this->create($payload);
    }
    public function edit(array $payload, string $id){
        return $this->find($id)->update($payload);
    }
    public function drop(string $id) {
        return $this->find($id)->delete();
    }
}
