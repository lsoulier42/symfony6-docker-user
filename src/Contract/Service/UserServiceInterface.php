<?php

namespace App\Contract\Service;

use App\Dto\ForgotPasswordRequestDto;
use App\Dto\UserDto;
use App\Entity\User;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

interface UserServiceInterface
{
    /**
     * @param UserDto $dto
     * @param bool $flush
     * @return User
     */
    public function createUser(UserDto $dto, bool $flush = true): User;

    /**
     * @param UserDto $dto
     * @param bool $flush
     * @return User
     * @throws TransportExceptionInterface
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function registerUser(UserDto $dto, bool $flush = true): User;

    /**
     * @param User $user
     * @param UserDto $dto
     * @param bool $flush
     * @return User
     */
    public function updateUser(User $user, UserDto $dto, bool $flush = true): User;

    /**
     * @param User $user
     * @return void
     */
    public function enableUser(
        User $user
    ): void;

    /**
     * @param ForgotPasswordRequestDto $dto
     * @param bool $flush
     * @return void
     * @throws NonUniqueResultException
     * @throws TransportExceptionInterface
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function requestForgotPassword(
        ForgotPasswordRequestDto $dto,
        bool $flush = true
    ): void;
}
