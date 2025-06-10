<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    protected $fillable = [
        'user_id', 
        'judul', 
        'deskripsi', 
        'status',
        'jenis',        
        'feedback',      
        'id_komplain'     
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
}