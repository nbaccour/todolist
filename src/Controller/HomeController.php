<?php

namespace App\Controller;

use App\Repository\TaskRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{

    protected $repository;
    protected $paginator;

    public function __construct(
        TaskRepository $repository,
        PaginatorInterface $paginator
    ) {
        $this->repository = $repository;
        $this->paginator = $paginator;
    }

    /**
     * @Route("/", name="home")
     */
    public function index(Request $request): Response
    {
        $tasks = $this->repository->findBy([], ['id' => 'DESC']);

        $tasksList = $this->paginator->paginate(
            $tasks, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            6 // Nombre de résultats par page
        );

        return $this->render('home.html.twig', ['tasks' => $tasksList]);
    }
}
