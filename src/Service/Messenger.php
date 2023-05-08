<?php

namespace App\Service;

use App\Entity\Customer;
use App\EntityRepository\CustomerRepository;
use App\Entity\Message;
use Doctrine\ORM\EntityManagerInterface;
use HttpException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class Messenger
{
    /**
     * @var \Doctrine\ORM\EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    /**
     * @var \App\EntityRepository\CustomerRepository
     */
    private CustomerRepository $customerRepository;

    /**
     * @var \Symfony\Component\Routing\Generator\UrlGeneratorInterface
     */
    private UrlGeneratorInterface $urlGenerator;

    /**
     * @var SenderInterface[]
     */
    protected $senders = [];

    public function __construct(
        EntityManagerInterface $entityManager,
        CustomerRepository $customerRepository,
        UrlGeneratorInterface $urlGenerator
    )
    {
        $this->entityManager = $entityManager;
        $this->customerRepository = $customerRepository;
        $this->urlGenerator = $urlGenerator;

        $this->senders = [
            new EmailSender(),
            new SMSSender()
        ];
    }

    public function saveAndSend(string $requestData, string $customerCode) {
        try {
            $customer = $this->customerRepository->find($customerCode);
            if (!$customer) {
                throw new HttpException('404', sprintf('Customer not found (%s).', $customerCode));
            }

            $requestDataArray = json_decode($requestData, true);
            $message = $this->createNewMessage($requestDataArray, $customer);

            $this->notifyCustomer($message);
        } catch (\Throwable $e) {
            dd($e->getMessage());
        }

        $this->sendResponse($message);
    }

    private function createNewMessage(array $requestDataArray, Customer $customer): ?Message {
        if (!empty($requestDataArray['body'])) {
            $message = (new Message())
                ->setText($requestDataArray['body'])
                ->setType($customer->getNotificationType())
                ->setCustomer($customer);

            $this->entityManager->persist($message);
            $this->entityManager->flush();
        }

        return $message ?? null;
    }

    private function notifyCustomer(Message $message)
    {
        foreach ($this->senders as $sender) {
            if ($sender->supports($message)) {
                $sender->send($message);

                return;
            }
        }

        throw new NotFoundHttpException(
            sprintf('Notification method "%s" is not supported.', $message->type)
        );
    }

    private function sendResponse(Message $message) {
        $response = new Response('Customer notified', Response::HTTP_CREATED);

        $url = $this->urlGenerator->generate('get_customer_message', [
            'customerCode' => $message->getCustomer()->getCustomerCode(),
            'id' => $message->getId()
        ]);

        $response->headers->set('Location', $url);
        $response->send();
    }
}
