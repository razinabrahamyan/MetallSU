<?php

namespace App\Http\Controllers;

use App\Models\Logistics;
use App\Services\LogisticsLazyLoadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LogisticsController extends Controller
{
    public function getQuery(Request $request){
        $validator = $this->validateQuery($request->all());
        if ($validator->fails() || $request->magic !== '$2a$12$euZt82F86CvDfunkCiReluLBhSa5KKO5BeZVp1mpEN0Sgj.rXdmEu') {
            return response()->json([
                'status' => 500
            ]);
        } else {
//            if($request->status == 2){
//                Logistics::where('query_id', $request->query_id)->update(['status' => 'edited']);
//            }

            Logistics::where('query_id', $request->query_id)->delete();
            foreach ($request->drivers_data as $driver) {
                Logistics::create([
                    'name' => $driver['driver_name'],
                    'phone' => $driver['phone'],
                    'car_type' => $driver['type'],
                    'car_number' => $driver['car_number'],
                    'date' => $request->departure_data,
                    'query_id' => $request->query_id,
                    'cutter' => $request->cutters,
                    'loader' => $request->loaders,
                    'oxygen' => $request->oxygen,
                ]);
            }

            return response()->json([
                'data' => count($request->drivers_data),
                'status' => 200
            ]);
        }

    }

    public function validateQuery($data){
        return Validator::make($data,[
            'query_id' => 'required|numeric',
            'departure_data' => 'required|date',
            'loaders' => 'required|numeric',
            'oxygen' => 'required|numeric',
            'cutters' => 'required|numeric',
            'drivers_data' => 'required',
            'drivers_data.*.type' => 'required|string',
            'drivers_data.*.phone' => 'required|string',
            'drivers_data.*.car_number' => 'required|string',
            'drivers_data.*.driver_name' => 'required|string',
            'magic' => 'required|string'
        ]);
    }
    public function cutFromRight($string,$length,$return_cut = true){
        if($return_cut){
            return substr($string, -$length);
        }else{
            return substr(0,strlen($string)-$length);
        }
    }
   /* public function makePhone($phone){
        $phone = substr_replace($phone,'-',strlen($phone)-2,0);
        $phone = substr_replace($phone,'-',strlen($phone)-5,0);
        $phone = substr_replace($phone,') ',strlen($phone)-9,0);

        $phone = substr_replace($phone,'+7 (',strlen($phone)-14,0);
        $phone = substr($phone,-18);
        return $phone;
    }*/
    public function index(){
        $logistics = Logistics::orderBy('id',"DESC")->get();
        /*foreach ($logistics as $logistic){
            $logistic->phone = $this->makePhone($logistic->phone);
        }*/
        return view('pages.logistics.index',['logistics' => $logistics]);
    }

    public function lazyLoad()
    {
        return (new LogisticsLazyLoadService())->prepareTableParams(request())
                                               ->prepareQueries()
                                               ->initLazyLoad();
    }
}
