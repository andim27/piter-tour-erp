<?php


namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Department;

class DepartmentsController  extends Controller
{
    //
    use AuthenticatesUsers;

    protected function getList(Request $request) {

        $token = $this->guard()->attempt($this->credentials($request));

        $user = $request->user();
        $can_controls=[];
        if ($user->can('update-departments')) {
            array_push($can_controls,'edit');
        }
        if ($user->can('delete-departments')) {
            array_push($can_controls,'delete');
        }
        //$can_controls=['edit','delete',$user->name];
        return collect(
            ['success'=>true,
                'data'=>Department::all()->toArray(),
                'controls'=>$can_controls
            ]);//User::all()->toJson();
        //}

        return $request->json(['succes'=>false,'data'=>[]]);
    }
}

