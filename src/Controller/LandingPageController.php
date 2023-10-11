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
            // dd($form->getData());

            $billingAddress = $form['billingAddress']->getData();
            if (!$billingAddress || empty($billingAddress)) {
                $order->setBillingAddress(new Address);
                $order->getBillingAddress()->setClient(new Client);
                $order->getBillingAddress()->setCountry(new Country);
                $order->getBillingAddress()->getClient()->setFirstname($form['deliveryAddress']['client']['firstname']->getData());
                $order->getBillingAddress()->getClient()->setLastname($form['deliveryAddress']['client']['firstname']->getData());
                $order->getBillingAddress()->setLine1($form['deliveryAddress']['line1']->getData());
                $order->getBillingAddress()->setLine2($form['deliveryAddress']['line2']->getData());
                $order->getBillingAddress()->setCity($form['deliveryAddress']['city']->getData());
                $order->getBillingAddress()->setZipcode($form['deliveryAddress']['zipcode']->getData());
                $order->getBillingAddress()->getCountry()->setName($form['deliveryAddress']['country']['name']->getData());
                $order->getBillingAddress()->setPhone($form['deliveryAddress']['phone']->getData());
                
                dd($order);
            }
            
            $entityManager->persist($order);
            $entityManager->flush();

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
