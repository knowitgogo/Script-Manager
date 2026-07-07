<?php

return [
    'required' => 'Het :attribute-veld is verplicht.',
    'string' => 'Het :attribute moet een tekst zijn.',
    'email' => 'Het :attribute moet een geldig e-mailadres zijn.',
    'max' => [
        'numeric' => 'Het :attribute mag niet groter zijn dan :max.',
        'file' => 'Het :attribute mag niet groter zijn dan :max kilobytes.',
        'string' => 'Het :attribute mag niet langer zijn dan :max tekens.',
        'array' => 'Het :attribute mag niet meer dan :max items bevatten.',
    ],
    'min' => [
        'numeric' => 'Het :attribute moet minimaal :min zijn.',
        'file' => 'Het :attribute moet minimaal :min kilobytes zijn.',
        'string' => 'Het :attribute moet minimaal :min tekens lang zijn.',
        'array' => 'Het :attribute moet minimaal :min items bevatten.',
    ],
    'confirmed' => 'De :attribute bevestiging komt niet overeen.',
    'unique' => 'Het :attribute is al in gebruik.',
    'in' => 'De geselecteerde :attribute is ongeldig.',
    'integer' => 'Het :attribute moet een geheel getal zijn.',
    'numeric' => 'Het :attribute moet een getal zijn.',
    'boolean' => 'Het :attribute-veld moet waar of onwaar zijn.',

    'attributes' => [
        'name' => 'naam',
        'email' => 'e-mailadres',
        'password' => 'wachtwoord',
        'password_confirmation' => 'wachtwoord',
        'theme' => 'thema',
        'locale' => 'taal',
    ],
];
