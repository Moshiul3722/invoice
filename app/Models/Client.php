<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use HasFactory, SoftDeletes;


    // protected $fillable = ['name','email']; this means which columns I need to fill/access data

    //protected $guarded = ['id','created_at','updated_at'];  this means which columns are not access for next assignment


    protected $guarded = [];

    public function tasks()
    {
        return $this->hasMany(Task::class, 'client_id', 'id');
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'client_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
