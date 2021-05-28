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


    /**
     * @Route("registration", name="security_registration")
     */
    public function registration(
        Request $request,
        EntityManagerInterface $manager,
        UserPasswordEncoderInterface $encoder,
        UsertdRepository $userRepository
    ) {
        $user = new Usertd();
        $form = $this->createForm(RegistrationType::class, $user, ['validation_groups' => 'verif-pwd']);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $emailUser = $userRepository->findByEmail($user->getEmail());
            if (count($emailUser) !== 0) {
                $this->addFlash("warning",
                    "Erreur : Email existe : '" . $user->getEmail() . "' ");
                return $this->redirectToRoute("security_registration");
            }

            $password = $encoder->encodePassword($user, $user->getPassword());


            $user->setPassword($password);

            $manager->persist($user);
            $manager->flush();
            $this->addFlash(
                'success',
                "Compte crée avec succès ! "
            );

            return $this->redirectToRoute('security_login');
        }

        return $this->render('security/registration.html.twig',
            ['formView' => $form->createView()]);
    }
}
