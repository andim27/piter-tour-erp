<?php

namespace App\Http\Controllers\Bitrix;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
//use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Storage;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use App\Bitrix;
use App\User;
use Log;
use Exception;

class EventController extends Controller
{
//    use RegistersUsers;
//    //
//    public function __construct()
//    {
//        $this->middleware('guest');
//    }
    /**
     * Create the user.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function authCallback(Request $request)
    {
        if ($request['auth']) {
            Log::info('authcallback: token='.$request['auth']['access_token']);
            if ($request['auth']['access_token']) {
                $bitrix_app=config('bitrix.app.app_name');
                $b=Bitrix::where(['app_name'=>$bitrix_app])->first();
                $b->access_token=$request['auth']['access_token'];
                $b->save();
                return ['Bitrix connected...'];
            }
        }
    }
    public function authRefresh(Request $request)
    {
        $bitrix_app=config('bitrix.app.app_name');
        $redirect_url=config('bitrix.app.redirect_uri');

        $b=Bitrix::where(['app_name'=>$bitrix_app])->first();

        $curlRefresh = curl_init();
        $url=$bitrix_app . "bitrix24.ru/oauth/token/?client_id=".$b->client_id."&grant_type=refresh_token&client_secret=".$b->client_secret."&redirect_uri=".$redirect_url."&refresh_token=" . $b->refresh_token;

        curl_setopt_array($curlRefresh, array(
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_HEADER => 0,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $url
        ));

        $resultRefresh = curl_exec($curlRefresh);
        curl_close($curlRefresh);
        Log::info("authRefreshToken: url=".$url);
        Log::info("authRefreshToken: resultRefresh=".$resultRefresh);
        $resultRefresh = json_decode($resultRefresh, true);

        if ($resultRefresh["access_token"]) {
            $b->access_token = $resultRefresh["access_token"];
            $b->refresh_token = $resultRefresh["refresh_token"];
            $b->save();
        }



        return redirect("/home");
    }
    public function auth(Request $request)
    {
        $bitrix_app=config('bitrix.app.app_name');
        $client_id=config('bitrix.app.client_id');
        $client_secret=config('bitrix.app.client_secret');
        $redirect_url=config('bitrix.app.redirect_uri');
        try {
            $access_token = $request['access_token'];
            if ($access_token) {
                $b=Bitrix::where(['app_name'=>$bitrix_app])->first();
                $b->access_token=$access_token;
                $b->save();
                Log::info('Auth YES: token='.$access_token);
                return ['bitrix ok'];
            }

        } catch(Exception  $e) {
            Log::info('Auth error:'.$e->getMessage());
        }
        $code=$request['code'];
        if ($code) {
            $b=Bitrix::where(['app_name'=>$bitrix_app])->first();
            $b->code=$code;
            $b->save();
            $url_2="https://oltatravel.bitrix24.ru/oauth/token/?grant_type=authorization_code&client_id=".$client_id."&client_secret=".$client_secret."&redirect_uri=http://olta-tour-new.local/bitrix/auth&scope=calendar,crm,task,user&code=".$code;
            Log::info("Bitrix code=".$code);
            Log::info("Bitrix url_2=".$url_2);
            //echo "<br> code=".$code;
            //echo "<br> url_2=".$url_2;
            sleep(1);

            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_SSL_VERIFYPEER => 0,
                CURLOPT_HEADER => 0,
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_URL => $url_2
            ));

            $result = curl_exec($curl);
            curl_close($curl);
            //echo "<br> Curl url_2 Result=".$result;
            $result = json_decode($result, true);
            if ($result['access_token']) {
                $b=Bitrix::where(['app_name'=>$bitrix_app])->first();
                $b->access_token=$result['access_token'];
                $b->refresh_token=$result['refresh_token'];
                $b->save();
                Log::info('Auth YES: access_token='.$result['access_token']);
                Log::info('Auth YES: refresh_token='.$result['access_token']);
                return redirect('/home');;
            }

            return $result;
        } else {

            $url_1="https://".$bitrix_app.".bitrix24.ru/oauth/authorize/?response_type=code&client_id=".$client_id."&redirect_uri=".$redirect_url;
            return redirect($url_1);
            Log::info("Auth 1=".$url_1);
        }

        return;

    }
    public function auth_2(Request $request)
    {   //--token here---
        try {
            $access_token = $request['access_token'];
            if ($access_token) {
                $bitrix_app=config('bitrix.app.app_name');
                $b=Bitrix::where(['app_name'=>$bitrix_app])->first();
                $b->access_token=$access_token;
                $b->save();
                Log::info('Auth YES: token='.$access_token);
                return ['bitrix ok'];
            }

        } catch(Exception  $e) {
            Log::info('Auth error:'.$e->getMessage());
        }


    }
    public function code(Request $request)
    {
        return $request;
    }
    public function syncNewBitrixUsers(Request $request)
    {
        //$user_id=$request['user_id'];
        $b=new Bitrix();
        $token=$b::find(1)->first()->access_token;
        $res=$b->getRemoteUsers($token);
        //return $res;
        if (count($res)<=0 ) {
            $res=['errors'=>['mes'=>'syncNewBitrixUsers - no res','error_description'=>$res['error_description']]];
            return $res;
        }
        if (!empty($res['error'])) {
            return $res;
        }
        $remote_users=$res['result'];
        if ($remote_users) {
            $res=$b->addNewBitrixUsers($remote_users);
            $res=$b->updateUsersFromBitrixUsers($remote_users);
        }

        return $res;
    }
    public function syncUser(Request $request)
    {
        $res=[];
        $v=$this->validate($request, [
            'user_id' => 'required'
        ]);
        if ($v) {
            $user_id=$request['user_id'];
            //return ['bitrix_client_id'=>config('bitrix.app.client_id'),'client_secret_id'=>config('bitrix.app.client_secret')];
        }
//
//        return $res;
        $res=$this->getBitrixUser($request);
        //return $res;
        if ((count($res)<=0)) {
            $res=['errors'=>['mes'=>'SyncUser - no res','error_description'=>$res['error_description']]];
            return $res;
        }
        if (!empty($res['error'])) {
            return $res;
        }
        if ($res['result']) {
            $this->changeUserByBitrix($user_id,$res['result'][0]);
        }
    }
    public function createContact(Request $request)
    {
        Storage::append('bitrix/contacts.log', "------Create:".now()->jsonSerialize()['date']."----------");
        Storage::append('bitrix/contacts.log', $request);
        //return ['ok'];
        Log::info('--------------Bitrix createContruct:'.now()->jsonSerialize()['date']);
        Log::info($request);
    }
    /**
     * Update the user's fields.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function updateContact(Request $request)
    {
        Storage::append('bitrix/contacts.log', "------Update:".now()->jsonSerialize()['date']."----------");
        Storage::append('bitrix/contacts.log', $request);
        //return ['ok'];
        Log::info('--------------Bitrix updateContruct:'.now()->jsonSerialize()['date']);
        Log::info($request);
    }
    public function getBitrixUser(Request $request)
    {  $res=[];
        try {
            $user_id=$request['user_id'];
            $b=new Bitrix();
            $token=$b::find(1)->first()->access_token;
            $bitrix_user_id = User::where(['id'=>$user_id])->first()->bitrix_id;
            if ($bitrix_user_id) {
                $res=$b->getRemoteUser($bitrix_user_id,$token);
                Log::info('getBitrixUser id='.$user_id);
            } else {
                $res=['errors'=>['mes'=>'No bitrix_id...']];
                Log::info('ERROR:getBitrixUser id='.$user_id." No bitrix_id");
            }

            //--if user exist
           //--sync fields---
        } catch(Exception $e) {
            $res=['errors'=>['mes' => $e->getMessage(),'line'=>$e->getLine(),'code'=>$e->getCode()]];
            Log::info('ERROR:getBitrixUser id='.$user_id.' token:'.$token);
        }

        return $res;
    }
    public function changeUserByBitrix($user_id,$fields_arr)
    {
        $user=User::where(['id'=>$user_id])->first();
        if ($user) {
            $user->name=$fields_arr['NAME'];
            $user->userfio=$fields_arr['LAST_NAME']." ".$fields_arr['NAME']." ".$fields_arr['SECOND_NAME'];
            if ($user->email ==$fields_arr['EMAIL']) {
                $user->info="\n\nBirthDay:".$fields_arr['PERSONAL_BIRTHDAY'];
            } else {
                $user->info=$user->info."\n\nNew Email:".$fields_arr['EMAIL'];
            }
            if ($fields_arr['PERSONAL_MOBILE']) {
                $user->tel=$fields_arr['PERSONAL_MOBILE'];
            }

            $user->save();
        }
    }
}
