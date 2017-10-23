<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SiteController extends Controller
{
    public function index() {
        if (Auth::check()) {
            return redirect('/dashboard');
        }
        return view('pages.index');
    } 
}
