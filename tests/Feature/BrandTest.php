<?php

namespace Tests\Feature;

use App\Models\Brand\Brand;
use Tests\TestCase;

class BrandTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->createUserWithToken();
        $this->brand = Brand::factory()->create();
    }

    public function test_index_brand()
    {
        $response = $this->actingAs($this->user)->json(
            'GET',
            'api/brand?page[onlyData]=1',
            headers: $this->headerToken,
        );
        $expectedResponse = [
            'code',
            'error',
            'message',
            'result' => [
                '*' => [
                    'id',
                    'tenant_id',
                    'name',
                    'created_at',
                    'updated_at',
                ]
            ],
        ];
        $response
            ->assertStatus(200)
            ->assertJsonStructure($expectedResponse);
    }

    public function test_show_brand()
    {
        $response = $this->actingAs($this->user)->json(
            'GET',
            "api/brand/{$this->brand->id}",
            headers: $this->headerToken,
        );
        $expectedResponse = [
            'code',
            'error',
            'message',
            'result' => [
                'id',
                'tenant_id',
                'name',
                'created_at',
                'updated_at',
            ],
        ];
        $response
            ->assertStatus(200)
            ->assertJsonStructure($expectedResponse);
    }

    public function test_delete_brand()
    {
        $response = $this->actingAs($this->user)->json(
            'DELETE',
            "api/brand/{$this->brand->id}",
            headers: $this->headerToken,
        );
        $response
            ->assertStatus(204);            
    }

    public function test_store_brand()
    {
        $response = $this->actingAs($this->user)->json(
            'POST',
            "api/brand",
            ["name" => $this->brand->name],
            headers: $this->headerToken,
        );
        $expectedResponse = [
            'code',
            'error',
            'message',
            'result' => [
                'id',
                'tenant_id',
                'name',
                'created_at',
                'updated_at',
            ],
        ];
        $response
            ->assertStatus(201)
            ->assertJsonStructure($expectedResponse);
    }

    public function test_update_brand()
    {
        $response = $this->actingAs($this->user)->json(
            'PUT',
            "api/brand/{$this->brand->id}",
            ["name" => $this->brand->name],
            headers: $this->headerToken,
        );
        $expectedResponse = [
            'code',
            'error',
            'message',
            'result' => [
                'id',
                'tenant_id',
                'name',
                'created_at',
                'updated_at',
            ],
        ];
        $response
            ->assertStatus(200)
            ->assertJsonStructure($expectedResponse);
    }
}
