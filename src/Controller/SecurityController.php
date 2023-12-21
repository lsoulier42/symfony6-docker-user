<?php

namespace App\Controller;

use App\Contract\Service\UserServiceInterface;
use App\Dto\ChangePasswordDto;
use App\Dto\ForgotPasswordRequestDto;
use App\Dto\LoginDto;
use App\Dto\UserDto;
use App\Form\ChangePasswordType;
use App\Form\ForgotPasswordRequestType;
use App\Form\LoginType;
use App\Form\RegisterType;
use App\Repository\UserRepository;
use Doctrine\ORM\NonUniqueResultException;
use Exception;
use LogicException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class SecurityController extends AbstractController
{
    /**
     * @return Response
     */
    #[Route(path: '/login', name: 'app_login')]
    public function login(): Response
    {
        $dto = new LoginDto();
        $form = $this->createForm(LoginType::class, $dto);

        return $this->render(
            'security/login.html.twig',
            [
                'form' => $form->createView()
            ]
        );
    }

    /**
     * @param Request $request
     * @param UserServiceInterface $userService
     * @return Response
     * @throws TransportExceptionInterface
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    #[Route(path: '/register', name: 'app_register')]
    public function register(
        Request $request,
        UserServiceInterface $userService
    ): Response {
        $dto = new UserDto();
        $form = $this->createForm(RegisterType::class, $dto);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $userService->registerUser($dto);
            $this->addFlash('success', 'global.success.register');
            return $this->redirectToRoute(
                'homepage'
            );
        }
        return $this->render(
            'security/register.html.twig',
            [
                'form' => $form->createView()
            ]
        );
    }

    /**
     * @param string $token
     * @param UserRepository $userRepository
     * @param UserServiceInterface $userService
     * @param Security $security
     * @return Response
     * @throws NonUniqueResultException
     */
    #[Route(path: '/validate-email/{token}', name: 'app_validate_email')]
    public function validateEmail(
        string $token,
        UserRepository $userRepository,
        UserServiceInterface $userService,
        Security $security
    ): Response {
        if (
            !Uuid::isValid($token)
            || ($user = $userRepository->findOneByToken($token)) === null
        ) {
            $this->addFlash('danger', 'global.error.validate_email');
            return $this->redirectToRoute('homepage');
        }
        $this->addFlash('success', 'global.success.validate_email');
        $userService->enableUser($user);
        return $security->login($user, 'form_login', 'main');
    }

    /**
     * @param Request $request
     * @param UserServiceInterface $userService
     * @return Response
     * @throws TransportExceptionInterface
     */
    #[Route(path: '/forgot-password-request', name: 'app_forgot_password_request')]
    public function forgotPasswordRequest(
        Request $request,
        UserServiceInterface $userService
    ): Response {
        $dto = new ForgotPasswordRequestDto();
        $form = $this->createForm(
            ForgotPasswordRequestType::class,
            $dto
        );
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $userService->requestForgotPassword($dto);
                $this->addFlash('success', 'global.success.forgot_password_request');
            } catch (Exception $exception) {
                $this->addFlash('danger', $exception->getMessage());
            }
            return $this->redirectToRoute('homepage');
        }
        return $this->render(
            'security/forgot_password_request.html.twig',
            [
                'form' => $form->createView()
            ]
        );
    }

    /**
     * @param Request $request
     * @param string $token
     * @param UserRepository $userRepository
     * @param UserServiceInterface $userService
     * @param Security $security
     * @return Response
     * @throws NonUniqueResultException
     */
    #[Route(path: '/change-password/{token}', name: 'app_change_password')]
    public function changePassword(
        Request $request,
        string $token,
        UserRepository $userRepository,
        UserServiceInterface $userService,
        Security $security
    ): Response {
        if (
            !Uuid::isValid($token)
            || ($user = $userRepository->findOneByToken($token)) === null
        ) {
            $this->addFlash('danger', 'global.error.change_password');
            return $this->redirectToRoute('homepage');
        }
        $dto = new ChangePasswordDto();
        $form = $this->createForm(
            ChangePasswordType::class,
            $dto
        );
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $userService->changePassword($user, $dto);
            $this->addFlash('success', 'global.success.change_password');
            return $security->login($user, 'form_login', 'main');
        }
        return $this->render(
            'security/change_password.html.twig',
            [
                'form' => $form->createView()
            ]
        );
    }

    /**
     * @return void
     */
    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new LogicException(
            'This method can be blank - it will be intercepted by the logout key on your firewall.'
        );
    }
}
