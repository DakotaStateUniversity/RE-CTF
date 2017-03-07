<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        if(!Auth::Check()){ return -2;}
        if(app('App\Http\Controllers\CompetitionController')->available() != 1)
        {
          if(!Auth::user()->getAdmin())
            return "Not available";
        }
        $categories = DB::table('category')
        ->orderBy('level')
        ->get();
        return json_encode($categories);
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
      if(!Auth::Check()){ return -2;}
      if(!Auth::user()->getAdmin())
        return -1;
      if(empty($request->input('name')))
        return 0;

      $currentcount = count(DB::table('category')->get());
      if($currentcount == 0)
        $currentcount = 1;
      DB::table('category')->insert(array('name'=>$request->input('name'), 'level' => $currentcount));

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
    public function update(Request $request)
    {
      if(!Auth::Check()){ return -2;}
      if(!Auth::user()->getAdmin())
        return -1;
      if($request->input('data') == null || $request->input('data') == "[]")
        return 0;

      $data = json_decode($request->input('data'));
      foreach($data as $row)
      {
        DB::table('category')
        ->where('category_id',$row->catid)
        ->update(array('level' => $row->level));
      }

      return 1;
    }

    public function modify(Request $request)
    {
      if(!Auth::Check()){ return -2;}
      if(!Auth::user()->getAdmin())
        return -1;
      if( empty($request->input('catid')) || empty($request->input('name')) )
        return 0;

      DB::table('category')
        ->where('category_id',$request->input('catid'))
        ->update(array('name' => $request->input('name')));

      return $request->input('name');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if(!Auth::Check()){ return -2;}
        if(!Auth::user()->getAdmin())
          return -1;
        if(empty($request->input('catid')))
          return 0;

        DB::table('category')
        ->where('category_id', $request->input('catid'))
        ->delete();

        DB::table('challenge')
        ->where('category_id', $request->input('catid'))
        ->update(array('category_id' => 0));

        return 1;

    }
}
