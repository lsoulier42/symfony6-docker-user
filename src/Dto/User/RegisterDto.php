<?php

namespace App\Dto\User;

use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegisterDto
{
    /**
     * @var string $email
     */
    #[NotBlank]
    #[Email]
    private string $email;

    /**
     * @var string $plainPassword
     */
    #[NotBlank]
    private string $plainPassword;

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return RegisterDto
     */
    public function setEmail(string $email): RegisterDto
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return string
     */
    public function getPlainPassword(): string
    {
        return $this->plainPassword;
    }

    /**
     * @param string $plainPassword
     * @return RegisterDto
     */
    public function setPlainPassword(string $plainPassword): RegisterDto
    {
        $this->plainPassword = $plainPassword;
        return $this;
    }
}
