<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Editorial;


class EditorialesController extends Controller
{
    public function getEditorials()
    {
        return Editorial::all();
    }
    
}
