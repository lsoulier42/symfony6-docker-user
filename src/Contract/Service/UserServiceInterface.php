<?php

namespace App\Contract\Service;

use App\Dto\User\AdminEditUserDto;
use App\Dto\User\ChangePasswordDto;
use App\Dto\User\EditUserDto;
use App\Dto\User\ForgotPasswordRequestDto;
use App\Dto\User\RegisterDto;
use App\Entity\User;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

interface UserServiceInterface
{
    /**
     * @param RegisterDto $dto
     * @param bool $flush
     * @return User
     */
    public function createUser(RegisterDto $dto, bool $flush = true): User;

    /**
     * @param RegisterDto $dto
     * @param bool $flush
     * @return User
     * @throws TransportExceptionInterface
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function registerUser(RegisterDto $dto, bool $flush = true): User;

    /**
     * @param User $user
     * @param AdminEditUserDto $dto
     * @param bool $flush
     * @return User
     */
    public function updateUser(User $user, AdminEditUserDto $dto, bool $flush = true): User;

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

    /**
     * @param User $user
     * @param ChangePasswordDto $dto
     * @param bool $flush
     * @return void
     */
    public function changePassword(
        User $user,
        ChangePasswordDto $dto,
        bool $flush = true
    ): void;

    /**
     * @param User $user
     * @param bool $flush
     * @return void
     */
    public function deleteUser(
        User $user,
        bool $flush = true
    ): void;

    /**
     * @param User $user
     * @param EditUserDto $dto
     * @param bool $flush
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws TransportExceptionInterface
     */
    public function editUserAccount(
        User $user,
        EditUserDto $dto,
        bool $flush = true
    ): string;
}
