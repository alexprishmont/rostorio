<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Shifts;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RequestControllerTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->signIn();
        $this->app->setLocale('en');
    }

    public function test_it_returns_shift_requests_for_selected_month(): void
    {
        $this->user->shiftRequests()->create([
            'request' => 'cant_work',
            'shift_at' => Carbon::now()->addMonth()->format('Y-m-d'),
        ]);

        $response = $this->get(route('shifts.requests.show', ['user' => $this->user]));
        $result = json_decode($response->getContent(), true);
        $expected = [
            [
                'id' => 1,
                'title' => __('requests.cant_work'),
                'backgroundColor' => '#b91c1c',
                'borderColor' => '#b91c1c',
                'start' => Carbon::now()->addMonth()->format('Y-m-d'),
                'allDay' => true,
            ],
        ];

        $this->assertEquals($expected, $result);
    }
}
