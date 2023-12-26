<?php

namespace App\Service;

use App\Contract\Service\UserServiceInterface;
use App\Dto\User\AdminEditUserDto;
use App\Dto\User\ChangePasswordDto;
use App\Dto\User\EditUserDto;
use App\Dto\User\ForgotPasswordRequestDto;
use App\Dto\User\RegisterDto;
use App\Entity\User;
use App\Enum\UserRoleEnum;
use App\Repository\UserRepository;
use LogicException;
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
    public function createUser(RegisterDto $dto, bool $flush = true): User
    {
        $user = new User();
        $user->setEmail(self::cleanEmail($dto->getEmail()))
            ->setPlainPassword($dto->getPlainPassword())
            ->setEnabled(false)
            ->setRoles([UserRoleEnum::defaultRole()]);
        $this->userRepository->createOrUpdate($user, $flush);
        return $user;
    }

    /**
     * @param string $email
     * @return string
     */
    public static function cleanEmail(string $email): string
    {
        return strtolower(trim($email));
    }

    /**
     * @param User $user
     * @param AdminEditUserDto $dto
     * @return void
     */
    public static function hydrateUserFromAdminUserDto(User $user, AdminEditUserDto $dto): void
    {
        $user->setEmail(self::cleanEmail($dto->getEmail()))
            ->addRole($dto->getRole())
            ->setEnabled($dto->isEnabled());
    }

    /**
     * @inheritDoc
     */
    public function registerUser(RegisterDto $dto, bool $flush = true): User
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
        AdminEditUserDto $dto,
        bool $flush = true
    ): User {
        self::hydrateUserFromAdminUserDto($user, $dto);
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
     * @inheritDoc
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

    /**
     * @inheritDoc
     */
    public function deleteUser(
        User $user,
        bool $flush = true
    ): void {
        if (!$user->isAdmin()) {
            $this->userRepository->remove($user, $flush);
            return;
        }
        throw new LogicException("admin.user.delete.error.admin");
    }

    /**
     * @inheritDoc
     */
    public function editUserAccount(
        User $user,
        EditUserDto $dto,
        bool $flush = true
    ): string {
        $newEmail = self::cleanEmail($dto->getEmail());
        $oldEmail = $user->getEmail();
        $user->setEmail($newEmail);
        $message = "global.user.edit.success";
        if ($oldEmail !== $newEmail && !$user->isAdmin()) {
            $newToken = $this->generateUserToken($user, false);
            $user->setEnabled(false);
            $this->sendMailToken(
                $user,
                'security/validate_email.html.twig',
                'global.user.edit.validate_email_success',
                $newToken
            );
            $message = "global.user.edit.success_validate_email";
        }
        $this->userRepository->createOrUpdate($user, $flush);
        return $message;
    }
}
