<?php

return [
    'name' => [
        'required'  => 'Поле "Имя" обязательно для заполнения',
    ],
    'email' => [
        'required'  => 'Поле "E-Mail" обязательно для заполнения',
        'email'     => 'Поле "E-Mail" должно быть корректным E-Mail адресом',
        'unique'    => 'Поле "E-Mail" должно быть уникальным, возможно указанный Вами E-Mail уже используется'
    ], 
    'newpassword' => [
        'confirmed'  => 'Указанные пароли не совпадают',
    ], 
];