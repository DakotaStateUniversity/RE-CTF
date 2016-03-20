<?php

namespace App\Http\Controllers;
//namespace Carbon\Carbon;

use Illuminate\Http\Request;
use DB;
use Auth;
use Storage;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

//use Symfony\Component\HttpFoundation\File\UploadedFile;

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
      /*
      if(!Auth::Check()){ return -2;}
      if(!Auth::user()->getAdmin())
        return -1;
        $challenges = DB::table('challenge')
        ->orderBy('value')
        ->get();

        return json_encode($challenges);
        */
        return "Method no longer valid.";
    }

    //Attempt to solve a challenge
    public function attempt(Request $request)
    {
      if(!Auth::Check()){ return -2;}
      if(empty($request->input('challenge_id')) || empty($request->input('answer')) )
        return "Please type in a key/flag.";

      // Get the related challenge
      $challenge = DB::table('challenge')
      ->where('challenge_id','=',$request->input('challenge_id'))
      ->first();

      $challengesuccess = DB::table('attempt_log')
      ->where('challenge_id','=',$challenge->challenge_id)
      ->where('user_id','=', Auth::user()->id)
      ->where('result','=',1)
      ->count();
      if($challengesuccess > 0)
      {
        return "You have already completed this challenge.";
      }

      $currentTime = Carbon::now()->toDateTimeString();
      if(password_verify($request->input('answer'), $challenge->key))
      {
        DB::table('attempt_log')->insert(
        ['user_id' => Auth::user()->id, 'datetime' => $currentTime, 'challenge_id' => $request->input('challenge_id') , 'result' => 1]
        );
        return "You have successfully completed this challenge!";
      }
      else {
        DB::table('attempt_log')->insert(
        ['user_id' => Auth::user()->id, 'datetime' => $currentTime, 'challenge_id' => $request->input('challenge_id') , 'result' => 0]
        );
        return "Your answer did not match the flag.";
      }



    }

    //public method of retrieving challenge list, does not reveal key
    public function listall()
    {
      if(!Auth::Check()){ return -2;}
      $challenges = DB::table('challenge')
      ->orderBy('value')
      ->get();
      foreach($challenges as $challenge)
      {
        unset($challenge->key);
        $attempts = DB::table('attempt_log')
              ->where('user_id','=',Auth::user()->id)
              ->where('challenge_id','=',$challenge->challenge_id)
              ->get();
        $completed = 0;
        foreach($attempts as $attempt)
        {
          if($attempt->result == 1)
            $completed = 1;
        }
        $challenge->completed = $completed;
      }
      return json_encode($challenges);
    }

    public function info(Request $request)
    {
      if(!Auth::Check()){ return -2;}
      if(empty($request->input('challenge_id')))
        return 0;

      $challenge = DB::table('challenge')
      ->where('challenge_id','=', $request->input('challenge_id'))
      ->first();

      unset($challenge->key);
      $name = "Miscellaneous";
      if($challenge->category_id != 0 )
      {
        $relcat = DB::table('category')
        ->where('category_id','=',$challenge->category_id)
        ->first();
        $name = $relcat->name;
      }


      $attempts = DB::table('attempt_log')
      ->where('user_id','=',Auth::user()->id)
      ->where('challenge_id','=',$request->input('challenge_id'))
      ->get();
      $completed = 0;
      foreach($attempts as $attempt)
      {
        if($attempt->result == 1)
          $completed = 1;
      }

      $challenge->category_name = $name;
      $challenge->completed = $completed;

      return json_encode($challenge);

    }

    public function info_files(Request $request)
    {
      if(!Auth::Check()){ return -2;}
      if(empty($request->input('chalid')))
        return 0;
      $files = Storage::allFiles("challenges/" . $request->input('chalid'));
      $i = 0;
      foreach($files as $file)
      {
        $filepieces = explode("/", $file);
        $files[$i] = $filepieces[2];
        $i++;
      }
      return json_encode($files);
      //json_encode($files, JSON_UNESCAPED_SLASHES)
    }
    //This retrieves all information about the challenge, INCLUDING the key/flag
    //It is important that this remains admin-only
    public function data(Request $request)
    {
      if(!Auth::Check()){ return -2;}
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
      if(!Auth::Check()){ return -2;}
      if(!Auth::user()->getAdmin())
        return -1;
      if( empty($request->input('challenge_name')) || empty($request->input('value')) || empty($request->input('key')) || empty($request->input('description')) || ($request->input('category_id') != 0 && empty($request->input('category_id'))) )
        return 0;
      $key = password_hash($request->input('key'), PASSWORD_DEFAULT);
      DB::table('challenge')->insert(array('challenge_name'=>$request->input('challenge_name'), 'value' => $request->input('value'), 'key' => $key, 'description' => $request->input('description'), 'category_id' => $request->input('category_id')));

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
      if(!Auth::Check()){ return -2;}
      if(!Auth::user()->getAdmin())
        return -1;
      if( empty($request->input('chalid')) || empty($request->input('name')) )
        return 0;

      DB::table('challenge')
        ->where('challenge_id', '=', $request->input('chalid'))
        ->update(array('name' => $request->input('name')));

      return $request->input('name');
    }

    public function modify_hash(Request $request)
    {
      if(!Auth::Check()){ return -2;}
      if(!Auth::user()->getAdmin())
        return -1;
      if( empty($request->input('chalid')) || empty($request->input('pass')) )
        return 0;

      $pass = password_hash($request->input('pass'), PASSWORD_DEFAULT);
      DB::table('challenge')
      ->where('challenge_id', '=', $request->input('chalid'))
      ->update(array('key' => $pass));
    }

    public function modify(Request $request)
    {
      if(!Auth::Check()){ return -2;}
      if(!Auth::user()->getAdmin())
        return -1;
      if( empty($request->input('challenge_id')) || empty($request->input('challenge_name')) || empty($request->input('value')) || empty($request->input('description')) || ($request->input('category_id') != 0 && empty($request->input('category_id'))) )
        return 0;

      DB::table('challenge')
      ->where('challenge_id','=',$request->input('challenge_id'))
      ->update(array('challenge_name'=>$request->input('challenge_name'), 'value' => $request->input('value'), 'description' => $request->input('description'), 'category_id' => $request->input('category_id')));

    }

    public function file_get($chalid, $filename)
    {
      if(!Auth::Check()){ return -2;}
      if(empty($filename) || empty($chalid))
        return 0;

      if(!Storage::has("challenges/" . $chalid . "/" . $filename))
        return "File not found.";
      $content = Storage::get("challenges/" . $chalid . "/" . $filename);
      return response($content, 200)
      ->header('Content-Type', "application/octet-stream");

      // TODO: Add file retrieval for challenges
    }

    public function file_put(Request $request)
    {
      if(!Auth::Check()){ return -2;}
      if(!Auth::user()->getAdmin())
        return -1;
      if(empty($request->input('chalid_file')))
        return 0;
      if($request->hasFile('fileUpload'))
      {
        $file = $request->file('fileUpload');

      //  $file->move(public_path() . '/challengefiles/' . $request->input('chalid_file'), $file->getClientOriginalName());
      Storage::put("challenges/" . $request->input('chalid_file') . "/" . $file->getClientOriginalName(), file_get_contents($file));
      return redirect('admin');
      }
      echo "No file received\n";
      var_dump($request->all());


    }

    public function file_remove($chalid, $filename)
    {
      if(!Auth::Check()){ return -2;}
      if(!Auth::user()->getAdmin())
        return -1;
      if(empty($filename) || empty($chalid))
        return 0;

      Storage::delete("challenges/" . $chalid . "/" . $filename);

      return $filename . " has been deleted.";

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
        if(empty($request->input('challenge_id')))
          return 0;
        Storage::deleteDirectory("challenges/" . $request->input('challenge_id'));
        DB::table('attempt_log')
        ->where('challenge_id', $request->input('challenge_id'))
        ->delete();

        DB::table('challenge')
        ->where('challenge_id', $request->input('challenge_id'))
        ->delete();

        return 1;

    }
}
