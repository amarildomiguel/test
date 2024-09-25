<?php

namespace App\Models;

use App\Models\Utils\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class People extends BaseModel
{

    protected $fillable = [
        'name',
        'email',
        'phone',
        'gender',
        'address',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getCreatedAtAttribute($value)
    {
        return date('Y-m-d H:i:s', strtotime($value));
    }

    public function scopeSearch($query, $search)
    {
        if ($search) {

            $searchTerms = explode(' ', $search);

            $query->where(function ($query) use ($searchTerms) {
                foreach ($searchTerms as $term) {
                    $term = strtolower($term);
                    $query->where(function ($query) use ($term) {
                        $query->where('name', 'LIKE', "%$term%")
                            ->orWhere('phone', 'LIKE', "%$term%")
                            ->orWhere('email', 'LIKE', "%$term%")
                            ->orWhereHas('user', function ($query) use ($term) {
                                $query->whereRaw('lower(name) LIKE ?', ["%$term%"])
                                    ->orWhere('email', 'LIKE', "%$term%");
                            })
                        ;
                    });
                }
            });
        }

        return $query;
    }


    public function scopeSort($query, $sortBy)
    {
        if ($sortBy) {

            if ($sortBy['key'] === 'user.name') {
                return $query->join('users', 'people.user_id', '=', 'users.id')
                    ->orderBy('users.name', $sortBy['order'])
                    ->select('people.*');
            }

            return $query->orderBy($sortBy['key'], $sortBy['order']);
        }
        return $query;
    }
}
