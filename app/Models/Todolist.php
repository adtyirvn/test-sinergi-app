<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Todolist extends Model
{
    use HasFactory;
    protected $table = 'todolists';
    protected $fillable = ['note', 'complete', 'user_id'];
    public $timestamps = false;
}
