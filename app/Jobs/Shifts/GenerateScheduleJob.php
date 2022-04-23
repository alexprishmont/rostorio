<?php

declare(strict_types=1);

namespace App\Jobs\Shifts;

use App\Services\Shifts\ShiftPlannerService;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;

class GenerateScheduleJob implements ShouldQueue
{
    use Dispatchable;
    use Queueable;
    use SerializesModels;

    public function __construct(
        private Carbon $date
    ) {
    }

    public function handle(ShiftPlannerService $service): void
    {
        $service->getShiftsForMonth(Carbon::now()->addMonth()->format('Y-m'));
    }
}
