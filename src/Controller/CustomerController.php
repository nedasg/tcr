<?php declare(strict_types=1);

namespace App\Controller;

use App\Entity\Customer;
use App\Entity\Notification;
use App\EntityRepository\CustomerRepository;
use App\EntityRepository\NotificationRepository;
use App\Model\Message;
use App\Service\Messenger;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CustomerController extends AbstractController
{
    private Messenger $messenger;

    /**
     * @param \App\Service\Messenger $messenger
     */
    public function __construct(Messenger $messenger) {
        $this->messenger = $messenger;
    }

    /**
     * @Route("/customers/{customerCode}/messages", methods={"POST"})
     */
    public function notifyCustomer(string $customerCode, Request $request)
    {
        $this->messenger->saveAndSend($request->getContent(), $customerCode);
    }

    /**
     * @Route("/customers/{customerCode}/messages/{id}", name="get_customer_message", methods={"GET"})
     */
    public function getCustomerMessage() {}
}
