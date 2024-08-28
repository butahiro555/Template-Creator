<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Template extends Model
{   
    // createでrequestを保存できるのは、titleとcontentカラムのみにしておく
    protected $fillable = ['title', 'content'];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

}

