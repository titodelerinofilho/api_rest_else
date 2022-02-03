<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Lecture;
use App\Entity\Event;


/**
     * @Route("/lectures", name="lecture_")
     */

class LectureController extends AbstractController
{
    #[Route('/', name: 'index', methods: 'GET')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $event = new Event();

        $entityManager = $doctrine->getManager();
        $lectures = $entityManager->getRepository(Lecture::class)->findAll();

        if($lectures == NULL) {
            return new JsonResponse(['lecture' => 'Não há palestras!']);
        } 
        else 
        {
            $data = [];
            foreach ((array) $lectures as $lecture) {

                $event_id = $lecture->getEventId();
                $event_relate = $entityManager->getRepository(Event::class)->find($event_id);
                $event_name = $event_relate->getTitle();

                $data[] = [
                    'id' => $lecture->getId(),
                    'title' => $lecture->getTitle(),
                    'event_id' => $lecture->getEventId(),
                    'event_name' => $event_name,
                    'date' => $lecture->getDate(),
                    'time_start' => $lecture->getTimeStart(),
                    'time_end' => $lecture->getTimeEnd(),
                    'description' => $lecture->getDescription(),
                    'speaker' => $lecture->getSpeaker(),
                    'created_at' => $lecture->getCreatedAt(),
                    'updated_at' => $lecture->getUpdatedAt()
                    ];
            }
            return new JsonResponse(['lecture' => $data]);
        }
    }

    #[Route('/{LectureId}', name: 'showOnlyLecture', methods: 'GET')]
    public function showOnly(): Response
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/LectureController.php',
        ]);
    }

    #[Route('/', name: 'create', methods: 'POST')]
    public function create(ManagerRegistry $doctrine, Request $request): Response
    {
        $data = $request->request->all();
        $entityManager = $doctrine->getManager();

        $lectures = new Lecture();

        $date_start = new \DateTime($data['date']);
        $date_start->format('Y-m-d');

        $time_start = new \DateTime($data['time_start']);
        $time_start->format('H:i:s');

        $time_end = new \DateTime($data['time_end']);
        $time_end->format('H:i:s');

        $lectures->setTitle($data['title']);
        $lectures->setDate($date_start);
        $lectures->setEventId($data['event_id']);
        $lectures->setTimeStart($time_start);
        $lectures->setTimeEnd($time_end);
        $lectures->setDescription($data['description']);
        $lectures->setSpeaker($data['speaker']);
        $lectures->setCreatedAt(new \DateTime('now', new \DateTimezone('America/Sao_Paulo')));
        $lectures->setUpdatedAt(new \DateTime('now', new \DateTimezone('America/Sao_Paulo')));

        $entityManager->persist($lectures);
        $entityManager->flush();

        return new JsonResponse(['lecture' => 'Evento criado com sucesso']);
    }

    #[Route('/{LectureId}', name: 'update', methods: 'PUT|PATCH')]
    public function update(): Response
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/LectureController.php',
        ]);
    }

    #[Route('/{LectureId}', name: 'delete', methods: 'DELETE')]
    public function delete(): Response
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/LectureController.php',
        ]);
    }
}
