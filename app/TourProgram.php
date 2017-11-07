<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Log;
class TourProgram extends Model
{
    //
    protected $table = 'tour_programs';

    static public function saveFromExt($tour_id,$tour_program,$action,$tour_date_from) {
        if (empty($tour_id)) {return null;}
        if (empty($tour_program)) {return null;}
        try {
            if ($action=='update') {
                //-- delete existed records --because too many info needs to be updated
                TourProgram::where(['tour_id'=>$tour_id])->delete();
                //\DB::table('tour_programs')->where('tour_id', '==', $tour_id)->delete();
            }

            foreach ($tour_program as $day_index=>$day_program) {

                //$tp->save();
                //$cur_program_id =$tp->id;//--- ???---
                foreach ($day_program['cities'] as $city_index=>$city_services) {

                    //-- services in city --
                    foreach ($city_services['services'] as $day_service_index=>$service) {
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
