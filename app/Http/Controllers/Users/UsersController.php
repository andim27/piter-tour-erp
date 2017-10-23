<?php

namespace App\Http\Controllers\Users;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\User;//---my---
use App\Department;//---my---

class UsersController extends Controller
{
    //
    use AuthenticatesUsers;

    protected function getList(Request $request)
    {
        //$token = $this->guard()->attempt($this->credentials($request));

        //if ($token) {
        $user = $request->user();
        $can_controls=[];
        if ($user->can('update-users')) {
            array_push($can_controls,'edit');
        }
        if ($user->can('delete-users')) {
            array_push($can_controls,'delete');
        }
        //$can_controls=['edit','delete',$user->name];
            return collect(
                ['success'=>true,
                 'data'=>User::all()->toArray(),
                 'controls'=>$can_controls
                ]);//User::all()->toJson();
        //}

        return $request->json(['succes'=>false,'data'=>[]]);
    }
    public function deactivate(Request $request)
    {
        $this->validate($request, [
            'id' => 'required',
            'active'=>'required'
        ]);
        $user=User::select('id','name','active')->where(['id'=>$request['id']])->first();
        if ($user) {
            $user->active = 0;
            $user->save();
        }
//        if ($user->active != $request['active']) {
//            $user->active = $request['active'];
//            $user->save();
//        }
        return $user;
    }
    public function update(Request $request)
    {
        //$user = $request->user();
        //return $request['name'];
        //return $request['user_id'];
        $input =$request->all();
        $this->validate($request, [
            'name' => 'required',
            'id'=>'required'
        ]);
        //$user=User::find($request['user_id'])->first();
        $this->hidden=['password'];
        $user=User::where(['id'=>$request['id']])->first();
        foreach ($input as $key=>$value) {
            if ($user[$key] != $value) {
                $user[$key] = $value;
            }
        }
        @$user->department=Department::where(['id'=>$input['department_id']])->first()->name;;
        $user->save();

        return $user;
        //return collect(['success'=>true,'data'=>['id'=>$user->id]]);
        //return tap($user)->update($request->only('name', 'email','userfio','position','department_id','tel','tel_work','info'));
    }
    protected function create(Request $request) {
        $v=$this->validate($request, [
            'name' => 'required|max:55',
            'email' => 'required|email|max:255|unique:users',
            'department' =>'required',
            'position' =>'required'
        ]);
        $u=[];
        $res=false;
        if ($v) {
            $u=new User();
            $u->name=$request['name'];
            $u->userfio=$request['userfio'];
            $u->email=$request['email'];
            $u->department_id=$request['department'];
            @$u->department = Department::where(['id'=>$request['department']])->first()->name;
            $u->position=$request['position'];
            $u->tel=$request['tel'];
            $u->tel_work=$request['tel_work'];
            $u->info=$request['info'];

            $u->save();
           ;
            $u->toArray();
            $res=true;
        }

        return collect(
            ['success' =>$res, 'data' =>$u
            ]
        );
    }
}
