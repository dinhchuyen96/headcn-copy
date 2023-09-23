<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

class CustomerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Customer::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [    
            'name' => $this->faker->name($gender = null),
            'code' => Str::upper(Str::random(5)),
            'address' => $this->faker->streetAddress(), 
            // 'email' => $this->faker->email(),
            // 'phone' => $this->faker->phoneNumber(),
            // 'identity_code' => $this->faker->creditCardNumber(),
            // 'birthday' => $this->faker->date($format = 'Y-m-d', $max = '1999-12-31'),
            // 'sex' => $this->faker->numberBetween(1,2),
            // 'job' => $this->faker->jobTitle(),
        ];
    }
}
