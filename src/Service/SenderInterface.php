<?php

namespace App\Service;

use App\Entity\Message;

interface SenderInterface
{
    public function supports(Message $message): bool;

    public function send(Message $message);
}
