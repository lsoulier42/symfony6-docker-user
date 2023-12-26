<?php

namespace App\Dto\User;

use Symfony\Component\Validator\Constraints\NotBlank;

class ChangePasswordDto
{
    /**
     * @var string $plainPassword
     */
    #[NotBlank]
    private string $plainPassword;

    /**
     * @return string
     */
    public function getPlainPassword(): string
    {
        return $this->plainPassword;
    }

    /**
     * @param string $plainPassword
     * @return ChangePasswordDto
     */
    public function setPlainPassword(string $plainPassword): ChangePasswordDto
    {
        $this->plainPassword = $plainPassword;
        return $this;
    }
}
