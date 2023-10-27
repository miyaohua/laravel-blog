<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class PasswordRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // 6-16个字符且不能有特殊符号
        if(!preg_match('/^[a-zA-Z0-9]{6,16}$/',$value)){
            $fail('密码不符合规范');
        }
    }
}
