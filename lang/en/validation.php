<?php

return [
    'required' => ':attribute を入力してください。',
    'email' => ':attribute は有効なメールアドレス形式で入力してください。',
    'max' => [
        'string' => ':attribute は :max 文字以内で入力してください。',
    ],
    'min' => [
        'string' => ':attribute は :min 文字以上で入力してください。',
    ],
    'confirmed' => ':attribute が確認用と一致しません。',
    'custom' => [
        'email' => [
            'required' => 'メールアドレスを入力してください',
            'email' => 'メールアドレスは「ユーザー名@ドメイン」形式で入力してください',
        ],
        'password' => [
            'required' => 'パスワードを入力してください',
        ],
    ],
    'attributes' => [
        'email' => 'メールアドレス',
        'password' => 'パスワード',
    ],
];
