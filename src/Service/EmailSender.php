<?php

namespace App\Service;

use App\Entity\Message;

class EmailSender implements SenderInterface
{

    /**
     * @param \App\Entity\Message $message
     *
     * @return bool
     */
    public function supports(Message $message): bool
    {
        return $message->type === Message::TYPE_EMAIL;
    }

    /**
     * @param Message $message
     */
    public function send(Message $message)
    {
        print "Sending email" . PHP_EOL;
    }
}
