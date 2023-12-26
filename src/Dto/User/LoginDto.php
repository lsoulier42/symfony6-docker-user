<?php

namespace App\Dto\User;

use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class LoginDto
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
     * @var bool $rememberMe
     */
    private bool $rememberMe = false;

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return LoginDto
     */
    public function setEmail(string $email): LoginDto
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
     * @return LoginDto
     */
    public function setPlainPassword(string $plainPassword): LoginDto
    {
        $this->plainPassword = $plainPassword;
        return $this;
    }

    /**
     * @return bool
     */
    public function isRememberMe(): bool
    {
        return $this->rememberMe;
    }

    /**
     * @param bool $rememberMe
     * @return LoginDto
     */
    public function setRememberMe(bool $rememberMe): LoginDto
    {
        $this->rememberMe = $rememberMe;
        return $this;
    }
}
