<?php

use Enburn\CarRegistry\Database\Factories\BrandFactory;
use Enburn\CarRegistry\Database\Factories\VehicleModelFactory;
use Enburn\CarRegistry\Models\Brand;
use Enburn\CarRegistry\Models\VehicleModel;
use Tests\TestCase;

class VehicleModelTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->vehicleModels = VehicleModel::factory()->count(3)->make();
    }

    public function testCanCreateVehicleModel()
    {
        $response = $this->post('api/v1/models', $this->vehicleModels->first()->toArray());

        $response->assertStatus(201);
        $response->assertJsonFragment($this->vehicleModels->first()->toArray());
    }

    public function testCanSeeVehicleModels()
    {
        VehicleModel::firstOrCreate($this->vehicleModels->first()->toArray());

        $response = $this->get('api/v1/models');
        $response->assertStatus(200);
        $response->assertJsonFragment($this->vehicleModels->first()->toArray());
    }

    public function testCanUpdateVehicleModels()
    {
        $vehicleModel = $this->vehicleModels->first();
        $createdVehicleModel = VehicleModel::firstOrCreate($vehicleModel->toArray());
        $createdVehicleModel->name = 'Model 3';


        $uri = 'api/v1/models/' . $createdVehicleModel->id;
        $data = $createdVehicleModel->toArray();
        $response = $this->json('PATCH', $uri, $data);

        $response->assertStatus(201);
        $response->assertJson($data);
    }

    public function testCanDeleteBrand()
    {
        $vehicleModel = $this->vehicleModels->first();
        $createdVehicleModel = VehicleModel::firstOrCreate($vehicleModel->toArray());

        $response = $this->json('DELETE', 'api/v1/models/' . $createdVehicleModel->id);

        $response->assertStatus(200);
        $this->assertTrue(null !== $createdVehicleModel->withTrashed()->find($createdVehicleModel->id)->deleted_at);
    }
}
