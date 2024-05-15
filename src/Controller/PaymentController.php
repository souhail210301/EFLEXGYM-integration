<?php

namespace App\Controller;

use App\Service\panierService;
use Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;           
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Stripe\Charge;
use Symfony\Component\Security\Core\Security;



class PaymentController extends AbstractController
{
    #[Route('/command/stripe', name: 'app_stripe')]
    public function index(panierService $basketService, UserRepository $userRep, Security $security, Request $request): Response
    {


        $id_user = 3; 

        $basketData = $basketService->getCartItems($id_user);
        $basketItemsCount = count($basketData);
        //$connectedUser = $userRep->find(32);

        $totalPrice = array_reduce($basketData, function ($total, $product) {
            return $total + $product->getIdProduit()->getPrix();
        }, 0);

        

        return $this->render('payment/payment.html.twig', [
            'stripe_key' => $_ENV["STRIPE_KEY"],
            'totalPrice' => $totalPrice,
            'basketItemsCount' => $basketItemsCount,
        ]);
    }


    #[Route('/stripe/create-charge', name: 'app_stripe_charge', methods: ['POST'])]
public function createCharge(Request $request, panierService $basketService, UserRepository $userRep)
{
    $id_user = 3; 

    $basketData = $basketService->getCartItems($id_user);
    $basketItemsCount = count($basketData);

    $totalPrice = array_reduce($basketData, function ($total, $product) {
        return $total + $product->getIdProduit()->getPrix();
    }, 0);


    // Log the total price for debugging
    error_log("Total Price: " . $totalPrice);

    Stripe\Stripe::setApiKey($_ENV["STRIPE_SECRET"]);

    try {
        Stripe\Charge::create([
            "amount" => $totalPrice * 100,  // Amount in cents
            "currency" => "usd",
            "source" => $request->request->get('stripeToken'),
            "description" => "Paiement de la commande via EFLEXGYM",
            "metadata" => [
                "client_name" => "John Doe"
            ]
        ]);

        $this->addFlash(
            'successPaiement',
            'Payment succées!',
        );

        $basketService->emptyCart($id_user);

        return $this->redirectToRoute('produits');

    } catch (\Exception $e) {
        // Log the exception for debugging
        error_log("Error creating charge: " . $e->getMessage());
        
        $this->addFlash(
            'errorPaiement',
            'Error processing payment!',
        );

        return $this->redirectToRoute('app_stripe');
    }
}

}