<?php

namespace App\Service;

use App\Entity\Message;

interface SenderInterface
{

    /**
     * @param \App\Entity\Message $message
     *
     * @return bool
     */
    public function supports(Message $message): bool;

    /**
     * @param \App\Entity\Message $message
     *
     * @return mixed
     */
    public function send(Message $message);
}
