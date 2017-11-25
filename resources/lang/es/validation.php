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

    'accepted'             => 'El :attribute debe ser aceptado.',
    'active_url'           => 'El :attribute no es una URL válida.',
    'after'                => 'El :attribute debe ser una fecha posterior a :date.',
    'after_or_equal'       => 'El :attribute debe ser una fecha posterior o igual a :date.',
    'alpha'                => 'El :attribute solo puede contener letras.',
    'alpha_dash'           => 'El :attribute solo puede contener letras, números y guiones.',
    'alpha_num'            => 'El :attribute solo puede contener letras y números.',
    'array'                => 'El :attribute debe ser una matriz.',
    'before'               => 'El :attribute debe ser una fecha anterior :date.',
    'before_or_equal'      => 'El :attribute debe ser una fecha anterior o igual a :date.',
    'between'              => [
        'numeric' => 'El :attribute debe estar entre :min and :max.',
        'file'    => 'El :attribute debe estar entre :min and :max kilobytes.',
        'string'  => 'El :attribute debe estar entre :min and :max caracteres.',
        'array'   => 'El :attribute must have between :min and :max artículos.',
    ],
    'boolean'              => 'El :attribute el campo debe ser verdadero o falso.',
    'confirmed'            => 'El :attribute la confirmación no coincide.',
    'date'                 => 'El :attribute no es una fecha válida.',
    'date_format'          => 'El :attribute no coincide con el formato :format.',
    'different'            => 'El :attribute y :other debe ser diferente.',
    'digits'               => 'El :attribute debe ser :digits dígitos.',
    'digits_between'       => 'El :attribute debe estar entre :min and :max dígitos.',
    'dimensions'           => 'El :attribute tiene dimensiones de imagen no válidas.',
    'distinct'             => 'El :attribute el campo tiene un valor duplicado.',
    'email'                => 'El :attribute Debe ser una dirección de correo electrónico válida.',
    'exists'               => 'El seleccionado :attribute es inválido.',
    'file'                 => 'El :attribute debe ser un archivo.',
    'filled'               => 'El :attribute el campo debe tener un valor.',
    'image'                => 'El :attribute debe ser una imagen.',
    'in'                   => 'El seleccionado :attribute es inválido.',
    'in_array'             => 'El :attribute el campo no existe en :other.',
    'integer'              => 'El :attribute debe ser un entero.',
    'ip'                   => 'El :attribute debe ser una dirección IP válida.',
    'ipv4'                 => 'El :attribute debe ser una dirección IPv4 válida.',
    'ipv6'                 => 'El :attribute debe ser una dirección IPv6 válida.',
    'json'                 => 'El :attribute debe ser una cadena JSON válida.',
    'max'                  => [
        'numeric' => 'El :attribute no puede ser mayor que :max.',
        'file'    => 'El :attribute no puede ser mayor que :max kilobytes.',
        'string'  => 'El :attribute no puede ser mayor que :max caracteres.',
        'array'   => 'El :attribute no puede tener más de :max artículos.',
    ],
    'mimes'                => 'El :attribute debe ser un archivo de tipo: :values.',
    'mimetypes'            => 'El :attribute debe ser un archivo de tipo: :values.',
    'min'                  => [
        'numeric' => 'El :attribute al menos debe ser :min.',
        'file'    => 'El :attribute al menos debe ser :min kilobytes.',
        'string'  => 'El :attribute al menos debe ser :min caracteres.',
        'array'   => 'El :attribute debe tener al menos :min artículos.',
    ],
    'not_in'               => 'El seleccionado :attribute es inválido.',
    'numeric'              => 'El :attribute Tiene que ser un número.',
    'present'              => 'El :attribute el campo debe estar presente.',
    'regex'                => 'El :attribute el formato no es válido.',
    'required'             => 'El :attribute Se requiere campo.',
    'required_if'          => 'El :attribute campo es requerido cuando :other es :value.',
    'required_unless'      => 'El :attribute campo es requerido a menos que :other está en :values.',
    'required_with'        => 'El :attribute campo es requerido cuando :values está presente.',
    'required_with_all'    => 'El :attribute campo es requerido cuando :values está presente.',
    'required_without'     => 'El :attribute campo es requerido cuando :values no es presente.',
    'required_without_all' => 'El :attribute campo es requerido cuando ninguno de :values están presentes.',
    'same'                 => 'El :attribute y :other debe coincidir con.',
    'size'                 => [
        'numeric' => 'El :attributedebe ser :size.',
        'file'    => 'El :attributedebe ser :size kilobytes.',
        'string'  => 'El :attributedebe ser :size caracteres.',
        'array'   => 'El :attribute debe contener :size artículos.',
    ],
    'string'               => 'El :attributedebe ser una cadena de texto.',
    'timezone'             => 'El :attributedebe ser una zona válida.',
    'unique'               => 'El :attribute ya se ha tomado.',
    'uploaded'             => 'El :attribute no se pudo subir.',
    'url'                  => 'El :attribute el formato no es válido.',

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
        'g-recaptcha-response' => [
            'required' => 'Por favor verifica que no eres un robot',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [],
    "recaptcha" => 'El :attribute el campo no es correcto.',

];
