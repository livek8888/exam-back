<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */

    /**
     * 아이디 규칙
     * => 영문 필수, 숫자 허용, 특수기호(_ 만 허용)  최소 4글자  ~ 최대 12글자
     *
     * 비밀번호 규칙
     * => 첫 문자는 대문자이며, 영문 + 숫자 + 특수기호 ( _,!,@,#,$,(,),%,^ 만 허용)  포함하여 최소 8자 ~ 최대 20자
     */


    public function rules()
    {
        return [
            'account' => 'required',
            'password' => 'required',
        ];
    }
}
