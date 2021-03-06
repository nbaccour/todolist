<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskmodifyType;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
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
    protected $paginator;

    public function __construct(
        TaskRepository $repository,
        EntityManagerInterface $manager,
        PaginatorInterface $paginator
    ) {
        $this->repository = $repository;
        $this->manager = $manager;
        $this->paginator = $paginator;
    }


    /**
     * @Route("/tasks", name="task_show")
     */
    public function show(Request $request): Response
    {

//        $userConnect = $this->getUser();
//        if (!$userConnect) {
//            return $this->redirectToRoute("security_login");
//        }
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY', null, "Accès refusé : vous devez etre connecté");
        $tasks = $this->repository->findBy([], ['id' => 'DESC']);

        $tasksList = $this->paginator->paginate(
            $tasks, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            10 // Nombre de résultats par page
        );


        return $this->render('/task/list.html.twig', ['tasks' => $tasksList]);


    }

    /**
     * @Route("/mytask", name="task_mytask")
     */
    public function mytask()
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY', null, "Accès refusé : vous devez etre connecté");
        $user = $this->getUser();
        return $this->render('/user/mytask.html.twig', ['tasks' => $user->getTask()]);


    }

    /**
     * @Route("/tasks/create", name="task_create")
     * @IsGranted("ROLE_USER", message="Vous devez etres connecté pour acceder à vos taches")
     */
    public function create(Request $request)
    {

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY', null, "Accès refusé : vous devez etre connecté");

        $task = new Task();

        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

        }

        $create = $this->createOrUpdate($form, $task);

        if ($create === true) {
            return $this->redirectToRoute("task_mytask");
        }


        return $this->render('/task/create.html.twig', ['formView' => $form->createView(), 'task' => $task]);

    }

    /**
     * @Route("/tasks/modify/{id}", name="task_modify")
     */
    public function modify($id, Request $request, Security $security)
    {

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY', null, "Accès refusé : vous devez etre connecté");

        $task = $this->repository->find($id);

        if (!$task) {
            throw $this->createNotFoundException("La tache $id n'existe pas");
        }

        $this->denyAccessUnlessGranted('CAN_EDIT', $task, "Vous n'êtes pas le propriétaire de cette tache");


        $form = $this->createForm(TaskmodifyType::class, $task);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

        }

        $modify = $this->createOrUpdate($form, $task, 'modify');
        if ($modify === true) {

            return $this->redirectToRoute("task_mytask");
        }


        return $this->render('/task/modify.html.twig',
            ['formView' => $form->createView(), 'task' => $task]);
    }

    /**
     * @Route("/tasks/delete/{id}", name="task_delete")
     */
    public function delete($id)
    {

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY', null, "Accès refusé : vous devez etre connecté");

        $task = $this->repository->find($id);

        if (!$task) {
            throw $this->createNotFoundException("La tache $id n'existe pas");
        }


        $this->manager->remove($task);
        $this->manager->flush();

        $this->addFlash("warning", "La tâche a bien été supprimée.");

        return $this->redirectToRoute("task_mytask");
    }

    public function createOrUpdate($form, $task, string $type = 'create')
    {

        $user = $this->getUser();
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY', null, "Accès refusé : vous devez etre connecté");

        $return = false;
        if ($form->isSubmitted() && $form->isValid()) {

            if ($type === 'create') {
                $task->setUsertd($user)
                    ->setCreateAt(new \DateTime())
                    ->setIsDone(0);
            }
            $this->manager->persist($task);
            $this->manager->flush();
            $msg = ($type === 'create') ? "La tâche a été bien été ajoutée." : "La tâche a bien été modifiée.";
            $this->addFlash('success', $msg);

            $return = true;
        }

        return $return;
    }
}
