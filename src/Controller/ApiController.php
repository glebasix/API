<?php

namespace App\Controller;

use App\Repository\BtcCoursesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\BtcCourses;
use MonterHealth\ApiFilterBundle\MonterHealthApiFilter;
use Symfony\Component\HttpFoundation\JsonResponse;


#[Route('/api')]
class ApiController extends AbstractController
{
    #[Route('/btc', name: 'btc_index', methods: ['GET'])]
    public function index(ManagerRegistry $doctrine): Response
    {
        $bitcoins = $doctrine
            ->getRepository(BtcCourses::class)
            ->findAll();

        $data = [];

        foreach ($bitcoins as $bitcoin) {
            $data[] = [
                'id' => $bitcoin->getId(),
                'currency' => $bitcoin->getCurrency(),
                'course' => $bitcoin->getCourse(),
                'dataTime' => $bitcoin->getDataTime(),
            ];
        }
        return $this->json($data);
    }

    #[Route('/btc', name: 'btc_new', methods: ['POST'])]
    public function new(ManagerRegistry $doctrine, Request $request): Response
    {
        $entityManager = $doctrine->getManager();

        $bitcoin = new BtcCourses();
        $bitcoin->setCurrency($request->request->get('currency'));
        $bitcoin->setCourse($request->request->get('course'));
        $bitcoin->setDataTime(new \DateTime());

        $entityManager->persist($bitcoin);
        $entityManager->flush();

        return $this->json('Created new project successfully with id ' . $bitcoin->getId());
    }

    #[Route('/btc/{id}', name: 'btc_show', methods: ['GET'])]
    public function show(ManagerRegistry $doctrine, int $id): Response
    {
        $bitcoin = $doctrine->getRepository(BtcCourses::class)->find($id);

        if (!$bitcoin) {

            return $this->json('No project found for id' . $id, 404);
        }

        $data = [
            'id' => $bitcoin->getId(),
            'currency' => $bitcoin->getCurrency(),
            'course' => $bitcoin->getCourse(),
            'dataTime' => $bitcoin->getDataTime(),
        ];

        return $this->json($data);
    }

    #[Route('/btc/{id}', name: 'btc_edit', methods: ['PUT'])]
    public function edit(ManagerRegistry $doctrine, Request $request, int $id): Response
    {
        $entityManager = $doctrine->getManager();
        $bitcoin = $entityManager->getRepository(BtcCourses::class)->find($id);

        if (!$bitcoin) {
            return $this->json('No project found for id' . $id, 404);
        }

        $bitcoin->setCurrency($request->request->get('currency'));
        $bitcoin->setCourse($request->request->get('course'));
        $entityManager->flush();

        $data = [
            'id' => $bitcoin->getId(),
            'currency' => $bitcoin->getCurrency(),
            'course' => $bitcoin->getCourse(),
            'dataTime' => $bitcoin->getDataTime(),
        ];
        return $this->json($data);
    }

    #[Route('/btc/{id}', name: 'btc_delete', methods: ['DELETE'])]
    public function delete(ManagerRegistry $doctrine, int $id): Response
    {
        $entityManager = $doctrine->getManager();
        $bitcoin = $entityManager->getRepository(BtcCourses::class)->find($id);

        if (!$bitcoin) {
            return $this->json('No project found for id' . $id, 404);
        }

        $entityManager->remove($bitcoin);
        $entityManager->flush();

        return $this->json('Deleted a project successfully with id ' . $id);
    }


    #[Route('/filter', name: 'btc_filter', methods: ['GET'])]
    public function dateFilter(BtcCoursesRepository $btcCoursesRepository, Request $request): Response
    {
        if ($request->query->count()) {
            if ($request->query->get('startDate')){
                $start = $request->query->get('startDate');
                $start = $start." 00:00:00";
            } else {
                $start = "1500-1-1 00:00:00";
            }

            if ($request->query->get('endDate')){
                $end = $request->query->get('endDate');
                $end = $end." 23:59:59";
            } else {
                $end = "2500-1-1 00:00:00";
            }

            $bitcoins = $btcCoursesRepository->findByDateField($start, $end);
            if ($request->query->get('currency')){
                $currency = $request->query->get('currency');
                $bitcoins = $btcCoursesRepository->findByCurrencyField($currency);
            }
            $data = [];
            foreach ($bitcoins as $bitcoin) {
                $data[] = [
                    'id' => $bitcoin->getId(),
                    'currency' => $bitcoin->getCurrency(),
                    'course' => $bitcoin->getCourse(),
                    'dataTime' => $bitcoin->getDataTime(),
                ];
            }
            return $this->json($data);
        } else {
            return $this->json('No parameters found, check your parameters', 404);
        }
    }
}