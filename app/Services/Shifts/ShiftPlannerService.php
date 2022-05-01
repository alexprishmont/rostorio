<?php

declare(strict_types=1);

namespace App\Services\Shifts;

use App\Enums\ShiftRequestTypes;
use App\Models\Company;
use App\Models\Shifts\Request;
use App\Models\Shifts\Shift;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class ShiftPlannerService
{
    private const STARTS_AT = 'starts_at';

    private const ENDS_AT = 'ends_at';

    private Carbon $shiftStartsAt;

    private Carbon $shiftEndsAt;

    private array $shifts = [];

    private Company $company;

    private array $errors = [];

    private array $warnings = [];

    public function __construct()
    {
        $this->company = Auth::user()->company;

        $this->shiftStartsAt = $this->company->shifts_begins_at;
        $this->shiftEndsAt = $this->company->shifts_ends_at;

        $this->errors = [];
    }

    public function initShifts(array $shifts): void
    {
        foreach ($shifts as $shift) {
            $date = Carbon::parse($shift['start'])->format('Y-m-d');
            $worker = User::query()->where('email', $shift['worker']['email'])->first();

            $this->shifts[$date] = [
                'id' => $shift['id'],
                self::STARTS_AT => Carbon::parse($shift['start']),
                self::ENDS_AT => Carbon::parse($shift['end']),
                'worker' => $worker,
            ];
        }

        $this->errors = [];
    }

    public function getShifts(): array
    {
        return $this->shifts;
    }

    public function getShiftsForMonth(string $month): void
    {
        $this->errors = [];
        $workers = $this->company->accounts()->get();

        $start = Carbon::parse($month)->startOfMonth();
        $end = Carbon::parse($month)->endOfMonth();

        while ($start->lte($end)) {
            $currentDate = $start->format('Y-m-d');
            $worker = $workers->random();

            $this->shifts[$currentDate] = $this->assignWorkerToShift($worker, $start);
            $start->addDay();
        }

        $start = Carbon::parse($month)->startOfMonth();

        while ($start->lte($end)) {
            $currentDate = $start->format('Y-m-d');
            $worker = $this->shifts[$currentDate]['worker'];

            if ($this->checkIfWorkerWantsToWork($worker, $start)) {
                $this->shifts[$currentDate] = $this->assignWorkerToShift($worker, $start);
                $start->addDay();

                continue;
            }

            // find worker who wants work at this shift
            while ($this->checkIfWorkerWantsToWork($worker, $start)) {
                $oldWorker = $worker;
                $worker = $this->getWorkerWhoWantsToWork($start);

                if (! $worker) {
                    $worker = $this->getRandomWorker($workers, $oldWorker);
                }

                if (! $this->fulfillsHardConstraints($worker, $start)) {
                    $worker = $oldWorker;
                    break;
                }
            }

            while ($this->checkIfWorkerCantWork($worker, $start)) {
                $newWorker = $this->getWorkerWhoWantsToWork($start);

                if (! $newWorker) {
                    break;
                }

                $foundShift = $this->getShiftWhereWorkerCantWork($newWorker)
                    ?? $this->getRandomWorkerShift($newWorker, $month);

                if (empty($foundShift)) {
                    break;
                }

                $workerRequest = $worker
                    ->shiftRequests()
                    ->where('shift_at', Carbon::parse($foundShift[self::STARTS_AT]))
                    ->first();

                if (! $workerRequest || $workerRequest->request === ShiftRequestTypes::WANT_TO_WORK) {
                    // swap the shifts
                    $this->shifts[Carbon::parse($foundShift[self::STARTS_AT])
                        ->format('Y-m-d')]['worker'] = $worker;
                    $oldWorker = $worker;
                    $worker = $newWorker;
                }

                if ($oldWorker
                    && (
                        ! $this->fulfillsHardConstraints($worker, $start)
                        || ! $this->fulfillsHardConstraints(
                            $oldWorker,
                            Carbon::parse($foundShift[self::STARTS_AT])
                        )
                    )
                ) {
                    $this->shifts[Carbon::parse($foundShift[self::STARTS_AT])
                        ->format('Y-m-d')]['worker'] = $worker;
                    $worker = $oldWorker;
                }
            }

            while ($this->checkIfWorkerDontWantToWorkButCan($worker, $start)) {
                $newWorker = $this->getWorkerWhoWantsToWork($start);

                if (! $newWorker) {
                    break;
                }

                $foundShift = $this->getShiftWhereWorkerCantWork($newWorker)
                    ?? $this->getRandomWorkerShift($newWorker, $month);

                if (empty($foundShift)) {
                    break;
                }

                $workerRequest = $worker
                    ->shiftRequests()
                    ->where('shift_at', Carbon::parse($foundShift[self::STARTS_AT]))
                    ->first();

                if (! $workerRequest || $workerRequest->request === ShiftRequestTypes::WANT_TO_WORK) {
                    // swap the shifts
                    $this->shifts[Carbon::parse($foundShift[self::STARTS_AT])
                        ->format('Y-m-d')]['worker'] = $worker;
                    $oldWorker = $worker;
                    $worker = $newWorker;
                }

                if ($oldWorker
                    && (
                        ! $this->fulfillsHardConstraints($worker, $start)
                        || ! $this->fulfillsHardConstraints(
                            $oldWorker,
                            Carbon::parse($foundShift[self::STARTS_AT])
                        )
                    )
                ) {
                    $this->shifts[Carbon::parse($foundShift[self::STARTS_AT])
                        ->format('Y-m-d')]['worker'] = $worker;
                    $worker = $oldWorker;
                }
            }

            $this->shifts[$currentDate] = $this->assignWorkerToShift($worker, $start);
            $start->addDay();
        }

        $start = Carbon::parse($month)->startOfMonth();

        while ($start->lte($end)) {
            $currentDate = $start->format('Y-m-d');
            $worker = $this->shifts[$currentDate]['worker'];

            while (! $this->fulfillsHardConstraints($worker, $start)) {
                $worker = $this->getRandomWorker($workers, $worker);
            }

            $this->shifts[$currentDate] = $this->assignWorkerToShift($worker, $start);
            $start->addDay();
        }

        $this->errors = [];

        foreach ($this->shifts as $shift) {
            $worker = $shift['worker'];
            $date = Carbon::parse($shift[self::STARTS_AT]);

            $this->fulfillsHardConstraints($worker, $date);
            $this->fulfillsSoftConstraints($worker, $date);
        }

        $this->save();
    }

    public function save(): void
    {
        foreach ($this->shifts as $shift) {
            $worker = $shift['worker'];

            unset($shift['worker']);

            if (isset($shift['id'])) {
                $shiftModel = Shift::query()->find($shift['id']);
                $shiftModel->update(
                    array_merge($shift, ['user_id' => $worker->id])
                );

                continue;
            }

            $worker->shifts()->create(
                array_merge($shift, ['company_id' => $worker->company->id]),
            );
        }
    }

    public function fulfillsSoftConstraints(User $worker, Carbon $shiftDate): bool
    {
        $softConstraint1 = $this->checkIfWorkerCantWork($worker, $shiftDate);
        $softConstraint2 = $this->checkIfWorkerDontWantToWorkButCan($worker, $shiftDate);
        $softConstraint3 = $this->checkIfWorkerWantsToWork($worker, $shiftDate);

        if ($softConstraint1) {
            $this->warnings[] = [
                'code' => 'worker_cant_work',
                'worker' => $worker,
                'shift' => $shiftDate->format('Y-m-d'),
            ];
        }

        if ($softConstraint2) {
            $this->warnings[] = [
                'code' => 'worker_dont_want_to_work',
                'worker' => $worker,
                'shift' => $shiftDate->format('Y-m-d'),
            ];
        }

        if (! $softConstraint3) {
            $this->warnings[] = [
                'code' => 'worker_wants_to_work',
                'worker' => $worker,
                'shift' => $shiftDate->format('Y-m-d'),
            ];
        }

        return $softConstraint1 && $softConstraint2 && $softConstraint3;
    }

    public function fulfillsHardConstraints(User $worker, Carbon $shift): bool
    {
        $hardConstraint1 = $this->checkIfWorkerHasProperRest($worker, $shift);
        $hardConstraint2 = $this->checkIfWorkerHasRestAfterMultipleShifts($worker, $shift);
        $hardConstraint3 = $this->checkIfWorkerFitsWeeklyHours($worker, $shift);
        $hardConstraint4 = $this->checkIfWorkerDoesntExceedMaximumHoursPerMonth($worker);

        if (! $hardConstraint1) {
            $this->errors[] = [
                'code' => 'rest_between_shifts',
                'worker' => $worker,
                'shift' => $shift->format('Y-m-d'),
            ];
        }

        if (! $hardConstraint2) {
            $this->errors[] = [
                'code' => 'rest_after_multiple_shifts',
                'worker' => $worker,
                'shift' => $shift->format('Y-m-d'),
            ];
        }

        if (! $hardConstraint3) {
            $this->errors[] = [
                'code' => 'weekly_hours',
                'worker' => $worker,
                'shift' => $shift->format('Y-m-d'),
            ];
        }

        if (! $hardConstraint4) {
            $this->errors[] = [
                'code' => 'maximum_hours_per_month',
                'worker' => $worker,
                'shift' => $shift->format('Y-m-d'),
            ];
        }

        return $hardConstraint1 && $hardConstraint2 && $hardConstraint3 && $hardConstraint4;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    private function getRandomWorkerShift(?User $worker, string $date): array
    {
        $randomDate = Carbon::parse($date)->startOfMonth()->addDays(random_int(
            0,
            Carbon::parse($date)->daysInMonth - 1
        ));
        $formatted = $randomDate->format('Y-m-d');
        $shift = $this->shifts[$formatted];

        while (! $worker->is($shift['worker'])) {
            $randomDate = Carbon::parse($date)->startOfMonth()->addDays(random_int(
                0,
                Carbon::parse($date)->daysInMonth - 1
            ));
            $formatted = $randomDate->format('Y-m-d');
            $shift = $this->shifts[$formatted];
        }

        return $shift;
    }

    private function getShiftWhereWorkerCantWork(?User $worker): array
    {
        if (! $worker) {
            return [];
        }

        foreach ($this->shifts as $shift) {
            if (! $worker->is($shift['worker'])) {
                continue;
            }

            $request = $worker->shiftRequests()->where('shift_at', Carbon::parse($shift[self::STARTS_AT]))->first();

            if ($request && ($request->request === ShiftRequestTypes::CANT_WORK
                    || $request->request === ShiftRequestTypes::CAN_BUT_DONT_WANT_TO_WORK)
            ) {
                return $shift;
            }
        }

        return [];
    }

    private function getWorkerWhoWantsToWork(Carbon $shift): ?User
    {
        $requests = Request::query()
            ->where('shift_at', $shift)
            ->where('request', ShiftRequestTypes::WANT_TO_WORK)
            ->get();

        if ($requests->isNotEmpty()) {
            return $requests->random()->user;
        }

        $randomWorker = User::all()->random();
        $request = $randomWorker->shiftRequests()->where('shift_at', $shift)->first();

        if (! $request) {
            return $randomWorker;
        }

        return null;
    }

    public function checkIfWorkerDontWantToWorkButCan(User $worker, Carbon $shift): bool
    {
        $request = $worker->shiftRequests()->where('shift_at', $shift->format('Y-m-d'))->first();

        return $request && $request->request === ShiftRequestTypes::CAN_BUT_DONT_WANT_TO_WORK->value;
    }

    public function checkIfWorkerCantWork(User $worker, Carbon $shift): bool
    {
        $request = $worker->shiftRequests()->where('shift_at', $shift->format('Y-m-d'))->first();

        return $request && $request->request === ShiftRequestTypes::CANT_WORK->value;
    }

    public function checkIfWorkerWantsToWork(User $worker, Carbon $shift): bool
    {
        $request = $worker->shiftRequests()->where('shift_at', $shift->format('Y-m-d'))->first();

        if (! $request) {
            return true;
        }

        if ($request->request === ShiftRequestTypes::WANT_TO_WORK->value) {
            return true;
        }

        return false;
    }

    public function checkIfWorkerHasRestAfterMultipleShifts(
        User $worker,
        Carbon $currentShift
    ): bool {
        $shiftsCount = 0;

        foreach ($this->shifts as $date => $shift) {
            if (empty($shift)) {
                continue;
            }

            if ($worker->is($shift['worker'])) {
                $shiftsCount++;
            } else {
                $shiftsCount = 0;
            }

            if ($shiftsCount >= 6 &&
                Carbon::parse($date)
                    ->setHours($this->shiftEndsAt->hour)
                    ->setMinutes($this->shiftEndsAt->minute)
                    ->diffInHours($currentShift
                        ->setHours($this->shiftStartsAt->hour)
                        ->setMinutes($this->shiftStartsAt->minute)) < 35
            ) {
                return false;
            }
        }

        return true;
    }

    public function checkIfWorkerHasProperRest(User $worker, Carbon $shiftTime): bool
    {
        $shiftTime->setHours($this->shiftStartsAt->hour)->setMinutes($this->shiftStartsAt->minute);

        $loopStart = $shiftTime->copy()
            ->setHours($this->shiftEndsAt->hour)
            ->setMinutes($this->shiftEndsAt->minute)
            ->subDay();

        while (! $loopStart->eq($shiftTime->copy()->startOfMonth())) {
            $shiftDate = $loopStart->format('Y-m-d');
            $shift = $this->shifts[$shiftDate] ?? [];

            if (empty($shift)) {
                return true;
            }

            if ($worker->is($shift['worker'])) {
                if ($loopStart->diffInHours($shiftTime) >= 11) {
                    return true;
                }

                return false;
            }

            $loopStart->subDay();
        }

        return false;
    }

    public function checkIfWorkerFitsWeeklyHours(User $worker, Carbon $shiftTime): bool
    {
        $timeWorkedThisWeek = 0;

        $weekStart = $shiftTime->copy()->startOfWeek();
        $weekEnd = $shiftTime->copy()->endOfWeek();

        while ($weekStart->lte($weekEnd)) {
            if (empty($this->shifts[$weekStart->format('Y-m-d')])) {
                $weekStart->addDay();
                continue;
            }

            $shift = $this->shifts[$weekStart->format('Y-m-d')];

            if (! empty($shift) && $worker->is($shift['worker'])) {
                $timeWorkedThisWeek += Carbon::parse($shift[self::STARTS_AT])->diffInHours(Carbon::parse($shift[self::ENDS_AT]));
            }

            if ($timeWorkedThisWeek >= 40) {
                return false;
            }

            $weekStart->addDay();
        }

        return true;
    }

    public function checkIfWorkerDoesntExceedMaximumHoursPerMonth(User $worker): bool
    {
        $workTime = 0;

        foreach ($this->shifts as $shift) {
            if (empty($shift)) {
                continue;
            }

            $shiftStartsAt = Carbon::parse($shift[self::STARTS_AT]);
            $shiftEndsAt = Carbon::parse($shift[self::ENDS_AT]);

            if ($worker->is($shift['worker'])) {
                $workTime += $shiftStartsAt->diffInHours($shiftEndsAt);
            }
        }

        if ($workTime > 168) {
            return false;
        }

        return true;
    }

    public function getWarnings(): array
    {
        return $this->warnings;
    }

    private function assignWorkerToShift(User $worker, Carbon $shift): array
    {
        return [
            'worker' => $worker,
            self::STARTS_AT => $shift
                ->setHours($this->shiftStartsAt->hour)
                ->setMinutes($this->shiftStartsAt->minute)
                ->format('Y-m-d H:i'),
            self::ENDS_AT => $shift
                ->setHours($this->shiftEndsAt->hour)
                ->setMinutes($this->shiftEndsAt->minute)
                ->format('Y-m-d H:i'),
        ];
    }

    private function getRandomWorker(Collection $workers, User $worker): User
    {
        $newWorker = $workers->random();

        while ($newWorker->is($worker)) {
            $newWorker = $workers->random();
        }

        return $newWorker;
    }
}
