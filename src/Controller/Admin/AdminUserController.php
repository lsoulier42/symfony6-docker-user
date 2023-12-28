<?php

namespace App\Controller\Admin;

use App\Contract\Service\UserServiceInterface;
use App\Controller\AbstractBaseController;
use App\Dto\User\AdminEditUserDto;
use App\Entity\User;
use App\Enum\UserRoleEnum;
use App\Form\User\AdminEditUserType;
use App\Repository\UserRepository;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted(UserRoleEnum::ROLE_ADMIN->name)]
#[Route(path: '/admin_user')]
class AdminUserController extends AbstractBaseController
{
    /**
     * @param Request $request
     * @param UserRepository $userRepository
     * @return Response
     */
    #[Route(path: "/", name: "admin_user_index")]
    public function index(
        Request $request,
        UserRepository $userRepository
    ): Response {
        $users = $userRepository->findAllPaginated(
            self::createPaginationDto($request)
        );
        return $this->render(
            'admin/user/index.html.twig',
            [
                "users" => $users
            ]
        );
    }

    /**
     * @param Request $request
     * @param User $user
     * @param UserServiceInterface $userService
     * @return Response
     */
    #[Route(path: "/{user}/edit", name: "admin_user_edit")]
    public function edit(
        Request $request,
        User $user,
        UserServiceInterface $userService
    ): Response {
        $dto = new AdminEditUserDto($user);
        $form = $this->createForm(AdminEditUserType::class, $dto);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $userService->updateUser($user, $dto);
                $this->addSuccessMessage("admin.user.edit.success");
            } catch (Exception $exception) {
                $this->addErrorMessage($exception->getMessage());
            }
            return $this->redirectToRoute("admin_user_index");
        }
        return $this->render(
            'admin/user/edit.html.twig',
            [
                "user" => $user,
                "form" => $form->createView()
            ]
        );
    }

    /**
     * @param Request $request
     * @param User $user
     * @param CsrfTokenManagerInterface $csrfTokenManager
     * @param UserServiceInterface $userService
     * @return Response
     */
    #[Route(path: "/{user}/delete", name: "admin_user_delete")]
    public function delete(
        Request $request,
        User $user,
        CsrfTokenManagerInterface $csrfTokenManager,
        UserServiceInterface $userService
    ): Response {
        try {
            $csrf = $request->query->get('csrf_token');
            if (!$csrfTokenManager->isTokenValid(new CsrfToken("delete-user", $csrf))) {
                throw new AccessDeniedException("global.error.invalid_csrf");
            }
            $userService->deleteUser($user);
            $this->addSuccessMessage("admin.user.delete.success");
        } catch (Exception $exception) {
            $this->addErrorMessage($exception->getMessage());
        }
        return $this->redirectToRoute("admin_user_index");
    }
}
