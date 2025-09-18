<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class PasswordResetMail extends Mailable
{
    public string $code;

    public function __construct(string $code)
    {
        $this->code = $code;
    }

    public function build()
    {
        return $this
            ->subject('Восстановление пароля')
            ->html("
                <p>Здравствуйте!</p>
                <p>Ваш код для сброса пароля: <strong>{$this->code}</strong></p>
                <p>Введите его на сайте, чтобы продолжить.</p>
            ");
    }
}
