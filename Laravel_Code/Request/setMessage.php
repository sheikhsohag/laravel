public function rules()
{
    return [
        'phone' => [
            'required',
            'numeric',
            'digits:11',
            function ($attribute, $value, $fail) {
                if (substr($value, 0, 2) !== '01') {
                    $fail('The phone must start with 01.');
                }
            }
        ]
    ];
}

// In your FormRequest or validator
public function rules()
{
    return [
        'number' => 'required|digits:11'
    ];
}