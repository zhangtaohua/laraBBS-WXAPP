<?php

namespace App\Http\Requests\Api;

//use Illuminate\Foundation\Http\FormRequest;

use Dingo\Api\Http\FormRequest as BaseFormRequest;

// 编写这个是让别的类继承，这样就不用每一个 request都写一次 authorize()方法。
class FormRequest extends BaseFormRequest
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

}
