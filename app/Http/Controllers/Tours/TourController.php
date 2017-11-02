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
                    $req_work_date =date('Y-m-d', strtotime($request['work_date']));
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
                    $tour=Tour::where(['ext_q_id'=>$request['ext_q_id']])->first();
                    if (empty($tour)) {
                        $tour=new Tour();
                    }
                    //--update if exist ext quotation
                    $tour->ext_q_id=$result['data']['quotation']['id'];
                    $tour->tour_name=@$result['data']['quotation']['name'];
                    $tour->client_name=@$result['data']['quotation']['clientName'];
                    $tour->people=@$result['data']['quotation']['groups'][0]['people'];
                    $tour->cities_str=@$result['data']['quotation']['citiesStr'];
                    $tour->nights=@$result['data']['quotation']['nights'];
                    $tour->sales_user_name=@$result['data']['quotation']['user']['username'];
                    $tour->booking_user_name=@$user->name;
                    $tour->work_date=$req_work_date;
                    $tour->date_from=@$result['data']['quotation']['dates'][0]['dateFrom'];
                    $tour->date_to=@$result['data']['quotation']['dates'][0]['dateTo'];
                    //--clear options--
                    $options=@$result['data']['quotation']['options'];
                    for ($i=0;$i<count($options);$i++) {
                        if ($options[$i]['key'] =='guide_language') {
                            $options[$i]['enum']=''; //--too lage
                            break;
                        }
                    }
                    $tour->options=json_encode($options);
                    $tour->save();
                    //return $tour->id;
                    //---SAVE tour programs from external sourse
                    $res=TourProgram::saveFromExt($tour->id,$result['data']['quotation']['program']);
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
