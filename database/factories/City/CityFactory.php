<?php

namespace Database\Factories\City;

use App\Models\City\City;
use App\Models\State\State;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User\User>
 */
class CityFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = City::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->city(),
            'ibge_code' => $this->faker->numberBetween(1000000, 3500000),
            'state_id' => State::factory()->create()->id,
        ];
    }
}
