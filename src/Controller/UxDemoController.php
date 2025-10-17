<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;
use Symfony\UX\Map\Map;
use Symfony\UX\Map\Point;
use Symfony\UX\Map\Marker;
use Symfony\UX\Map\InfoWindow;

#[Route('/ux-demo')]
class UxDemoController extends AbstractController
{
    #[Route('/', name: 'ux_demo_index')]
    public function index(): Response
    {
        return $this->render('ux_demo/index.html.twig');
    }

    #[Route('/chartjs', name: 'ux_demo_chartjs')]
    public function chartjs(ChartBuilderInterface $chartBuilder): Response
    {
        // Chart 1: Line Chart - Sales Data
        $lineChart = $chartBuilder->createChart(Chart::TYPE_LINE);
        $lineChart->setData([
            'labels' => ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet'],
            'datasets' => [
                [
                    'label' => 'Ventes 2024',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.2)',
                    'borderColor' => 'rgb(59, 130, 246)',
                    'data' => [30, 45, 35, 50, 65, 70, 85],
                    'tension' => 0.4,
                ],
                [
                    'label' => 'Ventes 2023',
                    'backgroundColor' => 'rgba(239, 68, 68, 0.2)',
                    'borderColor' => 'rgb(239, 68, 68)',
                    'data' => [20, 35, 30, 40, 50, 55, 60],
                    'tension' => 0.4,
                ],
            ],
        ]);
        $lineChart->setOptions([
            'responsive' => true,
            'plugins' => [
                'legend' => [
                    'position' => 'top',
                ],
                'title' => [
                    'display' => true,
                    'text' => 'Évolution des ventes mensuelles',
                ],
            ],
        ]);

        // Chart 2: Bar Chart - Revenue by Product
        $barChart = $chartBuilder->createChart(Chart::TYPE_BAR);
        $barChart->setData([
            'labels' => ['Produit A', 'Produit B', 'Produit C', 'Produit D', 'Produit E'],
            'datasets' => [
                [
                    'label' => 'Revenus (K€)',
                    'backgroundColor' => [
                        'rgba(59, 130, 246, 0.8)',
                        'rgba(16, 185, 129, 0.8)',
                        'rgba(251, 191, 36, 0.8)',
                        'rgba(239, 68, 68, 0.8)',
                        'rgba(168, 85, 247, 0.8)',
                    ],
                    'data' => [125, 89, 95, 110, 140],
                ],
            ],
        ]);
        $barChart->setOptions([
            'responsive' => true,
            'plugins' => [
                'legend' => [
                    'display' => false,
                ],
                'title' => [
                    'display' => true,
                    'text' => 'Revenus par produit',
                ],
            ],
        ]);

        // Chart 3: Pie Chart - Market Share
        $pieChart = $chartBuilder->createChart(Chart::TYPE_PIE);
        $pieChart->setData([
            'labels' => ['Desktop', 'Mobile', 'Tablette', 'Autres'],
            'datasets' => [
                [
                    'label' => 'Parts de marché',
                    'data' => [45, 35, 15, 5],
                    'backgroundColor' => [
                        'rgba(59, 130, 246, 0.8)',
                        'rgba(16, 185, 129, 0.8)',
                        'rgba(251, 191, 36, 0.8)',
                        'rgba(239, 68, 68, 0.8)',
                    ],
                ],
            ],
        ]);
        $pieChart->setOptions([
            'responsive' => true,
            'plugins' => [
                'legend' => [
                    'position' => 'right',
                ],
                'title' => [
                    'display' => true,
                    'text' => 'Répartition par type d\'appareil',
                ],
            ],
        ]);

        // Chart 4: Doughnut Chart - Budget Distribution
        $doughnutChart = $chartBuilder->createChart(Chart::TYPE_DOUGHNUT);
        $doughnutChart->setData([
            'labels' => ['Marketing', 'R&D', 'Opérations', 'Support', 'Administratif'],
            'datasets' => [
                [
                    'label' => 'Budget (%)',
                    'data' => [30, 25, 20, 15, 10],
                    'backgroundColor' => [
                        'rgba(59, 130, 246, 0.8)',
                        'rgba(16, 185, 129, 0.8)',
                        'rgba(251, 191, 36, 0.8)',
                        'rgba(239, 68, 68, 0.8)',
                        'rgba(168, 85, 247, 0.8)',
                    ],
                ],
            ],
        ]);
        $doughnutChart->setOptions([
            'responsive' => true,
            'plugins' => [
                'legend' => [
                    'position' => 'bottom',
                ],
                'title' => [
                    'display' => true,
                    'text' => 'Distribution du budget annuel',
                ],
            ],
        ]);

        return $this->render('ux_demo/chartjs.html.twig', [
            'lineChart' => $lineChart,
            'barChart' => $barChart,
            'pieChart' => $pieChart,
            'doughnutChart' => $doughnutChart,
        ]);
    }

    #[Route('/map', name: 'ux_demo_map')]
    public function map(): Response
    {
        // Création de la carte centrée sur Paris
        $map = (new Map())
            ->center(new Point(48.8566, 2.3522))
            ->zoom(12);

        // Ajout de marqueurs pour différents lieux touristiques de Paris
        $map
            ->addMarker(new Marker(
                position: new Point(48.8584, 2.2945),
                title: 'Tour Eiffel',
                infoWindow: new InfoWindow(
                    headerContent: '<strong>Tour Eiffel</strong>',
                    content: 'Monument emblématique de Paris'
                )
            ))
            ->addMarker(new Marker(
                position: new Point(48.8606, 2.3376),
                title: 'Musée du Louvre',
                infoWindow: new InfoWindow(
                    headerContent: '<strong>Musée du Louvre</strong>',
                    content: 'Le plus grand musée d\'art au monde'
                )
            ))
            ->addMarker(new Marker(
                position: new Point(48.8530, 2.3499),
                title: 'Cathédrale Notre-Dame',
                infoWindow: new InfoWindow(
                    headerContent: '<strong>Notre-Dame de Paris</strong>',
                    content: 'Cathédrale gothique historique'
                )
            ))
            ->addMarker(new Marker(
                position: new Point(48.8867, 2.3431),
                title: 'Sacré-Cœur',
                infoWindow: new InfoWindow(
                    headerContent: '<strong>Basilique du Sacré-Cœur</strong>',
                    content: 'Située au sommet de Montmartre'
                )
            ))
            ->addMarker(new Marker(
                position: new Point(48.8738, 2.2950),
                title: 'Arc de Triomphe',
                infoWindow: new InfoWindow(
                    headerContent: '<strong>Arc de Triomphe</strong>',
                    content: 'Monument commémoratif'
                )
            ));

        return $this->render('ux_demo/map.html.twig', [
            'map' => $map,
        ]);
    }

    #[Route('/turbo', name: 'ux_demo_turbo')]
    public function turbo(): Response
    {
        return $this->render('ux_demo/turbo.html.twig');
    }

    #[Route('/turbo/frame1', name: 'ux_demo_turbo_frame1')]
    public function turboFrame1(): Response
    {
        return $this->render('ux_demo/turbo_frame1.html.twig', [
            'loadTime' => date('H:i:s'),
        ]);
    }

    #[Route('/turbo/frame2', name: 'ux_demo_turbo_frame2')]
    public function turboFrame2(): Response
    {
        return $this->render('ux_demo/turbo_frame2.html.twig', [
            'loadTime' => date('H:i:s'),
        ]);
    }

    #[Route('/stimulus', name: 'ux_demo_stimulus')]
    public function stimulus(): Response
    {
        return $this->render('ux_demo/stimulus.html.twig');
    }
}
