<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Inventory;
use App\Models\Autor;
use App\Models\Editorial;

class Libro extends Model
{
    protected $table = "books";
    protected $fillable = [
        'name', 'description', 'relase_date', 'author_id', 'editorial_id'
    ];

    public function Inventory()
    {
        return $this->hasOne(Inventory::class, 'book_id','id');
    }

    public function Author()
    {
        return $this->belongsTo(Autor::class, 'author_id');
    }

    public function Editorial()
    {
        return $this->belongsTo(Editorial::class, 'editorial_id');
    }
}
