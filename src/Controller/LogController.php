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
        $queryBuilder = $logRepository->createQueryBuilder('l');

        // Obtener parámetros de filtro
        $usuario = $request->query->get('usuario');
        $accion = $request->query->get('accion');
        $fechaDesde = $request->query->get('fecha_desde');
        $fechaHasta = $request->query->get('fecha_hasta');

        // Aplicar filtros
        if ($usuario) {
            $queryBuilder->andWhere('l.Usuario LIKE :usuario')
                ->setParameter('usuario', '%' . $usuario . '%');
        }

        if ($accion) {
            $queryBuilder->andWhere('l.Accion LIKE :accion')
                ->setParameter('accion', '%' . $accion . '%');
        }

        if ($fechaDesde) {
            $queryBuilder->andWhere('l.Fecha >= :fechaDesde')
                ->setParameter('fechaDesde', new \DateTime($fechaDesde));
        }

        if ($fechaHasta) {
            $queryBuilder->andWhere('l.Fecha <= :fechaHasta')
                ->setParameter('fechaHasta', new \DateTime($fechaHasta));
        }

        // Ordenar y paginar
        $queryBuilder->orderBy('l.Fecha', 'DESC');
        $adapter = new QueryAdapter($queryBuilder);
        $pagerfanta = new Pagerfanta($adapter);
        $pagerfanta->setMaxPerPage(100);
        $currentPage = $request->query->getInt('page', 1);
        $pagerfanta->setCurrentPage($currentPage);

        return $this->render('log/index.html.twig', [
            'logs' => $pagerfanta,
        ]);
    }
    
    // ruta y controlador para estadísticas
    #[Route('/logs/estadisticas', name: 'log_stats')]
    public function stats(LogRepository $logRepository): Response
    {
        $actionsByUsuario = $logRepository->countActionsByUsuario();
        $actionsByAccion = $logRepository->countActionsByAccion();

        return $this->render('log/stats.html.twig', [
            'actionsByUsuario' => $actionsByUsuario,
            'actionsByAccion' => $actionsByAccion,
        ]);
    }

}
