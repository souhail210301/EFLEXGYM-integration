<?php

namespace App\Controller;
use Infobip\Configuration;

use Infobip\Model\SmsDestination;
use Infobip\Model\SmsTextualMessage;
use Infobip\Model\SmsAdvancedTextualRequest;
use Infobip\Api\SmsApi;
use Twilio\Rest\Client;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriterer;
use App\Entity\Participation;
use App\Entity\User;
use App\Entity\Evenement;
use App\Form\EvenementType;
use Doctrine\ORM\EntityManagerInterface;
use Endroid\QrCode\Writer\PngWriter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Endroid\QrCode\Color\Color;
use Symfony\Component\Security\Core\Security; // 
#[Route('/evenement')]
class EvenementController extends AbstractController
{

    private $security; // Declare Security class property

    public function __construct(Security $security)
    {
        $this->security = $security;
    }


    #[Route('/new', name: 'app_evenement_new', methods: ['GET', 'POST'])]
public function new(Request $request, EntityManagerInterface $entityManager): Response
{
    $evenement = new Evenement();
    $form = $this->createForm(EvenementType::class, $evenement);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Handle image upload if provided
        $imageFile = $form->get('image')->getData();
        if ($imageFile) {
            $newFilename = uniqid().'.'.$imageFile->guessExtension();
            // Move the uploaded file to the image directory
            $imageFile->move(
                $this->getParameter('kernel.project_dir').'/public/uploads',
                $newFilename
            );
            // Set the image path to the entity
            $evenement->setImage($newFilename);
        }

        $entityManager->persist($evenement);
        $entityManager->flush();

        return $this->redirectToRoute('app_evenement_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('evenement/new.html.twig', [
        'evenement' => $evenement,
        'form' => $form,
    ]);
}
#[Route('/', name: 'app_evenement_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $evenements = $entityManager
            ->getRepository(Evenement::class)
            ->findAll();

        return $this->render('evenement/index.html.twig', [
            'evenements' => $evenements,
        ]);
    }
    
#[Route('/events', name: 'app_evenement_events', methods: ['GET'])]
public function events(EntityManagerInterface $entityManager): Response
{
    $evenements = $entityManager
        ->getRepository(Evenement::class)
        ->findAll();

    return $this->render('evenement/event.html.twig', [
        'evenements' => $evenements,
    ]);
}


    #[Route('/{id}', name: 'app_evenement_show', methods: ['GET'])]
    public function show(Evenement $evenement): Response
    {
        return $this->render('evenement/show.html.twig', [
            'evenement' => $evenement,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_evenement_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Evenement $evenement, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EvenementType::class, $evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_evenement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('evenement/edit.html.twig', [
            'evenement' => $evenement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_evenement_delete', methods: ['POST'])]
    public function delete(Request $request, Evenement $evenement, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$evenement->getId(), $request->request->get('_token'))) {
            $entityManager->remove($evenement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_evenement_index', [], Response::HTTP_SEE_OTHER);
    }
    
    #[Route('/{id}/participate', name: 'app_evenement_participate', methods: ['POST'])]
public function participate(Request $request, Evenement $evenement, EntityManagerInterface $entityManager): Response
{
    // Create a new participation for the user with ID 3 and the specified event
    $user = $this->security->getUser();

        // Make sure the user is logged in
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        // Create a new participation for the logged-in user and the specified event
        $participation = new Participation();
        $participation->setIdUser($user);
        $participation->setIdEvenement($evenement);

        // Increment the number of participants by 1 for the event
        $nbrDeParticipant = $participation->getNbrDeParticipant() + 1;
        $participation->setNbrDeParticipant($nbrDeParticipant);

        $entityManager->persist($participation);
        $entityManager->flush();

   
    $qrCodeText = "User: " . $participation->getIdUser()->getName() . ", Event: " . $participation->getIdEvenement()->getEventName();
    $qr_code = Qrcode::create($qrCodeText)
                                ->setSize(600)
                                ->setMargin(40)
                                ->setForegroundColor(New Color(255, 128 ,0))
                                ->setBackgroundColor(New Color (155, 204, 255));
    $writer = new PngWriter;
    $result = $writer->write($qr_code);
    $response = new Response($result->getString());
    $response->headers->set('Content-Type', $result->getMimeType());
    $number = '+21693553223';
    $account_id = "ACf4352a83002361ab1f50319359fa0b46";
    $auth_token = "3f9d85d1c44dba18ce1d6d0f6a38bc43";

    $client = new Client($account_id, $auth_token);

    $twilio_number = "+16812011196";

    $client->messages->create(
        $number,
        [
            "from" => $twilio_number,
            "body" => $qrCodeText
        ]
    );


return $response;
    
}

    

#[Route('/{id}', name: 'app_evenement_details')]
public function details(Evenement $evenement, HttpClientInterface $client): Response
{
    $url = 'https://api.weatherapi.com/v1/forecast.json?key=3a408b6703c94995be2184734242804&q=Paris&days=1';
    $response = $client->request('GET', $url);
    $weatherData = $response->toArray();

    $weather = isset($weatherData['forecast']['forecastday'][0]['day']) ? $weatherData['forecast']['forecastday'][0]['day'] : null;

    return $this->render('evenement/details.html.twig', [
        'evenement' => $evenement,
        'weather' => $weather, // Pass the weather data to the template
    ]);
}
    
}

    


