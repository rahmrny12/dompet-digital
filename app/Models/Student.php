<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    public function parent()
    {
        return $this->belongsTo(StudentParent::class);
    }

    public function balance()
    {
        return $this->hasOne(StudentBalance::class);
    }

    public function balance_setting()
    {
        return $this->hasOne(BalanceSetting::class);
    }
}
