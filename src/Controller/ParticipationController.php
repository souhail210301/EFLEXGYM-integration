<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Evenement;
use App\Entity\Participation;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

#[Route('/participate')]
class ParticipationController extends AbstractController
{
    #[Route('/participate/{eventId}', name: 'app_participation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, $eventId): Response
    {
        $event = $entityManager->getRepository(Evenement::class)->find($eventId);
        $user = $this->getUser();

        if (!$event) {
            // Handle error, event not found
            // You can return a response or redirect to an error page
        }

        $participation = new Participation();
        $participation->setIdUser($user);
        $participation->setIdEvenement($event);
        $participation->setNbrDeParticipant($event->getNbrDeParticipant() + 1);

        $form = $this->createFormBuilder($participation)
            ->add('user', HiddenType::class, ['data' => $user]) // Static user ID
            ->add('event', HiddenType::class, ['data' => $eventId])
            ->add('nbrDeParticipant', HiddenType::class, ['data' => $event->getNbrDeParticipant() + 1])
            ->add('save', SubmitType::class, ['label' => 'Participate'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($participation);
            $entityManager->flush();

            // Redirect to a success page or back to the event page
            return $this->redirectToRoute('event_details', ['eventId' => $eventId]);
        }

        return $this->renderForm('participation/new.html.twig', [
            'participation' => $participation,
            'form' => $form,
        ]);
    }
}
