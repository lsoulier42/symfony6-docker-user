<?php

namespace App\Service;

use App\Contract\Service\UserServiceInterface;
use App\Dto\ChangePasswordDto;
use App\Dto\ForgotPasswordRequestDto;
use App\Dto\UserDto;
use App\Entity\User;
use App\Repository\UserRepository;
use RuntimeException;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Uid\Uuid;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

readonly class UserService implements UserServiceInterface
{
    /**
     * @param UserRepository $userRepository
     * @param MailerInterface $mailer
     * @param Environment $environment
     * @param TranslatorInterface $translator
     * @param string $defaultFromEmail
     */
    public function __construct(
        private UserRepository $userRepository,
        private MailerInterface $mailer,
        private Environment $environment,
        private TranslatorInterface $translator,
        private string $defaultFromEmail
    ) {
    }

    /**
     * @inheritDoc
     */
    public function createUser(UserDto $dto, bool $flush = true): User
    {
        $user = new User();
        self::hydrateUserFromDto($user, $dto);
        $this->userRepository->createOrUpdate($user, $flush);
        return $user;
    }

    /**
     * @param User $user
     * @param UserDto $dto
     * @return void
     */
    public static function hydrateUserFromDto(User $user, UserDto $dto): void
    {
        $dto->setEmail(strtolower(trim($dto->getEmail())));
        $user->setEmail($dto->getEmail())
            ->setPlainPassword($dto->getPlainPassword())
            ->addRole($dto->getRole())
            ->setEnabled($dto->isEnabled());
    }

    /**
     * @inheritDoc
     */
    public function registerUser(UserDto $dto, bool $flush = true): User
    {
        $user = $this->createUser($dto, false);
        $token = $this->generateUserToken($user, $flush);
        $this->sendMailToken(
            $user,
            'security/validate_email.html.twig',
            'global.register.email.subject',
            $token
        );
        return $user;
    }

    /**
     * @inheritDoc
     */
    public function updateUser(
        User $user,
        UserDto $dto,
        bool $flush = true
    ): User {
        self::hydrateUserFromDto($user, $dto);
        $this->userRepository->createOrUpdate($user, $flush);
        return $user;
    }

    /**
     * @inheritDoc
     */
    public function enableUser(
        User $user
    ): void {
        $user
            ->setEnabled(true)
            ->setToken(null);
        $this->userRepository->createOrUpdate($user);
    }

    /**
     * @param User $user
     * @param bool $flush
     * @return string
     */
    private function generateUserToken(User $user, bool $flush = true): string
    {
        $token = Uuid::v4()->toRfc4122();
        $user->setToken($token);
        $this->userRepository->createOrUpdate($user, $flush);
        return $token;
    }

    /**
     * @inheritDoc
     */
    public function requestForgotPassword(
        ForgotPasswordRequestDto $dto,
        bool $flush = true
    ): void {
        if (
            ($user = $this->userRepository->findOneByEmail($dto->getEmail())) === null
        ) {
            throw new RuntimeException('global.error.forgot_password_request');
        }
        $token = $this->generateUserToken($user, $flush);
        $this->sendMailToken(
            $user,
            'security/change_password_email.html.twig',
            'global.change_password.email.subject',
            $token
        );
    }

    /**
     * @param User $user
     * @param string $template
     * @param string $subjectTransKey
     * @param string $token
     * @return void
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws TransportExceptionInterface
     */
    private function sendMailToken(
        User $user,
        string $template,
        string $subjectTransKey,
        string $token
    ): void {

        $html = $this->environment
            ->render(
                $template,
                [
                    'token' => $token
                ]
            );
        $subject = $this->translator
            ->trans($subjectTransKey);
        $email = new Email();
        $email
            ->from($this->defaultFromEmail)
            ->to($user->getEmail())
            ->subject($subject)
            ->html($html);
        $this->mailer->send($email);
    }

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
    ): void {
        $user->setPlainPassword($dto->getPlainPassword());
        $user->setToken(null);
        $this->userRepository->createOrUpdate($user, $flush);
    }
}
