<?php

namespace App\Http\Controllers\Tours;

use App\Tour;
use App\TourProgram;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Response;
use Log;
class TourController extends Controller
{
    use AuthenticatesUsers;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $data=Tour::all();
        return ['success'=>'ok','data'=>$data];
    }


    public function createByQuotation(Request $request)
    {
        $this->validate($request, [
            'ext_q_id' => 'required|numeric',
            'dossier' => 'required'
        ]);
        //return $request['work_date'];
        try {
            if (!empty($request['ext_q_id'])) {
                $user = $request->user();//--get current user---
                //---query fo quotation---
                $ext_q_id = $request['ext_q_id'];
                $queryUrl = 'http://rfq.oltatravel.com/async/olta.getquotation?id=' . $ext_q_id;
                $curl = curl_init();

                curl_setopt_array($curl, array(
                    CURLOPT_HEADER => 0,
                    CURLOPT_RETURNTRANSFER => 1,
                    //CURLOPT_URL => $queryUrl . '?' . $queryData,
                    CURLOPT_URL => $queryUrl,
                ));

                $result = curl_exec($curl);
                curl_close($curl);
                $result = json_decode($result, 1);
                //return $result['data']['quotation']['program'];

                if ($result['success'] ==true) {
                    //---CHECK Group people
                    $people_checked=false;
                    $groups = $result['data']['quotation']['groups'];
                    $ext_p='';
                    for ($i=0;$i<count($groups);$i++) {
                        $ext_p=$ext_p.($i==0?'':', ').$groups[$i]['people'];
                        if ($groups[$i]['people'] == $request['people']) {
                            $people_checked=true;
                            break;
                        }
                    }
                    if ($people_checked==false) {
                        $out_res=['errors'=>['people'=>['0'=>'Group people not the same:<'.$ext_p.'>']],'message'=>'People value error'];
                        return response()->json($out_res)->setStatusCode(422, 'People value error!');;
                    }
                    //---CHECK DateFrom tour
                    $tour_date_checked=false;
                    $tour_dates = $result['data']['quotation']['dates'];
                    $ext_d='';
                    //$req_work_date =date(Y-m-d h:i:s, strtotime($yourDate));
                    $req_work_date  = date('Y-m-d', strtotime($request['work_date']));
                    $tour_date_from = @$result['data']['quotation']['dates'][0]['dateFrom'];
                    $people         = @$result['data']['quotation']['groups'][0]['people'];
                    for ($i=0;$i<count($tour_dates);$i++) {
                        $ext_d=$ext_d.($i==0?'':', ').$tour_dates[$i]['dateFrom'];
                        if ($tour_dates[$i]['dateFrom'] == $req_work_date ) {
                            $tour_date_checked=true;
                            break;
                        }
                    }

                    if ($tour_date_checked==false) {
                        $out_res=['errors'=>['work_date'=>['0'=> $req_work_date.' Tour  start date wrong:<'.$ext_d.'>']],'message'=> $req_work_date.'Tour date value error,valid date:<'.$ext_d.'>'];
                        return response()->json($out_res)->setStatusCode(202, 'Tour date value error!');;
                    }
                    //--ADD tour record(Dosie) to table
                    //$tour=Tour::where(['ext_q_id'=>$request['ext_q_id']])->first();
                    $tour=Tour::where(['dossier'=>$request['dossier']])->first();
                    $tour_programm_action='create';
                    if (empty($tour)) {
                        $tour=new Tour();
                        //$tour_programm_action='update';
                    }
                    //--update if exist ext quotation
                    $tour->ext_q_id=$result['data']['quotation']['id'];
                    $tour->dossier=$request['dossier'];
                    $tour->tour_name=@$result['data']['quotation']['name'];
                    $tour->client_name=@$result['data']['quotation']['clientName'];
                    $tour->people=$people;//@$result['data']['quotation']['groups'][0]['people'];
                    $tour->cities_str=@$result['data']['quotation']['citiesStr'];
                    $tour->nights=@$result['data']['quotation']['nights'];
                    $tour->sales_user_name=@$result['data']['quotation']['user']['username'];
                    $tour->booking_user_name=@$user->name;
                    $tour->work_date=$req_work_date;
                    $tour->date_from=$tour_date_from;
                    $tour->date_to=@$result['data']['quotation']['dates'][0]['dateTo'];
                    //--clear options--
                    $options=@$result['data']['quotation']['options'];
                    for ($i=0;$i<count($options);$i++) {
                        if ($options[$i]['key'] =='guide_language') {
                            $options[$i]['enum']=''; //--too lage
                            break;
                        }
                    }
                    //--dossier#
                    $options['dossier']=$request['dossier'];
                    $options['dossier#']['value']=$request['dossier'];

                    $tour->currency_type_str='RUB';//--- get from api ?
                    $tour->options=json_encode($options);
                    $tour->save();
                    //return $tour_date_from;
                    //---SAVE tour programs from external sourse
                    $params=['action'=>$tour_programm_action,'tour_date_from'=>$tour_date_from,'people'=>$people];

                    $res=TourProgram::saveFromExt($tour->id,$result['data']['quotation']['program'],$params);
                    if ($res != true) {
                        $out_res=['errors'=>['program'=>['0'=>'Tour program error:']],'message'=>'Tour program save error'];
                        return response()->json($out_res)->setStatusCode(422, 'Tour program save error!');;
                    }


                }
                //Log::info('Quotation - res =' . $result);
                return ['success' => 'ok', 'data' => $result];
            }
        } catch (Exeption $e) {
            return ['error'=>$e->getMessage(),'error_description'=>'error line:'.$e->getLine()];
        }
        return ['success' => 'no', 'data' => ''];

    }

    public function getProgram(Request $request)
    {

        $this->validate($request, [
            'tour_id' => 'required',
        ]);

        try {
            //return ['success'=>'ok','data'=>'???'.$request['tour_id']];
            $tour_id=$request['tour_id'];
            $records=[];
            $tour_program_arr=[];
            //$days_collect=\DB::table('tour_programs')->selectRaw("DISTINCT day_index")->where('tour_id','=',$tour_id)->get();
            //$tour_days=$days_collect->count();
            $records=TourProgram::where('tour_id','=',$tour_id)->orderBy('day_index')->selectRaw("*")->get();
            $days_tour_arr=TourProgram::where('tour_id','=',$tour_id)->orderBy('day_index')->selectRaw("DISTINCT day_index,options")->get()->toArray();
            $cur_day_index=0;
            foreach($days_tour_arr as $day) {
                $options=json_decode($day['options']);
                if (!empty($options->service_date_title)) {
                    $day_title=$options->service_date_title;
                } else {
                    $day_title='Day title';
                }
                //if ($day['day_index'] != $cur_day_index) {
                    //$services=[];
                    $services =$records->where('day_index',$day['day_index']);
                    $city_services   =$services->groupBy('city_name');
                    $services->concat(['visible_comment' => false]);
                    //--set is_transport to true/false for UI component
                    $city_grouped = $city_services->mapToGroups(function ($item, $key) {
                        //return [$item['city_name'] => $item['cities']];
                        return [['city_name'=>$key,'services'=>$item]];
                    });
                    //$services->all();
                    //$city_services->all();
                    //Log::info('Day_index:  '.$day['day_index'].' Cities:'.$city_services->toJson());
                    Log::info('\n\nGrouped Day_index:  '.$day['day_index'].' Cities grouped:'.$city_grouped[0]->toJson());
                    Log::info('\n\nGrouped Count:  '.$city_grouped[0]->count());
                    //Log::info('Day_index:  '.$day['day_index'].' Services:'.$services->toJson());
                    $cur_day_index = $day['day_index'];
                //}

                $supplements=[];//{service_name:'transport',service_hours:8,service_price:120,service_sum:960,is_transport:true}
                array_push($tour_program_arr,['day_index'=>$day['day_index'],'day_title'=>$day_title,'cities'=>$city_grouped[0],'services'=>$services,'supplements'=>$supplements]);
            }

            //$all_days
            return ['success'=>'ok','data'=>$tour_program_arr];

        } catch (Exeption $e) {
            return ['error'=>$e->getMessage(),'error_description'=>'error line:'.$e->getLine()];
        }
    }

    public function saveComment(Request $request)
    {
        $this->validate($request, [
            'program_id' => 'required',
            'comment' => 'required',
        ]);
        try {
            $program_id=$request['program_id'];
            $comment=$request['comment'];
            $tp=TourProgram::where(['id'=>$program_id])->first();
            if ($tp) {
                $tp->comment=$comment;
                $tp->save();
                return ['success'=>'ok','data'=>['program_id'=>$program_id]];
            }

        } catch (Exeption $e) {
            Log::error('SaveComment:'.$e->getMessage().' line:'.$e->getLine().' code:'.$e->getCode());
            return ['error'=>$e->getMessage(),'error_description'=>'error line:'.$e->getLine()];
        }
        return;
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
     * @param  \App\Tour  $tour
     * @return \Illuminate\Http\Response
     */
    public function show(Tour $tour)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Tour  $tour
     * @return \Illuminate\Http\Response
     */
    public function edit(Tour $tour)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Tour  $tour
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tour $tour)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Tour  $tour
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tour $tour)
    {
        //
    }
}
