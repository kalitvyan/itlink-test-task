<?php

declare(strict_types=1);

namespace App\Tests\Functional\Api;

use App\Tests\Support\FunctionalTester;

final class HealthCest
{
    public function checksLiveness(FunctionalTester $I): void
    {
        $I->sendGet('/api/v1/health');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['data' => ['status' => 'ok']]);
    }

    public function checksReadiness(FunctionalTester $I): void
    {
        $I->sendGet('/api/v1/health/ready');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['data' => ['status' => 'ready']]);
    }
}
