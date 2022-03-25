<?php

namespace App\Type;

use Symfony\Component\Validator\ConstraintViolationListInterface;

class ValidationResult
{
    private array $errorMessages = [];
    private bool $hasErrors = false;
    private array $validatedData = [];

    public function getErrorMessages(): array
    {
        return $this->errorMessages;
    }

    public function setErrorMessages(array $errorMessages): self
    {
        $this->errorMessages = $errorMessages;

        return $this;
    }

    public function hasErrors(): bool
    {
        return $this->hasErrors;
    }

    public function setHasErrors(bool $hasErrors): self
    {
        $this->hasErrors = $hasErrors;

        return $this;
    }

    public function getValidatedData(): array
    {
        return $this->validatedData;
    }

    public function setValidatedData(array $validatedData): self
    {
        $this->validatedData = $validatedData;

        return $this;
    }
}