<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class StatsController extends Controller
{
    public function challenge_info($chalid)
    {
      $success = DB::table('attempt_log')
      ->where('challenge_id', '=', $chalid)
      ->where('result', '=', 1)
      ->count();
      $fail = DB::table('attempt_log')
      ->where('challenge_id', '=', $chalid)
      ->where('result', '=', 0)
      ->count();
      //$res =  array(['label' => 'Successful Attempts', 'color' => '#0a5b45',  'value' => $success ], ['label' => 'Unsuccessful Attempts', 'color' => '#5b2e0a',  'value' => $fail ]);
      $res = array(['good' => $success, 'bad' => $fail]);
      return json_encode($res);
    }
}
