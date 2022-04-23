<?php

declare(strict_types=1);

namespace App\Enums;

enum ShiftRequestTypes: string
{
    case WANT_TO_WORK = 'want_to_work';
    case CANT_WORK = 'cant_work';
    case CAN_BUT_DONT_WANT_TO_WORK = 'can_but_wont';
}
