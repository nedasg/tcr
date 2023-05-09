<?php

namespace App\Service;

use App\Entity\Customer;
use App\Entity\Message;

class MessageFormatter
{
    public MessageValidator $messageValidator;

    /**
     * @param \App\Service\MessageValidator $messageValidator
     */
    public function __construct(MessageValidator $messageValidator)
    {
        $this->messageValidator = $messageValidator;
    }

    /**
     * @param string $requestData
     * @param \App\Entity\Customer $customer
     *
     * @return \App\Entity\Message
     */
    public function getFormattedMessage(string $requestData, Customer $customer): Message
    {
        $requestDataArray = json_decode($requestData, true);

        return (new Message())
            ->setCustomer($customer)
            ->setText($requestDataArray['body'])
            ->setType($customer->getNotificationType());
    }
}