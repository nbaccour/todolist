<?php

namespace App\Controller;

use App\Entity\Usertd;
use App\Form\ResetPasswordType;
use App\Form\UsermodifyType;
use App\Form\UserType;
use App\Repository\UsertdRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class UserController extends AbstractController
{


    protected $userRepository;
    protected $manager;
    protected $encoder;
    protected $paginator;

    public function __construct(
        UsertdRepository $userRepository,
        EntityManagerInterface $manager,
        UserPasswordEncoderInterface $encoder,
        PaginatorInterface $paginator
    ) {
        $this->userRepository = $userRepository;
        $this->manager = $manager;
        $this->encoder = $encoder;
        $this->paginator = $paginator;
    }

    /**
     * @Route("/admin/users", name="user_show")
     */
    public function show(Request $request): Response
    {

//        $userConnect = $this->getUser();
//        if (!$userConnect) {
//            return $this->redirectToRoute("security_login");
//        }
//        if ($this->isGranted('ROLE_ADMIN') === false) {
////        if (in_array('ROLE_ADMIN', $userConnect->getRoles()) === false) {
//
//            $this->addFlash("warning",
//                "Accès refusé : vous devez avoir un rôle administrateur");
//            return $this->redirectToRoute("home");
//        }
        $this->denyAccessUnlessGranted("ROLE_ADMIN", null, "Accès refusé : vous devez avoir un rôle administrateur");
        $users = $this->userRepository->findAll();

        $usersList = $this->paginator->paginate(
            $users, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            6 // Nombre de résultats par page
        );


        return $this->render('/user/list.html.twig', ['users' => $usersList]);


    }

    /**
     * @Route("/admin/user/create", name="user_create")
     */
    public function create(Request $request): Response
    {

        $userConnect = $this->getUser();
        if (!$userConnect) {
            return $this->redirectToRoute("security_login");
        }
        if (in_array('ROLE_ADMIN', $userConnect->getRoles()) === false) {

            $this->addFlash("warning",
                "Accès refusé : vous devez avoir un rôle administrateur");
            return $this->redirectToRoute("home");
        }

        $user = new Usertd();

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

        }

        $create = $this->createOrUpdate($form, $user);

        if ($create === true) {
            return $this->redirectToRoute("user_show");
        }


        return $this->render('/user/create.html.twig', ['formView' => $form->createView(), 'user' => $user]);


    }

    /**
     * @Route("/admin/user/modify/{id}", name="user_modify")
     */
    public function modify($id, Request $request)
    {
        $userConnect = $this->getUser();
        if (!$userConnect) {
            return $this->redirectToRoute("security_login");
        }
        if (in_array('ROLE_ADMIN', $userConnect->getRoles()) === false) {

            $this->addFlash("warning",
                "Accès refusé : vous devez avoir un rôle administrateur");
            return $this->redirectToRoute("home");
        }


        $user = $this->userRepository->find($id);

        if (!$user) {
            throw $this->createNotFoundException("L'utilisateur $id n'existe pas");
        }

        $form = $this->createForm(UsermodifyType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

        }

        $modify = $this->createOrUpdate($form, $user, 'modify');
        if ($modify === true) {
            return $this->redirectToRoute("user_show");
        }


        return $this->render('/user/modify.html.twig',
            ['formView' => $form->createView(), 'user' => $user]);
    }

    /**
     * @Route("/admin/user/delete/{id}", name="user_delete")
     */
    public function delete($id)
    {

        $userConnect = $this->getUser();
        if (!$userConnect) {
            return $this->redirectToRoute("security_login");
        }
        if (in_array('ROLE_ADMIN', $userConnect->getRoles()) === false) {

            $this->addFlash("warning",
                "Accès refusé : vous devez avoir un rôle administrateur");
            return $this->redirectToRoute("home");
        }


        $user = $this->userRepository->find($id);

        if (!$user) {
            throw $this->createNotFoundException("L'utilisateur $id n'existe pas");
        }


        $this->manager->remove($user);
        $this->manager->flush();

        $this->addFlash("warning", "L'utilisateur a bien été suprimé ");

        return $this->redirectToRoute("user_show");
    }

    /**
     * @Route("/profile", name="user_profile")
     * @IsGranted("ROLE_USER", message="Vous devez etres connecté pour acceder à vos données")
     */
    public function profile(Request $request)
    {
        $user = $this->getUser();


        $formResetPwd = $this->createForm(ResetPasswordType::class, [],
            ['action' => $this->generateUrl('user_resetPassword')]);

        return $this->render("/user/profile.html.twig",
            [
                'user'         => $user,
                'formPassword' => $formResetPwd->createView(),
            ]);


    }

    /**
     * @Route("/resetpwd", name="user_resetPassword")
     * @IsGranted("ROLE_USER", message="Vous devez etres connecté pour acceder à vos données")
     */
    public function resetPassword(Request $request)
    {
        $user = $this->getUser();
        $form = $this->createForm(ResetPasswordType::class, $user, ['validation_groups' => 'verif-pwd']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
//            dump($user);
//            dd($user->getPassword());
            $hash = $this->encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash);

            $this->manager->flush();

            $this->addFlash('success', "Votre mot de passe a été modifié");

        }
        return $this->render("/user/profile.html.twig", ['user' => $user, 'formPassword' => $form->createView()]);

    }

    public function createOrUpdate($form, $user, string $type = 'create')
    {

        $userConnect = $this->getUser();
        if (!$userConnect) {
            return $this->redirectToRoute("security_login");
        }
        if (in_array('ROLE_ADMIN', $userConnect->getRoles()) === false) {

            $this->addFlash("warning",
                "Accès refusé : vous devez avoir un rôle administrateur");
            return $this->redirectToRoute("home");
        }


        $return = false;
        if ($form->isSubmitted() && $form->isValid()) {


            $this->manager->persist($user);
            $this->manager->flush();
            $msg = ($type === 'create') ? "Utilisateur ajouté" : "Utilisateur modifié";
            $this->addFlash('success', $msg);

            $return = true;
        }

        return $return;
    }

}
