<?php

namespace App\Dto;

class LoginDto extends AbstractUserDto
{
    /**
     * @var bool $rememberMe
     */
    private bool $rememberMe = false;

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
