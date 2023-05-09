<?php

namespace App\Service;

use App\Entity\Message;

class SMSSender implements SenderInterface
{

    /**
     * @param \App\Entity\Message $message
     *
     * @return bool
     */
    public function supports(Message $message): bool
    {
        return $message->type === Message::TYPE_SMS;
    }

    /**
     * @param Message $message
     */
    public function send(Message $message)
    {
        print "Sending SMS" . PHP_EOL;
    }
}
