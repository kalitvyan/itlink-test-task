<?php

declare(strict_types=1);

namespace App\Tests\Functional\Api;

use App\Tests\Support\FunctionalTester;

final class CarCest
{
    public function createsACarWithoutOptions(FunctionalTester $I): void
    {
        $I->sendPost('/api/v1/car/create', [
            'title' => 'Audi A6',
            'description' => 'One owner, full service history',
            'price' => 28000,
            'photo_url' => 'https://picsum.photos/seed/audi-a6/800/600',
            'contacts' => '+1234567890',
        ]);

        $I->seeResponseCodeIs(201);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'data' => [
                'title' => 'Audi A6',
                'options' => null,
            ],
        ]);
    }

    public function createsACarWithOptions(FunctionalTester $I): void
    {
        $I->sendPost('/api/v1/car/create', [
            'title' => 'Range Rover',
            'description' => 'Well maintained, garage kept',
            'price' => 52000.5,
            'photo_url' => 'https://picsum.photos/seed/range-rover/800/600',
            'contacts' => '+1987654321',
            'options' => [
                'brand' => 'Land Rover',
                'model' => 'Range Rover',
                'year' => 2022,
                'body' => 'SUV',
                'mileage' => 15000,
            ],
        ]);

        $I->seeResponseCodeIs(201);
        $I->seeResponseContainsJson([
            'data' => [
                'title' => 'Range Rover',
                'options' => [
                    'brand' => 'Land Rover',
                    'model' => 'Range Rover',
                    'year' => 2022,
                    'body' => 'SUV',
                    'mileage' => 15000,
                ],
            ],
        ]);

        $response = json_decode($I->grabResponse(), true);
        $id = $response['data']['id'];

        $I->sendGet('/api/v1/car/' . $id);
        $I->seeResponseCodeIs(200);
        $I->seeResponseContainsJson(['data' => ['id' => $id, 'title' => 'Range Rover']]);
    }

    public function rejectsIncompleteOptions(FunctionalTester $I): void
    {
        $I->sendPost('/api/v1/car/create', [
            'title' => 'Range Rover',
            'description' => 'Well maintained, garage kept',
            'price' => 52000.5,
            'photo_url' => 'https://picsum.photos/seed/range-rover/800/600',
            'contacts' => '+1987654321',
            'options' => [
                'brand' => 'Land Rover',
                'model' => 'Range Rover',
                'year' => 2022,
                'body' => 'SUV',
                // mileage missing on purpose
            ],
        ]);

        $I->seeResponseCodeIs(422);
        $I->seeResponseContainsJson([
            'errors' => [[
                'code' => 'validation_failed',
                'source' => ['pointer' => '/data/attributes/options/mileage'],
            ]],
        ]);
    }

    public function returnsA404ForAMissingCar(FunctionalTester $I): void
    {
        $I->sendGet('/api/v1/car/999999');

        $I->seeResponseCodeIs(404);
        $I->seeResponseContainsJson([
            'errors' => [[
                'status' => '404',
                'code' => 'resource_not_found',
            ]],
        ]);
    }

    public function listsCarsWithPagination(FunctionalTester $I): void
    {
        $I->sendGet('/api/v1/car/list?page=1');

        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();

        $response = json_decode($I->grabResponse(), true);

        $I->assertArrayHasKey('data', $response);
        $I->assertIsArray($response['data']);
        $I->assertArrayHasKey('meta', $response);
        $I->assertSame(1, $response['meta']['page']);
    }
}
