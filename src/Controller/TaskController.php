<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class TaskController extends AbstractController
{

    protected $repository;
    protected $manager;

    public function __construct(
        TaskRepository $repository,
        EntityManagerInterface $manager
    ) {
        $this->repository = $repository;
        $this->manager = $manager;
    }


    /**
     * @Route("/tasks", name="task_show")
     */
    public function show(): Response
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
//        $tasks = $this->repository->findAll();
        $tasks = $this->repository->findBy([], ['id' => 'DESC']);
        return $this->render('/task/list.html.twig', ['tasks' => $tasks]);


    }

    /**
     * @Route("/mytask", name="task_mytask")
     * @IsGranted("ROLE_USER", message="Vous devez etres connecté pour acceder à vos figures")
     */
    public function mytask()
    {

        $user = $this->getUser();
//        dump($user);
//        dd($user->getTask());
        return $this->render('/user/mytask.html.twig', ['tasks' => $user->getTask()]);


    }

    /**
     * @Route("/tasks/create", name="task_create")
     * @IsGranted("ROLE_USER", message="Vous devez etres connecté pour acceder à vos taches")
     */
    public function create(Request $request)
    {
        $userConnect = $this->getUser();
        if (!$userConnect) {
            return $this->redirectToRoute("security_login");
        }
//        if (in_array('ROLE_ADMIN', $userConnect->getRoles()) === false) {
//
//            $this->addFlash("warning",
//                "Accès refusé : vous devez avoir un rôle administrateur");
//            return $this->redirectToRoute("home");
//        }

        $task = new Task();

        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

        }

        $create = $this->createOrUpdate($form, $task);

        if ($create === true) {
            return $this->redirectToRoute("task_show");
        }


        return $this->render('/task/create.html.twig', ['formView' => $form->createView(), 'task' => $task]);

    }

    /**
     * @Route("/tasks/modify/{id}", name="task_modify")
     * @IsGranted("ROLE_USER", message="Vous devez etres connecté pour acceder à vos taches")
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

        $task = $this->repository->find($id);
        if (!$task) {
            throw $this->createNotFoundException("La tache $id n'existe pas");
        }

        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

        }

        $modify = $this->createOrUpdate($form, $task, 'modify');
        if ($modify === true) {
            return $this->redirectToRoute("task_show");
        }


        return $this->render('/task/modify.html.twig',
            ['formView' => $form->createView(), 'task' => $task]);
    }

    /**
     * @Route("/tasks/delete/{$id}", name="task_delete")
     */
    public function delete($id)
    {
    }

    public function createOrUpdate($form, $task, string $type = 'create')
    {

        $userConnect = $this->getUser();
        if (!$userConnect) {
            return $this->redirectToRoute("security_login");
        }
//        if (in_array('ROLE_ADMIN', $userConnect->getRoles()) === false) {
//
//            $this->addFlash("warning",
//                "Accès refusé : vous devez avoir un rôle administrateur");
//            return $this->redirectToRoute("home");
//        }


        $return = false;
        if ($form->isSubmitted() && $form->isValid()) {

            if ($type === 'create') {
                $task->setUsertd($userConnect)
                    ->setCreateAt(new \DateTime())
                    ->setIsDone(0);
            }
            $this->manager->persist($task);
            $this->manager->flush();
            $msg = ($type === 'create') ? "Tache ajoutée" : "Tache modifiée";
            $this->addFlash('success', $msg);

            $return = true;
        }

        return $return;
    }
}