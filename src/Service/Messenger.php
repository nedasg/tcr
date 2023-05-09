<?php

namespace App\Service;

use App\Entity\Customer;
use App\Entity\Message;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Messenger
{
    protected MessageManager $messageManager;
    protected MessageFormatter $messageFormatter;
    protected MessageValidator $messageValidator;

    /**
     * @var SenderInterface[]
     */
    protected array $senders = [];

    /**
     * @param \App\Service\MessageManager $messageManager
     * @param \App\Service\MessageFormatter $messageFormatter
     * @param \App\Service\MessageValidator $messageValidator
     */
    public function __construct(
        MessageManager $messageManager,
        MessageFormatter $messageFormatter,
        MessageValidator $messageValidator
    )
    {
        $this->messageManager = $messageManager;
        $this->messageFormatter = $messageFormatter;
        $this->messageValidator = $messageValidator;

        $this->senders = [
            new EmailSender(),
            new SMSSender()
        ];
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \App\Entity\Customer $customer
     *
     * @return \App\Entity\Message
     *
     * @throws \Exception
     */
    public function sendMessage(Request $request, Customer $customer): Message
    {
        $this->messageValidator->validate($request);

        $message = $this->messageFormatter
            ->getFormattedMessage($request->getContent(), $customer);

        $this->messageManager->save($message);

        $this->send($message);

        return $message;
    }

    /**
     * @param \App\Entity\Message $message
     *
     * @return void
     */
    private function send(Message $message)
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
}
