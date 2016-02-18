<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class ChallengeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    //This retrieves all information about the challenge, INCLUDING the key/flag
    //It is important that this remains admin-only
    public function index()
    {
      Auth::check();
      if(!Auth::user()->getAdmin())
        return -1;
        $challenges = DB::table('challenge')
        ->orderBy('value')
        ->get();
        return json_encode($challenges);
    }
    //This retrieves all information about the challenge, INCLUDING the key/flag
    //It is important that this remains admin-only
    public function data(Request $request)
    {
      Auth::check();
      if(!Auth::user()->getAdmin())
        return -1;
      if(empty($request->input('challenge_id')))
        return 0;

      $challenge = DB::table('challenge')
      ->where('challenge_id','=', $request->input('challenge_id'))
      ->get();

      return json_encode($challenge);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      Auth::check();
      if(!Auth::user()->getAdmin())
        return -1;
      if( empty($request->input('challenge_name')) || empty($request->input('value')) || empty($request->input('key')) || empty($request->input('description')) || ($request->input('category_id') != 0 && empty($request->input('category_id'))) )
        return 0;

      DB::table('challenge')->insert(array('challenge_name'=>$request->input('challenge_name'), 'value' => $request->input('value'), 'key' => $request->input('key'), 'description' => $request->input('description'), 'category_id' => $request->input('category_id')));

      return 1;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function modify_name(Request $request)
    {
      Auth::check();
      if(!Auth::user()->getAdmin())
        return -1;
      if( empty($request->input('chalid')) || empty($request->input('name')) )
        return 0;

      DB::table('challenge')
        ->where('challenge_id',$request->input('chalid'))
        ->update(array('name' => $request->input('name')));

      return $request->input('name');
    }

    public function modify(Request $request)
    {
      Auth::check();
      if(!Auth::user()->getAdmin())
        return -1;
      if( empty($request->input('challenge_id')) || empty($request->input('challenge_name')) || empty($request->input('value')) || empty($request->input('key')) || empty($request->input('description')) || ($request->input('category_id') != 0 && empty($request->input('category_id'))) )
        return 0;

      DB::table('challenge')
      ->where('challenge_id','=',$request->input('challenge_id'))
      ->update(array('challenge_name'=>$request->input('challenge_name'), 'value' => $request->input('value'), 'key' => $request->input('key'), 'description' => $request->input('description'), 'category_id' => $request->input('category_id')));

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        Auth::check();
        if(!Auth::user()->getAdmin())
          return -1;
        if(empty($request->input('challenge_id')))
          return 0;

        DB::table('challenge')
        ->where('challenge_id', $request->input('challenge_id'))
        ->delete();

        return 1;

    }
}
