<?php

use Enburn\CarRegistry\Database\Factories\VehicleFactory;
use Enburn\CarRegistry\Models\Vehicle;
use Tests\TestCase;

class VehicleTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->vehicle = Vehicle::factory()->count(3)->make();
    }

    public function testCanCreateVehicle()
    {
        $response = $this->post('api/v1/vehicles', $this->vehicle->first()->toArray());

        $response->assertStatus(201);
        $response->assertJsonFragment($this->vehicle->first()->toArray());
    }

    public function testCanSeeVehicles()
    {
        $vehicle = Vehicle::create($this->vehicle->first()->toArray());
        $vehicle->registered_at = $vehicle->registered_at->toDateString();
        $vehicle->veteran_status = (int) $vehicle->veteran_status;
        $vehicle->year = $vehicle->year->toDateString();

        $response = $this->get('api/v1/vehicles');

        $response->assertStatus(200);
        $response->assertJsonFragment($vehicle->toArray());
    }

    public function testCanUpdateVehicles()
    {
        $vehicleModel = $this->vehicle->first();
        $createdVehicle = Vehicle::create($vehicleModel->toArray());
        $createdVehicle->model = 'Model 3';
        $createdVehicle->year = $createdVehicle->year->toDateString();
        $createdVehicle->registered_at = $createdVehicle->registered_at->toDateString();
        $createdVehicle->veteran_status = (int) $createdVehicle->veteran_status;

        $uri = 'api/v1/vehicles/' . $createdVehicle->id;
        $data = $createdVehicle->toArray();
        $response = $this->json('PATCH', $uri, $data);

        $response->assertStatus(201);
        $response->assertJsonFragment($data);
    }

    public function testCanDeleteVehicle()
    {
        $vehicleModel = $this->vehicle->first();
        $createdVehicle = Vehicle::create($vehicleModel->toArray());

        $response = $this->json('DELETE', 'api/v1/vehicles/' . $createdVehicle->id);

        $response->assertStatus(200);
        $this->assertTrue(null !== $createdVehicle->withTrashed()->find($createdVehicle->id)->deleted_at);
    }
}
