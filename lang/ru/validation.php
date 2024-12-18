<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'required' => 'Поле :attribute обязательно для заполнения',
    'email' => 'Поле :attribute должно быть валидным адресом электронной почты',
    'min' => [
        'string' => 'Поле :attribute должно иметь минимум :min символов',
        'array' => 'Поле :attribute должно иметь минимум :min элементов',
        'file' => 'Файл :attribute должен весить минимум :min килобайт',
        'numeric' => 'Поле :attribute должно быть не меньше :min',
    ],
    'same' => 'Поле :attribute должно совпадать с :other',
    'filled' => 'Поле :attribute должно быть заполнено',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'password_repeat' => [
            'same' => 'Пароли не совпадают',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [
        'username' => 'юзернейм',
        'visible_name' => 'отображаемое имя',
        'email' => 'почта',
        'password' => 'пароль',
        'password_repeat' => 'подтверждение пароля',
    ],

];
