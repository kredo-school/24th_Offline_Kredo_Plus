<?php

/*
|--------------------------------------------------------------------------
| Validation Language Lines (overrides)
|--------------------------------------------------------------------------
|
| This file only overrides the messages actually shown on the auth forms
| (login / forgot-password / reset-password). Laravel merges this on top
| of its built-in English defaults (vendor/laravel/framework/.../lang/en
| /validation.php), so every other rule/key not listed here keeps working
| exactly as before.
|
*/

return [

    'required' => ':attribute を入力してください。',
    'email' => ':attribute には有効なメールアドレスを入力してください。',
    'confirmed' => ':attribute が一致しません。',
    'min' => [
        'string' => ':attribute は :min 文字以上で入力してください。',
    ],

    'attributes' => [
        'email' => 'メールアドレス',
        'password' => 'パスワード',
        'password_confirmation' => 'パスワード（確認）',
        'token' => 'トークン',
    ],

];
