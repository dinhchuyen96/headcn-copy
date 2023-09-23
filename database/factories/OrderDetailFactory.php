<?php

namespace Database\Factories;

use App\Models\Accessory;
use App\Models\Motorbike;
use App\Models\OrderDetail;
use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderDetailFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = OrderDetail::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'order_id'=> Order::pluck('id')->random(),
            'product_id'=> Motorbike::pluck('id')->random(),
            'quantity'=>$this->faker->randomNumber(),
            'price'=>$this->faker->randomNumber(),
            'buy_date'=>$this->faker->date($format = 'Y-m-d', $min = 'now' , $max = '2030-12-31'),
            'status'=>$this->faker->numberBetween(1,0),
            'category'=>$this->faker->numberBetween(1,2,3,4),
            'type'=>'3',
            'chassic_no'=>Motorbike::pluck('chassic_no')->random(),
            'engine_no'=>Motorbike::pluck('engine_no')->random(),
        ];
    }
}
