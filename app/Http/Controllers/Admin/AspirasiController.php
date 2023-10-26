<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AspirasiController extends Controller
{
    public function index()
    {
        return view("pages.aspirasi.index");
    }
}
