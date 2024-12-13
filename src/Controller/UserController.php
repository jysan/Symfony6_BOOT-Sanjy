<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/user/profile', name: 'user_profile')]
    public function profile(Request $request): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'Vos informations ont été mises à jour.');

            return $this->redirectToRoute('user_profile');
        }

        return $this->render('user/profile.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    // Création d'une réservation
    #[Route('/user/reservation/new', name: 'user_reservation_new')]
    public function createReservation(Request $request): Response
    {
        $reservation = new Reservation();
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Vous pouvez ajouter ici des règles supplémentaires pour valider les réservations
            $reservation->setRelations($this->getUser());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($reservation);
            $entityManager->flush();

            $this->addFlash('success', 'Votre réservation a été créée.');

            return $this->redirectToRoute('user_reservation_history');
        }

        return $this->render('user/reservation/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    // Consultation de l'historique des réservations
    #[Route('/user/reservations', name: 'user_reservation_history')]
    public function reservationHistory(): Response
    {
        $user = $this->getUser();
        $reservations = $user->getUserReservations(); // Vous devez avoir une méthode pour récupérer les réservations de l'utilisateur
        return $this->render('user/reservation/history.html.twig', [
            'reservations' => $reservations,
        ]);
    }
}
