<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\Json;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validation;

class MessageValidator
{

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return void
     */
    public function validate(Request $request)
    {
        $validator = Validation::createValidator();

        $violations = $validator->validate($request->getContent(),
            [
                new Json(),
                new Callback(function ($jsonString, ExecutionContextInterface $context) {
                    $data = json_decode($jsonString, true);

                    if (!isset($data['body'])) {
                        $context->buildViolation('Wrong json structure: the "body" key is missing.')
                            ->addViolation();
                    } elseif (empty($data['body'])) {
                        $context->buildViolation('Request "body" is empty. Message cannot be blank.')
                            ->addViolation();
                    }
                })
            ]
        );

        if (count($violations) > 0) {
            throw new ValidationFailedException('', $violations);
        }
    }
}