<?php

namespace App\Controller;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Psr7;
use GuzzleHttp\Psr7\Response as GuzzleResponse;
use GuzzleHttp\Psr7\Request as GuzzleRequest;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Exception\RequestException;
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
    public function index(Request $request, EntityManagerInterface $entityManager)
    {
        $order = new Order();
        $form = $this->createForm(OrderType::class, $order);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // We fulfill billingaddress with deliveryaddress if empty
            $deliveryAddress = $form['deliveryAddress']->getData();
            if (!$deliveryAddress || empty($deliveryAddress)) {
                $order->setDeliveryAddress(new Address);
                $order->getDeliveryAddress()->setClient(new Client);
                $order->getDeliveryAddress()->setCountry(new Country);
                $order->getDeliveryAddress()->getClient()->setFirstname($form['billingAddress']['client']['firstname']->getData());
                $order->getDeliveryAddress()->getClient()->setLastname($form['billingAddress']['client']['firstname']->getData());
                $order->getDeliveryAddress()->setLine1($form['billingAddress']['line1']->getData());
                $order->getDeliveryAddress()->setLine2($form['billingAddress']['line2']->getData());
                $order->getDeliveryAddress()->setCity($form['billingAddress']['city']->getData());
                $order->getDeliveryAddress()->setZipcode($form['billingAddress']['zipcode']->getData());
                $order->getDeliveryAddress()->getCountry()->setName($form['billingAddress']['country']['name']->getData());
                $order->getDeliveryAddress()->setPhone($form['billingAddress']['phone']->getData());
            }
            
            // We fulfill database
            $entityManager->persist($order);
            $entityManager->flush();

            // We create the body in an associative array
            $orderData = array(
                'order' => array(
                    // 'id' => '1',
                    'product' => $order->getProduct()->getName(),
                    'payment_method' => $order->getPaymentMethod()->getName(),
                    'status' => 'WAITING',
                    'client' => array(
                        'firstname' => $order->getBillingAddress()->getClient()->getFirstname(),
                        'lastname' => $order->getBillingAddress()->getClient()->getFirstname(),
                        'email' => $order->getUser()->getEmail()
                    ),
                    'addresses' => array(
                        'billing' => array(
                            'address_line1' => $order->getBillingAddress()->getLine1(),
                            'address_line2' => $order->getBillingAddress()->getLine2(),
                            'city' => $order->getBillingAddress()->getCity(),
                            'zipcode' => $order->getBillingAddress()->getZipcode(),
                            'country' => $order->getBillingAddress()->getCountry()->getName(),
                            'phone' => $order->getBillingAddress()->getPhone()
                        ),
                        'shipping' => array(
                            'address_line1' => $order->getDeliveryAddress()->getLine1(),
                            'address_line2' => $order->getDeliveryAddress()->getLine2(),
                            'city' => $order->getDeliveryAddress()->getCity(),
                            'zipcode' => $order->getDeliveryAddress()->getZipcode(),
                            'country' => $order->getDeliveryAddress()->getCountry()->getName(),
                            'phone' => $order->getDeliveryAddress()->getPhone()
                        )
                    )
                )
            );
        
            // We create the header of the request with the bearer
            $headers = [
                'Authorization' => 'Bearer mJxTXVXMfRzLg6ZdhUhM4F6Eutcm1ZiPk4fNmvBMxyNR4ciRsc8v0hOmlzA0vTaX',
                'Content-Type' => 'application/json',
                'Accept'     => 'application/json'
            ];
            $body = json_encode($orderData);

            // We create the guzzle client to use request methods
            $guzzleClient = new GuzzleClient(['
                base_uri' => 'https://api-commerce.simplon-roanne.com',
                'verify' => false  // Ignorer la vérification SSL
            ]);
            
            $response = $guzzleClient->request('POST', 'https://api-commerce.simplon-roanne.com/order', [
                'headers' => $headers,
                'body' => $body
            ]);
            
            $json_received = $response->getBody()->getContents(); // We receive a json;
            $array_received = json_decode($json_received, true);
            $id_product =  $array_received['order_id'];
            dd($id_product);

            // We  fulfill local database with product id
            $order->getProduct()->setApiId($id_product);

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
