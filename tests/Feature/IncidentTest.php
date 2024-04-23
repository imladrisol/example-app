<?php

declare(strict_types=1);

namespace Tests\Feature;

use Database\Seeders\IncidentSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Faker\Factory;

class IncidentTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed(IncidentSeeder::class);
    }

    public function testGetAllIncidents()
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . env('AUTH_TOKEN'))
            ->get('/api/v1/incidents');

        $response->assertStatus(200)
            ->assertJsonStructure(['data']);
    }

    public function testGetIncidentById()
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . env('AUTH_TOKEN'))
            ->get('/api/v1/incidents/1');

        $response->assertStatus(200)
            ->assertJsonStructure(['data']);
    }

    /**
     * Provide data for incidents with validation errors.
     *
     * @return array
     */
    public function invalidIncidentData(): array
    {
        $faker = Factory::create();

        return [
            [[
                'name' => $faker->title,
                'reason' => $faker->text,
                'courierId' => $faker->randomDigit,
                'clientId' => $faker->randomDigit,
                'occurredAt' => $faker->title,
                'deadline' => now()->addDays(7),
            ], 'occurredAt'],
            [[
                'name' => $faker->title,
                'reason' => $faker->text,
                'courierId' => $faker->randomDigit,
                'clientId' => $faker->randomDigit,
                'occurredAt' => now(),
                'deadline' => $faker->title,
            ], 'deadline'],
        ];
    }

    /**
     * Test creating an incident with validation errors.
     *
     * @dataProvider invalidIncidentData
     * @return void
     */
    public function testFailedCreateIncident(array $invalidData, string $errorField)
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . env('AUTH_TOKEN'))
            ->withHeader('Accept', 'application/json')
            ->post('/api/v1/incidents',
                $invalidData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                $errorField,
            ]);
    }

    public function testCreateIncident()
    {
        $faker = Factory::create();
        $response = $this->withHeader('Authorization', 'Bearer ' . env('AUTH_TOKEN'))
            ->withHeader('Accept', 'application/json')
            ->post('/api/v1/incidents',
                [
                    'name' => $faker->title,
                    'reason' => $faker->text,
                    'courierId' => $faker->randomDigit,
                    'clientId' => $faker->randomDigit,
                    'occurredAt' => now(),
                    'deadline' => now()->addDays(7),
                ]);

        $response->assertStatus(201);
    }

    public function testDeleteIncident()
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . env('AUTH_TOKEN'))
            ->delete('/api/v1/incidents/1');

        $response->assertStatus(200);
    }

    public function testUpdateIncident()
    {
        $faker = Factory::create();
        $response = $this->withHeader('Authorization', 'Bearer ' . env('AUTH_TOKEN'))
            ->withHeader('Accept', 'application/json')
            ->put('/api/v1/incidents/1',
                [
                    'name' => $faker->title,
                    'reason' => $faker->text,
                    'courierId' => $faker->randomDigit,
                    'clientId' => $faker->randomDigit,
                    'occurredAt' => now(),
                    'deadline' => now()->addDays(7),
                ]);

        $response->assertStatus(200);
    }

     public function testGetIncidentByWrongId()
     {
         $response = $this->withHeader('Authorization', 'Bearer ' . env('AUTH_TOKEN'))
             ->get('/api/v1/incidents/200');
         
         $response->assertStatus(404);
     }

    public function testDeleteIncidentByWrongId()
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . env('AUTH_TOKEN'))
            ->delete('/api/v1/incidents/200');

        $response->assertStatus(404);
    }

    public function testUpdateIncidentByWrongId()
    {
        $faker = Factory::create();
        $response = $this->withHeader('Authorization', 'Bearer ' . env('AUTH_TOKEN'))
            ->withHeader('Accept', 'application/json')
            ->put('/api/v1/incidents/200',
                [
                    'name' => $faker->title,
                    'reason' => $faker->text,
                    'courierId' => $faker->randomDigit,
                    'clientId' => $faker->randomDigit,
                    'occurredAt' => now(),
                    'deadline' => now()->addDays(7),
                ]);

        $response->assertStatus(404);
    }
}
