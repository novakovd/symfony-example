<?php

namespace App\Service;

use App\Type\ValidationResult;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints as Assert;
use GuzzleHttp\Utils as GuzzleUtils;
use Symfony\Component\Validator\Constraints\Composite;

class ValidatorService
{
    private ValidatorInterface $validator;
    private LoggerInterface $logger;

    public function __construct(ValidatorInterface $validator, LoggerInterface $logger)
    {
        $this->validator = $validator;
        $this->logger = $logger;
    }

    protected function getValidationMessages(ConstraintViolationListInterface $constraintViolationList): array {
        try {
            return
                array_map(function (ConstraintViolation $v) {
                    return
                        $v->getMessage() .
                        ' ' .
                        $v->getPropertyPath();
                }, $constraintViolationList->getIterator()->getArrayCopy());
        }
        catch (Exception $e) {
            $this->logger->error($e);

            return [
                'An error occurred during validation process'
            ];
        }
    }

    public function validateArray(array $data, Composite $assertCollection): ValidationResult
    {
        $dataValidatorList = $this->validator->validate($data, $assertCollection);

        if ($dataValidatorList->count() > 0) {
            return
                (new ValidationResult())
                    ->setErrorMessages($this->getValidationMessages($dataValidatorList))
                    ->setHasErrors(true);
        }

        return
            (new ValidationResult())
                ->setValidatedData($data);
    }

    public function validateJsonBody(Request $request, Composite $assertCollection = null): ValidationResult
    {
        $jsonValidationResult = $this->validateArray
        (
            [
                'requestBody' => $request->getContent(),
            ],
            new Assert\Collection([
                'requestBody' => [new Assert\NotBlank(), new Assert\Json()]
            ])
        );

        if($jsonValidationResult->hasErrors()) {
            return $jsonValidationResult;
        }

        $requestContent = GuzzleUtils::jsonDecode($request->getContent(), true);

        if(!is_null($assertCollection)) {
            return $this->validateArray($requestContent, $assertCollection);
        }

        return
            (new ValidationResult())
                ->setValidatedData($requestContent);
    }
}