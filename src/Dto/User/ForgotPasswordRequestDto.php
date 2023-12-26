<?php

namespace App\Dto\User;

use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class ForgotPasswordRequestDto
{
    /**
     * @var string $email
     */
    #[NotBlank]
    #[Email]
    private string $email;

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return ForgotPasswordRequestDto
     */
    public function setEmail(string $email): ForgotPasswordRequestDto
    {
        $this->email = $email;
        return $this;
    }
}
