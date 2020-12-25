<?php

namespace Database\Factories;

use App\Models\ServiceChagre;
use Illuminate\Database\Eloquent\Factories\Factory;

class ServiceChagreFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ServiceChagre::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'charge_amount' => 60,
        ];
    }
}
