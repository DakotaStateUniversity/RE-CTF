<?php

namespace App\Http\Controllers;
//namespace Carbon\Carbon;

use Illuminate\Http\Request;
use DB;
use Auth;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class ScoreController extends Controller
{

  public function scoreview()
  {
    return view('scoreboard');
  }

  public function get_score($userid)
  {
    $completed = DB::table('attempt_log')
    ->where('user_id', '=', $userid)
    ->where('result', '=', 1)
    ->get();
    $totalscore = 0;
    foreach($completed as $complete)
    {
      $chal = DB::table('challenge')
      ->where('challenge_id', '=', $complete->challenge_id)
      ->first();
      $totalscore += $chal->value;
    }
    return $totalscore;
  }

  public function totalscore()
  {
    if(!Auth::Check())
      return -2;
    $totalscore = $this->get_score(Auth::user()->id);
    return $totalscore;
  }

  public function scoreboard()
  {
    $useridlist = array();
    $userlist = array();
    $attempts = DB::table('attempt_log')
    ->where('result', '=', 1)
    ->get();

    foreach($attempts as $attempt)
    {
      if(!in_array($attempt->user_id, $useridlist))
        array_push($useridlist, $attempt->user_id);
    }
    foreach($useridlist as $userid)
    {
      $user = (object) null;
      $user->score = $this->get_score($userid);
      $user->name = DB::table('users')->where('id', '=', $userid)->first()->name;
      array_push($userlist, $user);
    }
    usort($userlist, array('App\Http\Controllers\ScoreController', 'usercompare'));
    echo json_encode($userlist);
  }

  public static function usercompare($u1, $u2)
  {
    if($u1->score == $u2->score)
    {
      return 0;
    }
    return ($u1->score > $u2->score) ? -1 : 1;
  }



}
