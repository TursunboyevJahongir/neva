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

    'accepted' => ':attribute должен быть принят.',
    'active_url' => ':attribute не является действительным URL-адресом.',
    'after' => ':attribute должно быть дата после :date.',
    'after_or_equal' => ':attribute должен быть датой после или равной :date.',
    'alpha' => ':attribute может содержать только буквы.',
    'alpha_dash' => ':attribute может содержать только буквы, цифры, дефисы и символы.',
    'alpha_num' => ':attribute может содержать только буквы и цифры.',
    'array' => ':attribute должен быть массивом.',
    'before' => ':attribute должно быть свидание до :date.',
    'before_or_equal' => ':attribute должен быть датой до или равной :date.',
    'between' => [
        'numeric' => ':attribute должно быть между :min и :max.',
        'file' => ':attribute должно быть между :min и :max kilobytes.',
        'string' => ':attribute должно быть между :min и :max characters.',
        'array' => ':attribute должно быть между :min и :max items.',
    ],
    'boolean' => ':attribute поле должно быть истинным или ложным.',
    'confirmed' => ':attribute подтверждение не совпадает.',
    'date' => ':attribute не действительная дата.',
    'date_equals' => ':attribute должна быть дата, равная :date.',
    'date_format' => ':attribute не соответствует формату :format.',
    'different' => ':attribute и :other должно быть другим.',
    'digits' => ':attribute должно быть :digits цифры.',
    'digits_between' => ':attribute должно быть между :min и :max цифры.',
    'dimensions' => ':attribute имеет недопустимые размеры изображения.',
    'distinct' => ':attribute поле имеет повторяющееся значение.',
    'email' => ':attribute адрес эл. почты должен быть действительным.',
    'ends_with' => ':attribute должен заканчиваться одним из следующих символов: :values.',
    'exists' => 'Избранные :attribute является недействительным.',
    'file' => ':attribute должен быть файл.',
    'filled' => ':attribute поле должно иметь значение.',
    'gt' => [
        'numeric' => ':attribute должно быть больше чем :value.',
        'file' => ':attribute должно быть больше чем :value килобайты.',
        'string' => ':attribute должно быть больше чем :value символы.',
        'array' => ':attribute должно быть больше, чем :value предметы.',
    ],
    'gte' => [
        'numeric' => ':attribute должно быть больше или равно :value.',
        'file' => ':attribute должно быть больше или равно :value килобайты.',
        'string' => ':attribute должно быть больше или равно :value символы.',
        'array' => ':attribute должен иметь :value предметы или больше.',
    ],
    'image' => ':attribute должно быть изображение.',
    'in' => 'Избранные :attribute является недействительным.',
    'in_array' => ':attribute поле не существует в :other.',
    'integer' => ':attribute должно быть целым числом.',
    'ip' => ':attribute должен быть действующий IP-адрес.',
    'ipv4' => ':attribute должен быть действующий IPv4-адрес.',
    'ipv6' => ':attribute должен быть действующий IPv6-адрес.',
    'json' => ':attribute должна быть действительной строкой JSON.',
    'lt' => [
        'numeric' => ':attribute должно быть меньше чем :value.',
        'file' => 'The :attribute должно быть меньше чем :value килобайты.',
        'string' => 'The :attribute должно быть меньше чем :value символы.',
        'array' => 'The :attribute должно быть меньше чем :value предметы.',
    ],
    'lte' => [
        'numeric' => ':attribute должно быть меньше или равно :value.',
        'file' => ':attribute должно быть меньше или равно :value килобайты.',
        'string' => ':attribute должно быть меньше или равно :value символы.',
        'array' => ':attribute не должно быть больше, чем :value предметы.',
    ],
    'max' => [
        'numeric' => ':attribute не может быть больше чем :max.',
        'file' => ':attribute не может быть больше чем :max килобайты.',
        'string' => ':attribute не может быть больше чем :max символы.',
        'array' => ':attribute не может быть больше, чем :max предметы.',
    ],
    'mimes' => ':attribute должен быть файл типа: :values.',
    'mimetypes' => ':attribute должен быть файл типа: :values.',
    'min' => [
        'numeric' => ':attribute должен быть не менее :min.',
        'file' => ':attribute должен быть не менее :min килобайты.',
        'string' => ':attribute должен быть не менее :min символы.',
        'array' => ':attribute должен быть не менее :min предметы.',
    ],
    'multiple_of' => ':attribute должно быть кратно :value.',
    'not_in' => 'Избранные :attribute является недействительным.',
    'not_regex' => ':attribute формат недействителен.',
    'numeric' => ':attribute должно быть числом.',
    'password' => 'Пароль неправильный.',
    'present' => ':attribute поле должно присутствовать.',
    'regex' => ':attribute формат недействителен.',
    'required' => ':attribute обязательное поле для заполнения.',
    'required_if' => ':attribute поле обязательно, когда :other является :value.',
    'required_unless' => ':attribute поле является обязательным, если только :other в :values.',
    'required_with' => ':attribute поле обязательно, когда :values присутствует.',
    'required_with_all' => ':attribute поле обязательно, когда :values присутствуют.',
    'required_without' => ':attribute поле обязательно, когда :values не присутствует.',
    'required_without_all' => ':attribute field is required when none of :values присутствуют.',
    'same' => ':attribute и :other должен соответствовать.',
    'size' => [
        'numeric' => ':attribute должно быть :size.',
        'file' => ':attribute должно быть :size килобайты.',
        'string' => ':attribute должно быть :size символы.',
        'array' => ':attribute должен содержать :size предметы.',
    ],
    'starts_with' => ':attribute должен начинаться с одного из следующих: :values.',
    'string' => ':attribute должен быть строкой.',
    'timezone' => ':attribute должна быть действующая зона.',
    'unique' => ':attribute уже использовано.',
    'uploaded' => ':attribute не удалось загрузить.',
    'url' => ':attribute формат недействителен.',
    'uuid' => ':attribute должен быть действительный UUID.',
    'car_not_available' => "Автомобиль недоступен.",
    'car_block' => "Автомобиль заблокирован.",
    'car_document_type_invalid' => "Тип автомобильного документа недействителен.",
    'document_attached' => "Этот тип документа уже был прикреплен к этому автомобилю..",


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
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail District" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [],

];
