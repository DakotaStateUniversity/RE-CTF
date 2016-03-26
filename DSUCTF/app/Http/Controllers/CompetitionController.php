<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class CompetitionController extends Controller
{
    public function available()
    {
      date_default_timezone_set("America/Chicago");
      $dbstart = DB::table('config')->where('name','=','competition_start')->first();
      $dbend = DB::table('config')->where('name','=','competition_end')->first();
      $startclear = 0;
      $endclear = 0;
      // If both are null
      if($dbstart->val_datetime == '0000-00-00 00:00:00')
        $startclear = 1;
      if($dbend->val_datetime == '0000-00-00 00:00:00')
        $endclear = 1;
      if($startclear == 1 & $endclear == 1)
        return 1;


      // If both are valid dates, see if we're valid
      if($startclear == 0 && $endclear == 0)
      {
        $cstart = new Carbon($dbstart->val_datetime);
        $cend = new Carbon($dbend->val_datetime);
        $now = new Carbon();
        if($now->between($cstart, $cend))
          return 1;
      }
      if($endclear == 0)
      {
        $cend = new Carbon($dbend->val_datetime);
        if($cend->gte(Carbon::now()))
          return 1;
        return "The competition is over.";
      }
      if($startclear == 0)
      {
        $cstart = new Carbon($dbstart->val_datetime);
        if($cstart->lte(Carbon::now()))
          $startclear = 1;
        return "The competition does not start until " . $cstart;
      }

    }

    public function update_time(Request $request)
    {
      if(!Auth::Check()){ return -2;}
      if(!Auth::user()->getAdmin())
        return -1;
      if(empty($request->input('competition_start')) || empty($request->input('competition_end')))
        return 0;
      $newstart = new Carbon($request->input('competition_start'));
      $newend = new Carbon($request->input('competition_end'));

      if($newstart->gte($newend))
      {
        DB::table('config')->where('name','=','competition_start')
        ->update(['val_datetime' => $newstart]);
        DB::table('config')->where('name','=','competition_end')
        ->update(['val_datetime' => '0000-00-00 00:00:00']);
      }
      else
      { // Verbose else
        DB::table('config')->where('name','=','competition_start')
        ->update(['val_datetime' => $newstart]);
        DB::table('config')->where('name','=','competition_end')
        ->update(['val_datetime' => $newend]);
      }


      return 1;
    }

    public function start_time()
    {
      return new Carbon(DB::table('config')->where('name','=','competition_start')->first()->val_datetime);
    }
    public function end_time()
    {
      return new Carbon(DB::table('config')->where('name','=','competition_end')->first()->val_datetime);
    }
}
