<?php

namespace App\Controller;

use App\Dto\LoginDto;
use App\Form\LoginType;
use LogicException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
