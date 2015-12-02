<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;
use App\team;
use App\admin;
use App\team_contents;
use App\student_classes;
use App\student_preferences;

class StudentController extends Controller
{

    public function __construct() {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('auth\login');
    }

    public function divideTeam($team, $min, $max){
        //return "Apple";
        // return $team;
        $len = count($team);
        $numTeam = $len/$min;
        $totalTeam = array();
        $team = array_flip($team);
        while(count($team) > 0){
            $t = array();
            while(count($t) < $min){
                array_push($t, array_pop($team));
                array_push($t, array_shift($team));
            }
            array_push($totalTeam, $t );
        }
        return $totalTeam;

    }


    public function generateTeam(Request $request){
        //DB::table('team_contents')->truncate();
        $min = $request->get('min');
        $max = $request->get('max');
        $students_p = student_preferences::all();
        $students_c = student_classes::all();
        $std_j = array();
        $std_p = array();
        $std_c = array();
        $std_u = array();

        foreach ( $students_p as $a) {
            if( $a->java > $a->python and $a->java > $a->c) {
                $std_j[$a->id] = $a->java;
            }
            elseif ( $a->python > $a->c and $a->python > $a-> c) {
                $std_p[$a->id] = $a->python;
            }
            elseif ( $a->c > $a->python and $a->c > $a->java) {
                $std_c[$a->id] = $a->c;
            }else {
                $std_u[$a->id] = $a->c;
            }
        }
        foreach( $students_c as $c){
            if (array_key_exists($c->id, $std_j)) {
                $std_j[$c->id] = $std_j[$c->id] + $c->classID;
            }
            if (array_key_exists($c->id, $std_p)) {
                $std_p[$c->id] = $std_p[$c->id] + $c->classID;
            }
            if (array_key_exists($c->id, $std_c)) {
                $std_c[$c->id] = $std_c[$c->id] + $c->classID;
            }
            if (array_key_exists($c->id, $std_u)) {
                $std_u[$c->id] = $std_u[$c->id] + $c->classID;
            }
        }

        asort($std_j);
        asort($std_c);
        asort($std_p);
        asort($std_u);

        return $this->divideTeam($std_u, $min, $max);
        if( count($std_j) > $max){

        }elseif( count($std_j) < $max and count($std_j) > $min) {

        }

        return $std_u;
    }






    public function studentInfo() {
        $user = \Auth::user();
        $classes = student_classes::where('id','=',$user->id)->get(array('classID'));
        $pref = student_preferences::where('id','=',$user->id)->get();
        return view('student\studentInfo', compact('user', 'classes', 'pref'));

   }

    public function editInfoPage($id) {

    }


    public function homepage() {
        $user = \Auth::user();
        $user_id = $user->id;
        $admin_id = admin::where('id','=',$user_id)->get();
        //return $admin_id;
        //$admin_id = $admin_id[0]->id;

        if(count($admin_id)) {
            $teams = team::all();
            $teamnames = array();
            foreach( $teams as $t) {
                array_push($teamnames, $t->teamName);
            }

            return view('admin',compact('teamnames'));
        } else {
            //return $pref[0]->python;
            // return $user->id;
            $teamcontent = team_contents::where('studentID','=' , $user->id)->get(array('teamID'));
            $teamname = array();
            foreach( $teamcontent as $teamId){
                $name = team::where('teamID', '=', $teamId->teamID)->get(array('teamName'));
                array_push($teamname, $name[0]->teamName);
            }
            // return $teamname;
            // return $user;
            //$team = find($user->)
            return view('home',compact('teamname'), compact('user'));
        }

    }

    public function admin() {
        $teams = team::all()->get();
        $teamnames = array();
        foreach( $teams as $t) {
            array_push($teamnames, $teams[0]->teamName);
        }
        return view('admin',compact('teamnames'));
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
        //
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
    public function update(/*Request $request, $id*/)
    {
        //
        return "Sucks to suck";
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
