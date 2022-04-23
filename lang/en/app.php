<?php
return [
    'something_went_wrong' => 'Something went wrong. Please try again.',
    'success_action' => 'Successful action',
    'error_action' => 'Cannot process',
    'please_provide_information' => 'Please provide information',
    'please_provider_information_full' => 'You should provide information about you & your company.',
    'success_message' => 'Your action is successful',
    'schedule' => [
        'constraints' => [
            'rest_between_shifts' => 'Worker :name does not have proper rest between shifts. Failed at shift :date',
            'rest_after_multiple_shifts' => 'Worker :name does not have proper rest after 6 shifts in a row. Failed at shift :date',
            'weekly_hours' => 'Worker :name exceeds weekly working hours. Failed at shift :date',
            'maximum_hours_per_month' => 'Worker :name exceeds month\'s working hours. Failed at shift :date',
            'worker_cant_work' => 'Worker\'s :name request for not working not fulfilled. Failed at shift :date',
            'worker_dont_want_to_work' => 'Worker\'s :name request that he does not want to work not fulfilled. Failed at shift :date',
            'worker_wants_to_work' => 'Worker\'s :name request that he wants to work not fulfilled. Failed at shift :date',
        ],
        'cannot_generate' => 'Cannot generate as current month already scheduled.',
    ],
];
