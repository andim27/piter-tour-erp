<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Log;
class Bitrix extends Model
{
    private $bitrix_app = "oltatravel";
    private $bitrix24 = "https://oltatravel.bitrix24.ru";
    private $client_id = "local.59e0d3016e6768.48540522";
    private $client_secret = "Nqx0DPafZ8Ffu2mW1TR2qJdq8bCzzR5IMN9dFM5BgDn1hNaSrn";

    private $scope = "crm,user";

    protected $access_token;
    protected $refresh_token;
    protected $result;
    protected $resultRefresh;
    //
    /**
     * Get new authorize data if you authorize is expire.
     *
     * @param array $auth - Authorize data, ex: Array('domain' => 'https://test.bitrix24.com', 'access_token' => '7inpwszbuu8vnwr5jmabqa467rqur7u6')
     * @return bool|mixed
     */
    public  function init()
    {
        $this->bitrix_app = config('bitrix.app.app_name');
        $this->client_id = config('bitrix.app.client_id');
        $this->client_secret = config('bitrix.app.client_secret');
        $this->scope = config('bitrix.app.scope');
        $this->bitrix24=config('bitrix.app.app_name').".bitrix24.ru";
    }
    public static function restAuth()
    {
        $bitrix_app = config('bitrix.app.app_name');
        $client_id = config('bitrix.app.client_id');
        $client_secret = config('bitrix.app.client_secret');
        $refresh_token = Bitrix::find(1)->first()->access_token;
        $auth = array();
        $auth['domain'] = $bitrix_app.".bitrix24.ru";
        $auth['refresh_token'] = $refresh_token;
        $auth['scope']=config('bitrix.app.scope');
        //$auth['domain']="oltatravel.bitrix24.ru";
        if (!$client_id || !$client_secret)
            return false;

        if (!isset($auth['refresh_token']) || !isset($auth['scope']) || !isset($auth['domain']))
            return false;

        $queryUrl = 'https://' . $auth['domain'] . '/oauth/token/';
        $queryData = http_build_query($queryParams = array(
            'grant_type' => 'refresh_token',
            'client_id' => $client_id,
            'client_secret' => $client_secret,
            'refresh_token' => $auth['refresh_token'],
            'scope' => $auth['scope'],
        ));

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_HEADER => 0,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $queryUrl . '?' . $queryData,
        ));

        $result = curl_exec($curl);
        curl_close($curl);
        Log::info('Bitrix -restAuth url='.$queryUrl);
        Log::info('Bitrix -restAuth data='.$queryData);
        $result = json_decode($result, 1);

        return $result;
    }
    public  function queryRest($method, $params,$token)
    {
        $params["auth"] = $token;
        $curlQuery = curl_init();
        $queryData = http_build_query($params);
        curl_setopt_array($curlQuery, array(
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_POST => 1,
            CURLOPT_HEADER => 0,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => "https://oltatravel.bitrix24.ru/rest/" . $method . ".json", // crm.deal.list
            CURLOPT_POSTFIELDS => $queryData,
        ));

        $resultQuery = curl_exec($curlQuery);
        curl_close($curlQuery);

        $resultQuery = json_decode($resultQuery, true);
        $this->resultQuery = $resultQuery;

        return $this->resultQuery;
    }
    public function getRemoteUser($user_id,$token)
    {
        //https://oltatravel.bitrix24.ru/rest/user.get.json?ID=1&auth=lnle90drll8tuqxg88kc43ztdi5pi0o7
        $params["auth"] = $token;
        $params["ID"] = $user_id;
        $res=$this->queryRest('user.get',$params,$token);
        return $res;
    }
    public function getRemoteUsers($token)
    {
        //https://oltatravel.bitrix24.ru/rest/user.get.json?ID=1&auth=lnle90drll8tuqxg88kc43ztdi5pi0o7
        $params["auth"] = $token;
        //$params["ID"] = $user_id;
        $res=$this->queryRest('user.get',$params,$token);
        return $res;
    }
    public function addNewBitrixUsers($bitrix_users_arr)
    {
        if (!$bitrix_users_arr){return false;}
        $new_users_arr=[];
        for ($i=0;$i<count($bitrix_users_arr);$i++  ) {
            $b_user_email=$bitrix_users_arr[$i]['EMAIL'];
            $user=User::where(['email'=>$b_user_email])->first();
            if (!$user) { //--This is new user
                $user_new= new User();
                $user_new->name=$bitrix_users_arr[$i]['NAME'];
                $user_new->email=$bitrix_users_arr[$i]['EMAIL'];
                $user_new->bitrix_id=$bitrix_users_arr[$i]['ID'];
                $user_new->userfio=$bitrix_users_arr[$i]['LAST_NAME'].' '.$bitrix_users_arr[$i]['NAME'];
                $user_new->tel=$bitrix_users_arr[$i]['PERSONAL_MOBILE'];
                $user_new->tel_work=$bitrix_users_arr[$i]['WORK_PHONE'];
                $user_new->position=$bitrix_users_arr[$i]['WORK_POSITION'];
                $user_new->active=$bitrix_users_arr[$i]['ACTIVE']?1:0;
                if ($bitrix_users_arr[$i]['PERSONAL_BIRTHDAY']) {
                    $user_new->info='BirthDay:'.$bitrix_users_arr[$i]['PERSONAL_BIRTHDAY'];
                }
                $user_new->save();
                array_push($new_users_arr,$bitrix_users_arr[$i]);
            }

        }
        return $new_users_arr;
    }
    public function updateUsersFromBitrixUsers($bitrix_users_arr)
    {
        //return $bitrix_users_arr;
        if (!$bitrix_users_arr){return false;}
        $new_users_arr=[];
        try {
            for ($i=0;$i<count($bitrix_users_arr);$i++  ) {
                $b_user_email=$bitrix_users_arr[$i]['EMAIL'];
                $user=User::where(['email'=>$b_user_email])->first();
                if ($user) { //--This is  user

                    if ($user->name != $bitrix_users_arr[$i]['NAME']) {
                        $user->name=$bitrix_users_arr[$i]['NAME'];
                    }
                    if ($user->bitrix_id !=$bitrix_users_arr[$i]['ID'] ) {
                        $user->bitrix_id=$bitrix_users_arr[$i]['ID'];
                    }
                    //$user->email=$bitrix_users_arr[$i]['EMAIL'];

                    $user->userfio=$bitrix_users_arr[$i]['LAST_NAME'].' '.$bitrix_users_arr[$i]['NAME'];

                    if ($user->tel != $bitrix_users_arr[$i]['PERSONAL_MOBILE']) {
                        $user->tel=$bitrix_users_arr[$i]['PERSONAL_MOBILE'];
                    }
                    if ($user->tel_work != $bitrix_users_arr[$i]['WORK_PHONE'] ) {
                        $user->tel_work=$bitrix_users_arr[$i]['WORK_PHONE'];
                    }
                    if ($user->position != $bitrix_users_arr[$i]['WORK_POSITION']) {
                        $user->position=$bitrix_users_arr[$i]['WORK_POSITION'];
                    }
                    //if ($user->department != $bitrix_users_arr[$i]['UF_DEPARTMENT']) {
                        //$user->department=$bitrix_users_arr[$i]['UF_DEPARTMENT'];//--Attention! This is array!
                        //$user->info= $user->info+";bitrix:dep_id="+(String)$bitrix_users_arr[$i]['UF_DEPARTMENT'][0];
                    //}
                    if ($user->skype != $bitrix_users_arr[$i]['UF_SKYPE'] ) {
                        $user->skype=$bitrix_users_arr[$i]['UF_SKYPE'];
                    }
                    if ($user->interests != $bitrix_users_arr[$i]['UF_INTERESTS'] ) {
                        $user->skype=$bitrix_users_arr[$i]['UF_INTERESTS'];
                    }
                    if ($user->photo != $bitrix_users_arr[$i]['PERSONAL_PHOTO'] ) {
                        $user->photo=$bitrix_users_arr[$i]['PERSONAL_PHOTO'];
                    }
                    if ($bitrix_users_arr[$i]['PERSONAL_BIRTHDAY']) {
                        $user->birthday=$bitrix_users_arr[$i]['PERSONAL_BIRTHDAY'];
                    }
                    $user->address=$bitrix_users_arr[$i]['PERSONAL_CITY'];//PERSONAL_CITY
                    $user->active=$bitrix_users_arr[$i]['ACTIVE']?1:0;
                    $user->save();
                    array_push($new_users_arr,$bitrix_users_arr[$i]);
                }

            }
        } catch (Exeption $e) {
            $new_users_arr=['error'=>$e->getMessage(),'error_description'=>'error line:'.$e->getLine()];
        }

        return $new_users_arr;
    }

}
