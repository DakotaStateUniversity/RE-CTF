<?php

namespace App\Http\Controllers;

use Auth;
use App\Http\Requests;
use Illuminate\Http\Request;

  class AdminController extends Controller
  {
    public function index()
    {
      if(Auth::user()->getAdmin())
        return view('admin');
      return redirect('login');
    }
  }

?>
