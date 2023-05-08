<?php

namespace App\Service;

use App\Entity\Message;

class EmailSender implements SenderInterface
{
    /**
     * {@inheritDoc}
     */
    public function supports(Message $message): bool
    {
        return $message->type === Message::TYPE_EMAIL;
    }

    /**
     * {@inheritDoc}
     *
     * @param Message $message
     */
    public function send(Message $message)
    {
        print "Sending email" . PHP_EOL;
    }
}
