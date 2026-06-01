<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $fillable = [
        'member_id',
        'name',
        'email',
        'phone',
        'address'
    ];

    public function borrowings()
    {
        return $this->hasMany(Borrowing::class);
    }
}
