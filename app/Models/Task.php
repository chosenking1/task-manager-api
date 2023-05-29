<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;


class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'department_id',
        'name',
        'description',
        'status',
        'approved'


    ];

    public function user(){
        return $this-> belongsTo(User::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
    
}
