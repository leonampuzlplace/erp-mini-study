<?php

namespace Database\Factories\Tenant;

use App\Models\Tenant\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User\User>
 */
class TenantFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Tenant::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'business_name' => $this->faker->name(),
            'alias_name' => $this->faker->name(),
            'ein' => $this->faker->unique()->randomDigit(),
            'state_registration' => $this->faker->text(20),
            'icms_taxpayer' => $this->faker->boolean(),
            'municipal_registration' => $this->faker->text(20),
            'note' => $this->faker->text(255),
            'internet_page' => $this->faker->url(),
            'created_at' => $this->faker->date('Y-m-d H:i:s'),
            'updated_at' => null,
        ];

// {
// 		"": "Company 1",
// 		"": "Company 1",
// 		"": "95.857.276\/0001-05",
// 		"": null,
// 		"": null,
// 		"": null,
// 		"": null,
// 		"": null,
// 		"": "2022-04-22 02:37:48",
// 		"": null,
// 		"tenant_address": [
// 			{
// 				"id": 1,
// 				"tenant_id": 1,
// 				"is_default": true,
// 				"zipcode": null,
// 				"address": "no address",
// 				"address_number": null,
// 				"complement": null,
// 				"district": null,
// 				"reference_point": null,
// 				"city_id": 1,
// 				"city": {
// 					"id": 1,
// 					"name": "Acrelândia",
// 					"ibge_code": "1200013",
// 					"state_id": 1,
// 					"state": {
// 						"id": 1,
// 						"name": "Acre",
// 						"abbreviation": "AC"
// 					}
// 				}
// 			}
// 		],
// 		"tenant_contact": [
// 			{
// 				"id": 1,
// 				"tenant_id": 1,
// 				"is_default": true,
// 				"name": "no name",
// 				"ein": null,
// 				"type": null,
// 				"note": null,
// 				"phone": null,
// 				"email": null
// 			}
// 		]
// 	}
// }

    }
}
