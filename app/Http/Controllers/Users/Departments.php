<?php

namespace App\Http\Controllers\Users;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Validator;
use App\Department;
use App\User;
use Log;
class Departments extends Controller
{
    //
    use AuthenticatesUsers;

    protected function getUsers(Request $request) {
        $token = $this->guard()->attempt($this->credentials($request));
        if (empty($token)) {
            Log::info("!!! -- Departments/getUsers NO TOKEN -- !!!!!");
        }
        try {
            if ($request['department_id']) {
                Log::info('Department/getUsers department_id='.$request['department_id']);
                $users=User::select('id','userfio')->where(['department_id'=>$request['department_id']])->get();
                return $users;
            }
        } catch (\Exception $e) {
            return ['error'=>$e->getMessage()];
        }

        return ['error'=>'No department_id'];
    }

    protected function getList(Request $request) {

        $token = $this->guard()->attempt($this->credentials($request));
        if (empty($token)) {
            Log::info("!!! -- Departments/getList NO TOKEN -- !!!!!");
        }
        $user = $request->user();
        $can_controls=[];
        if ($user->can('update-department')) {
            array_push($can_controls,'edit');
        }
        if ($user->can('delete-department')) {
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
    protected function create(Request $request) {
        $v=$this->validate($request, [
            'name' => 'required|max:55'
        ]);
        $department=[];
        $res=false;
        if ($v) {
           $dep=new Department();
           $dep->name=$request['name'];
           $dep->save();
           $res=true;
         }

        return collect(
            ['success' =>$res, 'data' =>$department
            ]
        );
    }
}
