<?php

namespace App\Controller;

use App\Repository\LogRepository;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LogController extends AbstractController
{
    #[Route('/logs', name: 'log_list')]
    public function index(LogRepository $logRepository, Request $request): Response
    {
        $queryBuilder = $logRepository->createQueryBuilder('l')
            ->orderBy('l.Fecha', 'DESC');

        // Crear el adaptador para la consulta
        $adapter = new QueryAdapter($queryBuilder);

        // Crear el paginador
        $pagerfanta = new Pagerfanta($adapter);

        // Configurar el número de ítems por página
        $pagerfanta->setMaxPerPage(100);

        // Configurar la página actual desde la solicitud
        $currentPage = $request->query->getInt('page', 1);
        $pagerfanta->setCurrentPage($currentPage);

        return $this->render('log/index.html.twig', [
            'logs' => $pagerfanta, // Pasar el paginador a la vista
        ]);
    }
}
