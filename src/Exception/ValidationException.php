<?php

namespace App\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class ValidationException extends HttpException
{
    /** @var ConstraintViolationListInterface */
    protected $errors;

    public function __construct(ConstraintViolationListInterface $errors)
    {
        parent::__construct(400, 'invalid_entity');

        $this->errors = $errors;
    }

    public function getErrors(): array
    {
        $errors = [];
        foreach ($this->errors as $e) {
            $errors[] = $e->getMessage();
        }
        return $errors;
    }
}