<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpParser\Node\Stmt\Return_;

class DashboardController extends Controller
{
    public function index(){
        return view('dashboard.menu');
    }
}
