<?php

namespace App\Http\Controllers\Tours;

use App\Tour;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Log;
class TourController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return ['success'=>'ok'];
    }


    public function createByQuotation(Request $request)
    {
        try {
            if (!empty($request['ext_q_id'])) {
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
                if ($result['success'] ==true) {
                    //--add tour record(Dosie) to table
                    $tour=new Tour();
                    $tour->ext_q_id=$result['data']['quotation']['id'];
                    $tour->tour_name=$result['data']['quotation']['name'];
                    $tour->client_name=$result['data']['quotation']['clientName'];
                    $tour->people=$result['data']['quotation']['groups'][0]['people'];
                    $tour->cities_str=$result['data']['quotation']['citiesStr'];
                    $tour->nights=$result['data']['quotation']['nights'];
                    $tour->sales_user_name=$result['data']['quotation']['user']['username'];
                    $tour->date_from=$result['data']['quotation']['dates'][0]['dateFrom'];
                    $tour->date_to=$result['data']['quotation']['dates'][0]['dateTo'];
                    $tour->save();

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
