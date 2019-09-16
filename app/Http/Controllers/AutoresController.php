<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Autor;

class AutoresController extends Controller
{
    public function getAuthors()
    {
        return Autor::all();
    }
}
