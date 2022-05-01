<?php
return [
    'something_went_wrong' => 'Įvyko klaida. Prašome pabandyti dar karta.',
    'success_action' => 'Sėkmingas veiksmas',
    'error_action' => 'Neįmanoma apdoroti užklausos',
    'please_provide_information' => 'Pateikite duomenis',
    'please_provider_information_full' => 'Pateikite duomenis apie savę ir Jūsų įmonę.',
    'success_message' => 'Duomenys sėkmingai atnaujinti.',
    'schedule' => [
        'constraints' => [
            'rest_between_shifts' => 'Darbuotojas :name neturi 11 val. poilsio tarp pamainų. Pamainos data :date',
            'rest_after_multiple_shifts' => 'Darbuotojas :name neturi 35 val. poilsio po 6 pamainų iš eilės. Pamainos data :date',
            'weekly_hours' => 'Darbuotojas :name viršija savaitės darbo valandų normą. Pamainos data :date',
            'maximum_hours_per_month' => 'Darbuotojas :name viršija mėnesio darbo valandų normą. Pamainos data :date',
            'worker_cant_work' => 'Darbuotojo :name pageidavimas nedirbti neišpildytas. Pamainos data :date',
            'worker_dont_want_to_work' => 'Darbuotojo :name pageidavimas, jog jis gali bet nenori dirbti neišpildytas. Pamainos data :date',
            'worker_wants_to_work' => 'Darbuotojo :name pageidavimas dirbti neišpildytas. Pamainos data :date',
        ],
        'cannot_generate' => 'Neįmanoma sugeneruoti darbo grafiką, nes jis jau yra sugeneruotas.',
    ],
];
