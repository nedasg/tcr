<?php

namespace App\Tests\Service;

use App\Service\MessageValidator;
use Exception;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

class MessageValidatorTest extends TestCase
{
    public function testValidateHappyPath() {
        $json = json_encode(['body' => 'notification text']);
        $request = new Request([], [], [], [], [], [], $json);

        $messageValidator = new MessageValidator();
        $this->assertTrue($messageValidator->validate($request));
    }

    public function testValidateFailsOnEmptyContent() {
        $request = new Request();

        $this->expectException(Exception::class);

        $messageValidator = new MessageValidator();
        $messageValidator->validate($request);
    }
}