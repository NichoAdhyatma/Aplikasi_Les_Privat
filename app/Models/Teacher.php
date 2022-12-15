<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Category;
use App\Models\User;

class Teacher extends Model
{
    use HasFactory;

    protected $guarded = [
        'id',
    ];

    protected $with = ['education', 'certificate'];

    public function scopeFilter($query, array $filter)
    {
        $query->when(
            $filter['daerah'] ?? false,
            fn ($query, $daerah) => $query->whereHas('user', function ($query) use($daerah) {
                $query->where('address', $daerah);
            })
        )->when(
            $filter['category'] ?? false,
            fn ($query, $category) => $query->whereHas('category', function ($query) use($category) {
                $query->where('name', $category);
            })
        )->where('status', true);
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function education()
    {
        return $this->hasMany(Education::class);
    }

    public function certificate()
    {
        return $this->hasMany(Certificate::class);
    }
}
