<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Users;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class WorkControllerTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->signIn();
    }

    public function test_it_renders_proper_component_on_index_page_with_props(): void
    {
        $this->get(route('profile.work.index'))
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->component('Users/Work/Index')
                ->hasAll(['totalWorkHours', 'shiftsCount']));
    }
}
