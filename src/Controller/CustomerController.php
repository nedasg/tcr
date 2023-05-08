<?php declare(strict_types=1);

namespace App\Controller;

use App\Entity\Customer;
use App\EntityRepository\CustomerRepository;
use App\Model\Message;
use App\Service\EmailSender;
use App\Service\Messenger;
use App\Service\SMSSender;
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
     * @Route("/customer/{customerCode}/notifications", name="customer_notifications", methods={"GET"})
     */
    public function notifyCustomer(string $customerCode, Request $request, CustomerRepository $customerRepository): Response
    {
        $requestData = json_decode($request->getContent(), true);

        /** @var Customer $customer */
        $customer = $customerRepository->find($customerCode);

        if (!$customer) {
            throw $this->createNotFoundException(
                sprintf('Customer not found (%s)', $customerCode)
            );
        }

        $message = (new Message())
            ->setBody($requestData ?? 'empty')
            ->setType($customer->getNotificationType());

        $this->messenger->send($message);

        return new Response("OK");
    }
}
