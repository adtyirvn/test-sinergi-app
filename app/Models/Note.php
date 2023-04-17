<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use HasFactory;
    protected $fillable = ['note', 'user_id'];
    protected $table = 'notes';
    public $timestamps = false;
    // protected $attributes = [
    //     'user_id' => 'user',
    // ];
}
