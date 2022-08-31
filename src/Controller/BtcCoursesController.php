<?php
namespace App\Controller;

use App\Entity\BtcCourses;
use App\Repository\BtcCoursesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/data')]
class BtcCoursesController extends AbstractController
{
    #[Route('/', name: 'app_btc', methods: ['GET'])]
    public function getAllCourses(BtcCoursesRepository $btcCoursesRepository): bool
    {
        $link = 'https://blockchain.info/ticker';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $link);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json')); // Assuming you're requesting JSON
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($ch);
        $courses = json_decode($response, true);

        $record = new BtcCourses();
        $record->setCourse($courses['USD']['buy']);
        $record->setCurrency('USD');
        $record->setDataTime(new \DateTime());
        $btcCoursesRepository->add($record, true);
        return true;
    }
}
