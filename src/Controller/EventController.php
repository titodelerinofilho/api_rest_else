<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Event;


    /**
     * @Route("/event", name="event_")
     */

class EventController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function index(ManagerRegistry $doctrine): Response
    {
        $doc = $doctrine->getManager();
        $events = $doc->getRepository(Event::class)->findAll();

        if($events == NULL) {
            return new JsonResponse(['event' => 'Não há eventos!']);
        } 
        else 
        {
            $data = [];
            foreach ((array) $events as $event) {
                $data[] = [
                    'id' => $event->getId(),
                    'title' => $event->getTitle(),
                    'date_start' => $event->getDateStart(),
                    'date_end' => $event->getDateEnd(),
                    'date_start' => $event->getDateStart(),
                    'description' => $event->getDescription(),
                    'status' => $event->getStatus(),
                    'created_at' => $event->getCreatedAt(),
                    'updated_at' => $event->getUpdatedAt()
                    ];
            }
            return new JsonResponse(['event' => $data]);
        }
    }
    /**
     * @Route("/{EventId}", name="showOnly", methods={"GET"})
     */
    public function showOnly(ManagerRegistry $doctrine, $EventId)
    {
        $doc = $doctrine->getManager();
        $events = $doc->getRepository(Event::class)->find($EventId);

        if($events == NULL) {
            return new JsonResponse(['event' => 'Não há eventos com o ID '. $EventId ]);
        } else {

        $data = [
            'id' => $events->getId(),
            'title' => $events->getTitle(),
            'date_start' => $events->getDateStart(),
            'date_end' => $events->getDateEnd(),
            'date_start' => $events->getDateStart(),
            'description' => $events->getDescription(),
            'status' => $events->getStatus(),
            'created_at' => $events->getCreatedAt(),
            'updated_at' => $events->getUpdatedAt()
        ];

            return new JsonResponse($data);
        }
    }
    /**
     * @Route("/", name="create", methods={"POST"})
     */
    public function create(ManagerRegistry $doctrine, Request $request) 
    {
        $data = $request->request->all();
        $doc = $doctrine->getManager();

        $event = new Event();

        $event->setTitle($data['title']);
        $event->setDateStart(new \DateTime('now', new \DateTimezone('America/Sao_Paulo')));
        $event->setDateEnd(new \DateTime('now', new \DateTimezone('America/Sao_Paulo')));
        $event->setDescription($data['description']);
        $event->setStatus($data['status']);
        $event->setCreatedAt(new \DateTime('now', new \DateTimezone('America/Sao_Paulo')));
        $event->setUpdatedAt(new \DateTime('now', new \DateTimezone('America/Sao_Paulo')));

        $doc->persist($event);
        $doc->flush();

        return new JsonResponse(['event' => 'Evento criado com sucesso']);
    }

    /**
     * @Route("/{EventId}", name="update", methods={"PUT","PATCH"})
     */
     public function update(Request $request, ManagerRegistry $doctrine, $EventId) 
     {
        $doc = $doctrine->getManager();
        $data = $request->request->all();

        $event = $doc->getRepository(Event::class)->find($EventId);
        if($request->request->has('title')) 
        {
            $event->setTitle($data['title']);
        }
        if($request->request->has('description'))
        {
            $event->setDescription($data['description']);
        }
        if($request->request->has('status'))
        {
            $event->setStatus($data['status']);
        }
        $event->setUpdatedAt(new \DateTime('now', new \DateTimezone('America/Sao_Paulo')));
        $doc->flush();

        return new JsonResponse(['data' => 'Evento de ID '.$EventId.' atualizado com sucesso!']);
     }

     /**
     * @Route("/{EventId}", name="delete", methods={"DELETE"})
     */
    public function delete(ManagerRegistry $doctrine, $EventId) 
    {
        $doc = $doctrine->getManager();

        $event = $doc->getRepository(Event::class)->find($EventId);
        $remove = $doc->remove($event);
        $doc->flush();

        return new JsonResponse(['data' => 'Evento de ID '.$EventId.' REMOVIDO com sucesso!']);
    }
}
