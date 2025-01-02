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

    'accepted' => ':attribute يجب أن يتم قبوله.',
    'active_url' => ':attribute ليس رابطاً صالحاً.',
    'after' => ':attribute يجب أن يكون تاريخاً بعد :date.',
    'after_or_equal' => ':attribute يجب أن يكون تاريخاً بعد أو مساويًا لـ :date.',
    'alpha' => ':attribute يجب أن يحتوي على أحرف فقط.',
    'alpha_dash' => ':attribute يجب أن يحتوي على أحرف وأرقام وشرطات.',
    'alpha_num' => ':attribute يجب أن يحتوي على أحرف وأرقام.',
    'array' => ':attribute يجب أن يكون مصفوفة.',
    'before' => ':attribute يجب أن يكون تاريخاً قبل :date.',
    'before_or_equal' => ':attribute يجب أن يكون تاريخاً قبل أو مساويًا لـ :date.',
    'between' => [
        'numeric' => ':attribute يجب أن يكون بين :min و :max.',
        'file' => ':attribute يجب أن يكون بين :min و :max كيلوبايت.',
        'string' => ':attribute يجب أن يكون بين :min و :max حروف.',
        'array' => ':attribute يجب أن يحتوي على :min و :max عناصر.',
    ],
    'boolean' => 'حقل :attribute يجب أن يكون صح أو خطأ.',
    'confirmed' => 'تأكيد :attribute غير مطابق.',
    'date' => ':attribute ليس تاريخاً صالحاً.',
    'date_equals' => ':attribute يجب أن يكون تاريخاً مساوياً لـ :date.',
    'date_format' => ':attribute لا يتوافق مع الشكل :format.',
    'different' => ':attribute و :other يجب أن يكونا مختلفين.',
    'digits' => ':attribute يجب أن يكون :digits رقم.',
    'digits_between' => ':attribute يجب أن يكون بين :min و :max رقم.',
    'dimensions' => ':attribute لديه أبعاد صورة غير صالحة.',
    'distinct' => 'حقل :attribute يحتوي على قيمة مكررة.',
    'email' => ':attribute يجب أن يكون عنوان بريد إلكتروني صالح.',
    'ends_with' => ':attribute يجب أن ينتهي بأحد القيم التالية: :values.',
    'exists' => ':attribute المحدد غير صالح.',
    'file' => ':attribute يجب أن يكون ملفاً.',
    'filled' => 'حقل :attribute يجب أن يحتوي على قيمة.',
    'gt' => [
        'numeric' => ':attribute يجب أن يكون أكبر من :value.',
        'file' => ':attribute يجب أن يكون أكبر من :value كيلوبايت.',
        'string' => ':attribute يجب أن يكون أكبر من :value حروف.',
        'array' => ':attribute يجب أن يحتوي على أكثر من :value عناصر.',
    ],
    'gte' => [
        'numeric' => ':attribute يجب أن يكون أكبر من أو مساويًا لـ :value.',
        'file' => ':attribute يجب أن يكون أكبر من أو مساويًا لـ :value كيلوبايت.',
        'string' => ':attribute يجب أن يكون أكبر من أو مساويًا لـ :value حروف.',
        'array' => ':attribute يجب أن يحتوي على :value عناصر أو أكثر.',
    ],
    'image' => ':attribute يجب أن تكون صورة.',
    'in' => ':attribute المحدد غير صالح.',
    'in_array' => 'حقل :attribute غير موجود في :other.',
    'integer' => ':attribute يجب أن يكون عدد صحيح.',
    'ip' => ':attribute يجب أن يكون عنوان IP صالح.',
    'ipv4' => ':attribute يجب أن يكون عنوان IPv4 صالح.',
    'ipv6' => ':attribute يجب أن يكون عنوان IPv6 صالح.',
    'json' => ':attribute يجب أن يكون نص JSON صالح.',
    'lt' => [
        'numeric' => ':attribute يجب أن يكون أقل من :value.',
        'file' => ':attribute يجب أن يكون أقل من :value كيلوبايت.',
        'string' => ':attribute يجب أن يكون أقل من :value حروف.',
        'array' => ':attribute يجب أن يحتوي على أقل من :value عناصر.',
    ],
    'lte' => [
        'numeric' => ':attribute يجب أن يكون أقل من أو مساويًا لـ :value.',
        'file' => ':attribute يجب أن يكون أقل من أو مساويًا لـ :value كيلوبايت.',
        'string' => ':attribute يجب أن يكون أقل من أو مساويًا لـ :value حروف.',
        'array' => ':attribute يجب أن لا يحتوي على أكثر من :value عناصر.',
    ],
    'max' => [
        'numeric' => ':attribute قد لا يكون أكبر من :max.',
        'file' => ':attribute قد لا يكون أكبر من :max كيلوبايت.',
        'string' => ':attribute قد لا يكون أكبر من :max حروف.',
        'array' => ':attribute قد لا يحتوي على أكثر من :max عناصر.',
    ],
    'mimes' => ':attribute يجب أن يكون ملف من نوع: :values.',
    'mimetypes' => ':attribute يجب أن يكون ملف من نوع: :values.',
    'min' => [
        'numeric' => ':attribute يجب أن يكون على الأقل :min.',
        'file' => ':attribute يجب أن يكون على الأقل :min كيلوبايت.',
        'string' => ':attribute يجب أن يكون على الأقل :min حروف.',
        'array' => ':attribute يجب أن يحتوي على الأقل :min عناصر.',
    ],
    'not_in' => ':attribute المحدد غير صالح.',
    'not_regex' => 'صيغة :attribute غير صالحة.',
    'numeric' => ':attribute يجب أن يكون رقماً.',
    'password' => 'كلمة المرور غير صحيحة.',
    'present' => 'حقل :attribute يجب أن يكون موجوداً.',
    'regex' => 'صيغة :attribute غير صالحة.',
    'required' => 'حقل :attribute مطلوب.',
    'required_if' => 'حقل :attribute مطلوب عندما يكون :other هو :value.',
    'required_unless' => 'حقل :attribute مطلوب إلا إذا كان :other في :values.',
    'required_with' => 'حقل :attribute مطلوب عندما يكون :values موجود.',
    'required_with_all' => 'حقل :attribute مطلوب عندما يكون :values موجود.',
    'required_without' => 'حقل :attribute مطلوب عندما لا يكون :values موجود.',
    'required_without_all' => 'حقل :attribute مطلوب عندما لا يكون أي من :values موجود.',
    'same' => ':attribute و :other يجب أن يتطابقا.',
    'size' => [
        'numeric' => ':attribute يجب أن يكون :size.',
        'file' => ':attribute يجب أن يكون :size كيلوبايت.',
        'string' => ':attribute يجب أن يكون :size حروف.',
        'array' => ':attribute يجب أن يحتوي على :size عناصر.',
    ],
    'starts_with' => ':attribute يجب أن يبدأ بأحد القيم التالية: :values.',
    'string' => ':attribute يجب أن يكون نص.',
    'timezone' => ':attribute يجب أن يكون منطقة صالحة.',
    'unique' => ':attribute مستخدم من قبل.',
    'uploaded' => 'فشل في تحميل :attribute.',
    'url' => 'صيغة :attribute غير صالحة.',
    'uuid' => ':attribute يجب أن يكون UUID صالح.',

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
            'rule-name' => 'رسالة مخصصة',
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

    'attributes' => [],

];
