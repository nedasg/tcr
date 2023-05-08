<?php

namespace App\Service;

use App\Model\Message;

class Messenger
{
    /**
     * @var SenderInterface[]
     */
    protected $senders = [];

    public function __construct()
    {
        $this->senders = [
            new EmailSender(),
            new SMSSender()
        ];
    }

    /**
     * {@inheritDoc}
     *
     */
    public function send(Message $message)
    {
        foreach ($this->senders as $sender) {
            if ($sender->supports($message)) {
                $sender->send($message);
            }
        }
    }
}
