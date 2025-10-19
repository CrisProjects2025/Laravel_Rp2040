<?php

namespace Database\Factories;
use App\Models\Sensor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Sensor>
 */
class SensorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //'timestamp' => $this->faker->dateTimeThisYear(),  // Random timestamp this year
            //'Light' => $this->faker->numberBetween(0, 100),  // Random integer for light intensity
            //'open_or_close' => $this->faker->randomElement(['open', 'close', 'unknown']),  // Random value for open/close status
            //'mode' => $this->faker->randomElement(['manual', 'automatic', 'off']),  // Random mode
           // 'temperature' => $this->faker->randomFloat(2, 0, 40),  // Random temperature between 0 and 40°C with 2 decimals
            //'humidity' => $this->faker->randomFloat(2, 0, 100),  // Random humidity between 0 and 100% with 2 decimals
        ];
    }
}
