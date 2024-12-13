<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Form\ReservationType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class ReservationController extends AbstractController
{
    /**
     * @Route("/api/reservation/new", name="api_reservation_new", methods={"POST"})
     */
    public function new(Request $request, ValidatorInterface $validator): JsonResponse
    {
        // Récupérer les données JSON envoyées dans le body de la requête
        $data = json_decode($request->getContent(), true);

        // Créer une nouvelle réservation à partir des données envoyées
        $reservation = new Reservation();
        $reservation->setDate(new \DateTime($data['date']))
                    ->setTimeSlot($data['timeSlot'])
                    ->setEventName($data['eventName']);

        // Valider l'entité
        $errors = $validator->validate($reservation);

        if (count($errors) > 0) {
            // Si des erreurs sont présentes, les formater et les renvoyer dans la réponse
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = $error->getMessage();
            }
            return new JsonResponse(['errors' => $errorMessages], Response::HTTP_BAD_REQUEST);
        }

        // Si aucune erreur, persister la réservation
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($reservation);
        $entityManager->flush();

        // Retourner une réponse JSON indiquant que la réservation a été créée
        return new JsonResponse([
            'message' => 'Réservation effectuée avec succès.',
            'data' => [
                'id' => $reservation->getId(),
                'date' => $reservation->getDate()->format('d/m/Y'),
                'timeSlot' => $reservation->getTimeSlot(),
                'eventName' => $reservation->getEventName()
            ]
        ], Response::HTTP_CREATED);
    }
}
