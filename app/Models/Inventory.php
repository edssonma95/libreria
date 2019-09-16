<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Book;

class Inventory extends Model
{
    protected $table = "inventory";

    public function Book()
    {
        return $this->belongsTo(Book::class, 'id','book_id');
    }
}
