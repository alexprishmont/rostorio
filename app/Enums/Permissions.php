<?php

declare(strict_types=1);

namespace App\Enums;

enum Permissions: string
{
    case ACCESS_COMPANY_EDIT_PAGE = 'Access company edit page';
    case EDIT_SHIFT_TIMES = 'Edit shift times';
    case EDIT_COMPANY_NAME = 'Edit company name';
    case MANAGE_EMPLOYEES = 'Manage employees';
    case MANAGE_ROLES = 'Manage roles';
    case MANAGE_NEXT_MONTH_SCHEDULE = 'Manage next month schedule';
    case MANAGE_CURRENT_MONTH_SCHEDULE = 'Manage current month schedule';
}
