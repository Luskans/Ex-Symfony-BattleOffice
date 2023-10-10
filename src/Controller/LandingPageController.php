<?php

namespace App\Controller;

use App\Entity\Address;
use App\Entity\Client;
use App\Entity\Country;
use App\Entity\Order;
use App\Entity\PaymentMethod;
use App\Entity\Product;
use App\Entity\Status;
use App\Form\OrderType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LandingPageController extends AbstractController
{
    #[Route('/', name: 'landing_page', methods: ['GET', 'POST'])]
    // public function index(Request $request, EntityManagerInterface $entityManager, Client $client, Address $address, Country $country, PaymentMethod $paymentMethod, Product $product, Status $status, Order $order)
    public function index(Request $request, EntityManagerInterface $entityManager)
    {
        $order = new Order();
        $form = $this->createForm(OrderType::class, $order);
        $form->handleRequest($request);
        // dd($form->getData());

        if ($form->isSubmitted() && $form->isValid()) {
            dd($form->getData());           

            return $this->redirectToRoute('confirmation');
        }

        return $this->render('landing_page/index_new.html.twig', [
            'orderForm' => $form
        ]);
    }
    
    #[Route('/confirmation', name: 'confirmation')]
    public function confirmation()
    {
        return $this->render('landing_page/confirmation.html.twig', [

        ]);
    }
}
