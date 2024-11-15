<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Template extends Model
{   
    use HasFactory;

    // createでrequestを保存できるのは、titleとcontentカラムのみにしておく
    protected $fillable = ['title', 'content', 'user_id'];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}