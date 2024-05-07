<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Classroom extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = ['id'];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }
}
