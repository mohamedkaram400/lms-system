<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Enrollment extends Model
{
    use HasFactory;

    protected $fillable = ['enrolled_at', 'user_id', 'course_id'];

    public function user() 
    {
        return $this->belongsTo(User::class);
    }

    public function course() 
    {
        return $this->belongsTo(Course::class);
    }
}
