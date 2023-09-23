<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Customer;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Generator as Faker;
use Illuminate\Support\Str;


class OrderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Order::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            //
            'customer_id' => Customer::pluck('id')->random(),
            'order_no' => Str::upper(Str::random(5)),
            'total_items' => '1',
            'sub_total' => '0',
            'tax' => '0',
            'discount' => '0',
            'total' => $this->faker->randomFloat($nbMaxDecimals = NULL, $min = 0, $max = 100000),
            'date_payment' => $this->faker->date($format = 'Y-m-d', $min = 'now' , $max = '2030-12-31'),
            'total_money' => $this->faker->randomFloat($nbMaxDecimals = NULL, $min = 0, $max = 100000),
            'category' => $this->faker->numberBetween(1,4),
            'admin_id' => User::pluck('id')->random(),
            'type' => $this->faker->numberBetween(1,2),
            'status' => $this->faker->numberBetween(1,5),
            'supplier_id'=> Supplier::pluck('id')->random(),
        ];
    }
}
