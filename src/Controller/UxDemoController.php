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
        // Création de la carte centrée sur l'Europe avec un zoom adapté pour voir tous les campus
        $map = (new Map())
            ->center(new Point(48.8566, 2.3522))
            ->zoom(3);

        // Campus en France
        $map
            ->addMarker(new Marker(
                position: new Point(48.8566, 2.3522),
                title: 'OMNES Education Paris',
                infoWindow: new InfoWindow(
                    headerContent: '<strong>Campus Paris</strong>',
                    content: 'ESCE, ECE Executive Education, INSEEC, HEIP, Sup Career, Sup de Création, Sup de Pub'
                )
            ))
            ->addMarker(new Marker(
                position: new Point(45.7640, 4.8357),
                title: 'OMNES Education Lyon',
                infoWindow: new InfoWindow(
                    headerContent: '<strong>Campus Lyon</strong>',
                    content: 'ESCE, ECE, HEIP, INSEEC'
                )
            ))
            ->addMarker(new Marker(
                position: new Point(44.8378, -0.5792),
                title: 'OMNES Education Bordeaux',
                infoWindow: new InfoWindow(
                    headerContent: '<strong>Campus Bordeaux</strong>',
                    content: 'ECE, INSEEC'
                )
            ))
            ->addMarker(new Marker(
                position: new Point(45.5647, 5.9178),
                title: 'OMNES Education Chambéry',
                infoWindow: new InfoWindow(
                    headerContent: '<strong>Campus Chambéry</strong>',
                    content: 'INSEEC'
                )
            ))
            ->addMarker(new Marker(
                position: new Point(47.0230, 4.8336),
                title: 'OMNES Education Beaune',
                infoWindow: new InfoWindow(
                    headerContent: '<strong>Campus Beaune</strong>',
                    content: 'INSEEC'
                )
            ))
            ->addMarker(new Marker(
                position: new Point(48.1173, -1.6778),
                title: 'OMNES Education Rennes',
                infoWindow: new InfoWindow(
                    headerContent: '<strong>Campus Rennes</strong>',
                    content: 'ECE, HEIP, INSEEC, Sup de Pub'
                )
            ))
            ->addMarker(new Marker(
                position: new Point(43.2965, 5.3698),
                title: 'OMNES Education Marseille',
                infoWindow: new InfoWindow(
                    headerContent: '<strong>Campus Marseille</strong>',
                    content: 'INSEEC, ESCE, ECE, HEIP, Sup de Création, Sup de Pub'
                )
            ))
            ->addMarker(new Marker(
                position: new Point(43.6047, 1.4442),
                title: 'OMNES Education Toulouse',
                infoWindow: new InfoWindow(
                    headerContent: '<strong>Campus Toulouse</strong>',
                    content: 'Campus en développement'
                )
            ))

            // Campus internationaux
            ->addMarker(new Marker(
                position: new Point(5.3600, -4.0083),
                title: 'OMNES Education Abidjan',
                infoWindow: new InfoWindow(
                    headerContent: '<strong>Campus Abidjan</strong>',
                    content: 'IFG Executive Education'
                )
            ))
            ->addMarker(new Marker(
                position: new Point(41.3851, 2.1734),
                title: 'OMNES Education Barcelone',
                infoWindow: new InfoWindow(
                    headerContent: '<strong>Campus Barcelone</strong>',
                    content: 'EU Business School'
                )
            ))
            ->addMarker(new Marker(
                position: new Point(46.2044, 6.1432),
                title: 'OMNES Education Genève',
                infoWindow: new InfoWindow(
                    headerContent: '<strong>Campus Genève</strong>',
                    content: 'CREA, EU Business School'
                )
            ))
            ->addMarker(new Marker(
                position: new Point(46.5197, 6.6323),
                title: 'OMNES Education Lausanne',
                infoWindow: new InfoWindow(
                    headerContent: '<strong>Campus Lausanne</strong>',
                    content: 'CREA'
                )
            ))
            ->addMarker(new Marker(
                position: new Point(51.5074, -0.1278),
                title: 'OMNES Education Londres',
                infoWindow: new InfoWindow(
                    headerContent: '<strong>Campus Londres</strong>',
                    content: 'Campus multi-écoles'
                )
            ))
            ->addMarker(new Marker(
                position: new Point(40.4168, -3.7038),
                title: 'OMNES Education Madrid',
                infoWindow: new InfoWindow(
                    headerContent: '<strong>Campus Madrid</strong>',
                    content: 'Campus international'
                )
            ))
            ->addMarker(new Marker(
                position: new Point(43.7384, 7.4246),
                title: 'OMNES Education Monaco',
                infoWindow: new InfoWindow(
                    headerContent: '<strong>Campus Monaco</strong>',
                    content: 'IUM'
                )
            ))
            ->addMarker(new Marker(
                position: new Point(48.1351, 11.5820),
                title: 'OMNES Education Munich',
                infoWindow: new InfoWindow(
                    headerContent: '<strong>Campus Munich</strong>',
                    content: 'EU Business School'
                )
            ))
            ->addMarker(new Marker(
                position: new Point(37.7749, -122.4194),
                title: 'OMNES Education San Francisco',
                infoWindow: new InfoWindow(
                    headerContent: '<strong>Campus San Francisco</strong>',
                    content: 'Campus multi-écoles'
                )
            ))
            ->addMarker(new Marker(
                position: new Point(37.3891, -5.9845),
                title: 'OMNES Education Séville',
                infoWindow: new InfoWindow(
                    headerContent: '<strong>Campus Séville</strong>',
                    content: 'Campus international'
                )
            ))
            ->addMarker(new Marker(
                position: new Point(39.4699, -0.3763),
                title: 'OMNES Education Valence',
                infoWindow: new InfoWindow(
                    headerContent: '<strong>Campus Valence</strong>',
                    content: 'Campus international'
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

    #[Route('/docs', name: 'ux_demo_docs')]
    public function documentation(): Response
    {
        return $this->render('ux_demo/docs.html.twig');
    }

    #[Route('/autocomplete', name: 'ux_demo_autocomplete')]
    public function autocomplete(): Response
    {
        return $this->render('ux_demo/autocomplete.html.twig');
    }

    #[Route('/dropzone', name: 'ux_demo_dropzone')]
    public function dropzone(): Response
    {
        return $this->render('ux_demo/dropzone.html.twig');
    }

    #[Route('/cropper', name: 'ux_demo_cropper')]
    public function cropper(): Response
    {
        return $this->render('ux_demo/cropper.html.twig');
    }

    #[Route('/notify', name: 'ux_demo_notify')]
    public function notify(): Response
    {
        return $this->render('ux_demo/notify.html.twig');
    }
}
