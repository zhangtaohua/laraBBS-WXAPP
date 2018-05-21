<?php

namespace App\Http\Requests\Api;

//use Illuminate\Foundation\Http\FormRequest;
//use Dingo\Api\Http\FormRequest;

// 这里继承了当前目录下的FormRequest 所以上面不用use 且下面不用写 authorize()方法了

class VerificationCodeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
//    public function authorize()
//    {
//        return true;
//    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
//              'phone' => 'required|regex:/^1[3456789]\d{9}$/|unique:users',
//            'phone' => 'required| regex:/^((13[0-9])|(14[5,7])|(15[0-3,5-9])|(17[0,3,5-8])|(18[0-9])|166|198|199|(147))\\d{8}$/ |unique:users',
//            'phone' => 'required|regex:/^13[\d]{9}$|^14[5,7]{1}\d{8}$|^15[^4]{1}\d{8}$|^17[0,6,7,8]{1}\d{8}$|^18[\d]{9}$/|unique:users',
            'captcha_key' => 'required|string',
            'captcha_code' => 'required|string',
        ];
    }

    public function attributes()
    {
        return [
            'captcha_key' => '图片验证码 key',
            'captcha_code' => '图片验证码',
        ];
    }
}
