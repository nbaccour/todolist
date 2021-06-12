<?php

namespace App\Controller;

use App\Entity\Usertd;
use App\Form\LoginType;
use App\Form\RegistrationType;
use App\Repository\UsertdRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{

    /**
     * @Route("/login", name="security_login")
     */
    public function login(AuthenticationUtils $utils): Response
    {

        $form = $this->createForm(LoginType::class, ['email' => $utils->getLastUsername()]);

        return $this->render('security/login.html.twig',
            ['formView' => $form->createView(), 'error' => $utils->getLastAuthenticationError('message')]);
    }



    /**
     * @Route("/logout", name="security_logout")
     */
    public function logout()
    {

    }

}
