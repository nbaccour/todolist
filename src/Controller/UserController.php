<?php

namespace App\Controller;

use App\Entity\Task;
use App\Entity\Usertd;
use App\Form\ResetPasswordType;
use App\Form\UsermodifyType;
use App\Form\UserType;
use App\Repository\TaskRepository;
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
    protected $taskRepository;

    public function __construct(
        UsertdRepository $userRepository,
        EntityManagerInterface $manager,
        UserPasswordEncoderInterface $encoder,
        PaginatorInterface $paginator,
        TaskRepository $taskRepository
    ) {
        $this->userRepository = $userRepository;
        $this->manager = $manager;
        $this->encoder = $encoder;
        $this->paginator = $paginator;
        $this->taskRepository = $taskRepository;
    }

    /**
     * @Route("/admin/users", name="user_show")
     */
    public function show(Request $request): Response
    {

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


        $this->denyAccessUnlessGranted("ROLE_ADMIN", null, "Accès refusé : vous devez avoir un rôle administrateur");

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

        $this->denyAccessUnlessGranted("ROLE_ADMIN", null, "Accès refusé : vous devez avoir un rôle administrateur");

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

        $this->denyAccessUnlessGranted("ROLE_ADMIN", null, "Accès refusé : vous devez avoir un rôle administrateur");


        $user = $this->userRepository->find($id);

        if (!$user) {
            throw $this->createNotFoundException("L'utilisateur $id n'existe pas");
        }
        $tasks = $user->getTask();
        //delete tasks user
        foreach ($tasks as $task) {
            $this->manager->remove($task);
        }


        $this->manager->remove($user);
        $this->manager->flush();

        $this->addFlash("warning", "L'utilisateur a bien été suprimé ");

        return $this->redirectToRoute("user_show");
    }

    /**
     * @Route("/account", name="user_account")
     * @IsGranted("ROLE_USER", message="Vous devez etres connecté pour acceder à vos données")
     */
    public function profile(Request $request)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY', null, "Accès refusé : vous devez etre connecté");

        $user = $this->getUser();

//        $formResetPwd = $this->createForm(ResetPasswordType::class, [],
//            ['action' => $this->generateUrl('user_resetPassword')]);

        return $this->render("/user/profile.html.twig",
            [
                'user'         => $user,
            ]);


    }

    /**
     * @Route("/resetpwd", name="user_resetPassword")
     * @IsGranted("ROLE_USER", message="Vous devez etres connecté pour acceder à vos données")
     */
    public function resetPassword(Request $request)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY', null, "Accès refusé : vous devez etre connecté");

        $user = $this->getUser();
        $form = $this->createForm(ResetPasswordType::class, $user, ['validation_groups' => 'verif-pwd']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $hash = $this->encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash);

            $this->manager->flush();

            $this->addFlash('success', "Votre mot de passe a été modifié");

        }
        return $this->render("/user/resetpwd.html.twig", ['formPassword' => $form->createView()]);

    }

    public function createOrUpdate($form, $user, string $type = 'create')
    {

        $this->denyAccessUnlessGranted("ROLE_ADMIN", null, "Accès refusé : vous devez avoir un rôle administrateur");


        $return = false;
        if ($form->isSubmitted() && $form->isValid()) {


            $this->manager->persist($user);
            $this->manager->flush();
            $msg = ($type === 'create') ? "L'utilisateur a bien été ajouté." : "L'utilisateur a bien été modifié";
            $this->addFlash('success', $msg);

            $return = true;
        }

        return $return;
    }

}
