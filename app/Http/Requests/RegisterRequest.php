<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'name'=>'required|unique:users|max:100',
            'email'=>'required|unique:users|email',
            'password'=>'required|min:6|max:30|confirmed',
            'password_confirmation'=>'required'
        ];
    }
//    public function messages()
//    {
//        return [
//            'name.required'=>'请输入姓名',
//            'name.unique'=>'姓名重复,请重新输入',
//            'name.max'=>'姓名最长100位',
//            'email.required'=>'请输入电子邮件',
//            'email.unique'=>'电子邮件重复',
//            'email.email'=>'格式错误',
//            'password.required'=>'请输入密码',
//            'password.min'=>'密码最少 :size 位',
//            'password.max'=>'密码最长 :size 位',
//            'password.confirmed'=>'密码与确认密码不符'
//        ];
//    }
}
