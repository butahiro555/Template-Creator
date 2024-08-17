<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable; // 追加

class Template extends Model
{   
    // createでrequestを保存できるのは、titleとcontentカラムのみにしておく
    protected $fillable = ['title', 'content'];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    use Sortable; // 追加
    
    public $sortable = ['created_at', 'updated_at']; // ソート対象カラム追加
}

