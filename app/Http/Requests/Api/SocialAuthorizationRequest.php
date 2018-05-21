<?php

namespace App\Http\Requests\Api;

//use Illuminate\Foundation\Http\FormRequest;
//use Dingo\Api\Http\FormRequest;

// 这里继承了当前目录下的FormRequest 所以上面不用use 且下面不用写 authorize()方法了

class SocialAuthorizationRequest extends FormRequest
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
        $rules = [
            'code' => 'required_without:access_token|string',
            'access_token' => 'required_without:code|string',
        ];

        if ($this->social_type == 'weixin' && !$this->code) {
            $rules['openid']  = 'required|string';
        }

        return $rules;
    }
}
