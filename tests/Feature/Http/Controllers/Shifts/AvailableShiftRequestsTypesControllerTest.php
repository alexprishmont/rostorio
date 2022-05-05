<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Shifts;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AvailableShiftRequestsTypesControllerTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->signIn();
        $this->app->setLocale('en');
    }

    public function test_it_returns_available_requests(): void
    {
        $response = $this->get(route('shifts.requests.types'));
        $result = json_decode($response->getContent(), true);

        $expected = [
            'want_to_work' => 'Want to work',
            'cant_work' => 'Cannot work',
            'can_but_wont' => 'Can but doesnt want',
        ];

        $this->assertEquals($expected, $result);
    }
}
