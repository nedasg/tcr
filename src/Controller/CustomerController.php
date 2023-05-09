<?php declare(strict_types=1);

namespace App\Controller;

use App\EntityRepository\CustomerRepository;
use App\Entity\Message;
use App\Service\Messenger;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class CustomerController extends AbstractController
{
    private Messenger $messenger;

    /**
     * @param \App\Service\Messenger $messenger
     */
    public function __construct(Messenger $messenger)
    {
        $this->messenger = $messenger;
    }

    /**
     * @param string $customerCode
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \App\EntityRepository\CustomerRepository $customerRepository
     *
     * @return void
     *
     * @throws \Exception
     *
     * @Route("/customers/{customerCode}/messages", methods={"POST"})
     */
    public function notifyCustomer(string $customerCode, Request $request, CustomerRepository $customerRepository)
    {
        $customer = $customerRepository->find($customerCode);

        if (!$customer) {
            throw new NotFoundHttpException(
                sprintf('Customer not found (%s).', $customerCode)
            );
        }

        $message = $this->messenger->sendMessage($request, $customer);

        $this->sendCustomerNotifiedResponse($message);
    }

    /**
     * @Route("/customers/{customerCode}/messages/{id}", name="get_customer_message", methods={"GET"})
     */
    public function getCustomerMessage() {}

    /**
     * @param \App\Entity\Message $message
     *
     * @return void
     */
    private function sendCustomerNotifiedResponse(Message $message)
    {
        $response = new Response('Customer notified', Response::HTTP_CREATED);

        $url = $this->generateUrl('get_customer_message', [
            'customerCode' => $message->getCustomer()->getCustomerCode(),
            'id' => $message->getId()
        ]);

        $response->headers->set('Location', $url);
        $response->send();
    }
}
