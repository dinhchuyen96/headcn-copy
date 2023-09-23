<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use App\Models\Motorbike;
use App\Models\HMSReceivePlan;
use App\Models\OrderDetail;
use Illuminate\Contracts\Validation\Validator as ValidationValidator;
use Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('sale_motor', function($attribute, $value, $parameters, $validator) {
            $motorbike = Motorbike::where('chassic_no', $parameters[0])->where('engine_no', $parameters[1])->get()->first();
            return $motorbike;
        });

        Validator::replacer('sale_motor', function($message, $attribute, $rule, $parameters) {
            return str_replace(':attribute',$attribute, 'Cặp Số khung - Số máy không tồn tại');
        });
        Validator::extend('sokhung', function($attribute, $value, $parameters, $validator) {
            $sokhung = Motorbike::where('chassic_no', $parameters[0])->get()->first();
            return $sokhung;
        });

        Validator::replacer('sokhung', function($message, $attribute, $rule, $parameters) {
            return str_replace(':attribute',$attribute, 'Số khung không tồn tại');
        });
        Validator::extend('somay', function($attribute, $value, $parameters, $validator) {
            $somay = Motorbike::where('engine_no', $parameters[0])->get()->first();
            return $somay;
        });

        Validator::replacer('somay', function($message, $attribute, $rule, $parameters) {
            return str_replace(':attribute',$attribute, 'Số máy không tồn tại');
        });


        Validator::extend('hms_plan', function($attribute, $value, $parameters, $validator) {

            $hmsReceivePlan = HMSReceivePlan::where('chassic_no', $parameters[0])->where('engine_no', $parameters[1])->get()->first();
            return $hmsReceivePlan;
        });

        Validator::replacer('hms_plan', function($message, $attribute, $rule, $parameters) {
            return str_replace(':attribute',$attribute, 'Cặp Số khung - Số máy không có trong kế hoạch nhập');
        });

        Validator::extend('bought', function($attribute, $value, $parameters, $validator) {

            $order_detail = OrderDetail::where('chassic_no', $parameters[0])->where('engine_no', $parameters[1])->whereNull('deleted_at')->get()->first();
            if($order_detail)
            {
                if(isset($parameters[2]))
                return $order_detail->id == $parameters[2];
            }
            else
            return true;
        });

        Validator::replacer('bought', function($message, $attribute, $rule, $parameters) {
            return str_replace(':attribute',$attribute, 'Cặp Số khung - Số máy bị trùng hoặc đã được nhập về');
        });

        Validator::extend('sold', function($attribute, $value, $parameters, $validator) {

            $order_detail = OrderDetail::where('chassic_no', $parameters[0])->where('engine_no', $parameters[1])->where('type', '!=', 3)->whereNull('deleted_at')->get()->first();
            if($order_detail)
            {
                if(isset($parameters[2]))
                    return $order_detail->id == $parameters[2];
            }
            else
                return true;
        });

        Validator::replacer('sold', function($message, $attribute, $rule, $parameters) {
            return str_replace(':attribute',$attribute, 'Cặp Số khung - Số máy đã bán hoặc đang chờ xuất');
        });



        DB::listen(function($query) {
            File::append(
                storage_path('/logs/query.log'),
                $query->sql . ' [' . implode(', ', $query->bindings) . ']' . PHP_EOL
            );
        });
    }
}
