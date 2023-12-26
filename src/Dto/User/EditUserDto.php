<?php

namespace App\Dto\User;

use App\Entity\User;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class EditUserDto
{
    /**
     * @var string $email
     */
    #[NotBlank]
    #[Email]
    private string $email;

    /**
     * @param User $user
     */
    public function __construct(
        User $user
    ) {
        $this->email = $user->getEmail();
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return EditUserDto
     */
    public function setEmail(string $email): EditUserDto
    {
        $this->email = $email;
        return $this;
    }
}
