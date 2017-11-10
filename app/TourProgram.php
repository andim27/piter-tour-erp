<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\TourSupplement;
use Log;
class TourProgram extends Model
{
    //
    protected $table = 'tour_programs';

    static public function saveFromExt($tour_id,$tour_program,$params) {
        if (empty($tour_id)) {return null;}
        if (empty($tour_program)) {return null;}
        try {
            $people=$params['people'];
            $action=$params['action'];
            $tour_date_from=$params['tour_date_from'];
            TourProgram::where(['tour_id'=>$tour_id])->delete();
            if ($action=='update') {
                //-- delete existed records --because too many info needs to be updated
                TourProgram::where(['tour_id'=>$tour_id])->delete();
                //\DB::table('tour_programs')->where('tour_id', '==', $tour_id)->delete();
            }
            //return $params;
            foreach ($tour_program as $day_index=>$day_program) {

                foreach ($day_program['cities'] as $city_index=>$city_services) {

                    //-- services in city --
                    foreach ($city_services['services'] as $day_service_index=>$service) {
                        //--- extract supplement services:transport,guide
                        //if (strpos($service['serviceType']['key'],'transport_new') >=0) {
                        if ($service['serviceType']['key']=='transport_new') {
                            $t_sup=new TourSupplement();
                            $t_sup->service_type='transport';
                            $t_sup->tour_id     = $tour_id;
                            $t_sup->day_index   = (int)$day_index+1;
                            $t_sup->city_name   = $city_services['city']['code'];
                            $t_sup->service_hours=$service['hours'];
                            $t_sup->service_price=$service['featuredService']['price'];
                            $t_sup->currency_type_str=$service['featuredService']['currency'];
                            if (empty($day_program['dateYmd'])) {
                                //--ask tour(dateFrom)
                                $t_sup->service_date=$tour_date_from;
                            } else {
                                $t_sup->service_date=\DateTime::createFromFormat('Y-m-d',$day_program['dateYmd']);
                            }
                            $t_sup->save();
                            continue;
                        }
                        //--- save services ---
                        //$tp=TourProgram::find($cur_program_id);
                        $tp=new TourProgram();
                        $tp->tour_id=$tour_id;
                        $tp->day_index=(int)$day_index+1;
                        $tp->day_service_index=(int)$day_service_index+1;
                        $tp->is_transport =($service['transport']=='1')?true:false;
                        $tp->service_type=$service['serviceType']['key'];

                        $tp->q_hours=$service['hours'];
                        $tp->q_price=$service['featuredService']['price'];
                        $tp->price_type=$service['featuredService']['priceType'];
                        $tp->currency_type_str=$service['featuredService']['currency'];
                        //--Qty extract--
                        //$featureds=$service['featured'];
                        //return $service['featureds'];
                        //Log::info('tour_id:'.$tour_id.' featureds:'.var_export($service['featureds']));
                        $tp->qty=@$service['featureds'][$people]['qty'];
                        //echo var_dump($service['featureds']);
                        //die();
                        if (empty($day_program['dateYmd'])) {
                            //--ask tour(dateFrom)
                            $tp->service_date=$tour_date_from;//--needs++ ???
                        } else {
                            $tp->service_date=\DateTime::createFromFormat('Y-m-d',$day_program['dateYmd']);
                        }
                        if (!empty($day_program['date'])) {
                            $tp->options=json_encode(['cities_title'=>'',
                                'service_date_title'=>$day_program['date']]);
                            //$tp->options=json_encode(['cities_title'=>$day_program['citiesTitle'],'service_date_title'=>$day_program['date']]);
                        } else {
                            $tp->options=json_encode(['cities_title'=>'',
                                'service_date_title'=>'']);
                        }

                        $tp->city_name=$city_services['city']['code'];
                        $tp->service_name=$service['name']['value'];

                        $tp->save();
                    }

                }

            }


        } catch (\Exception $e) {
            Log::error('-- ERROR --:'.$e->getMessage().'\n error line:'.$e->getLine());
            return false;
        }
        return true;
    }
}
