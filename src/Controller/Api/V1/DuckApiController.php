<?php

namespace App\Controller\Api\V1;

use App\Entity\Example\Duck;
use App\Factory\Example\DuckFactoryAbstract;
use App\Service\SerializerService;
use App\Service\ValidatorService;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/api/v1/ducks",  name="admin_api_ducks_")
 */
class DuckApiController extends AbstractController
{
    private ObjectManager $em;
    private ValidatorService $validatorService;
    private SerializerService $serializerService;

    public function __construct
    (
        ManagerRegistry $managerRegistry,
        ValidatorService $validatorService,
        SerializerService $serializerService
    )
    {
        $this->em = $managerRegistry->getManager();
        $this->validatorService = $validatorService;
        $this->serializerService = $serializerService;
    }

    /**
     * @Route("/{id}", name="find", methods={"GET"})
     */
    public function find(int $id): JsonResponse
    {
        $duck = $this->em->getRepository(Duck::class)->findOneBy(['id' => $id]);

        if($duck === null) return new JsonResponse(['Entity not found'], 404);

        return JsonResponse::fromJsonString
        (
            $this->serializerService->getDefaultSerializer()->serialize($duck, 'json')
        );
    }

    /**
     * @Route("/", name="find_all", methods={"GET"})
     */
    public function findAll(): JsonResponse
    {
        return
            JsonResponse::fromJsonString
            (
                $this
                    ->serializerService
                    ->getDefaultSerializer()
                    ->serialize($this->em->getRepository(Duck::class)->findAll(), 'json')
            );
    }

    /**
     * @Route(name="create", methods={"POST"})
     */
    public function create(Request $request, DuckFactoryAbstract $duckFactoryAbstract, ValidatorInterface $validator): JsonResponse
    {
        $validationResult =
            $this->validatorService->validateJsonBody
            (
                $request,
                new Assert\Collection(
                    [
                        'name' => [new Assert\NotBlank(), new Assert\Type('string')],
                        'color' => [new Assert\NotBlank(), new Assert\Type('string')]
                    ]
                )
            );

        if($validationResult->hasErrors()) {
            return new JsonResponse($validationResult->getErrorMessages(), 400);
        }

        $duck = $duckFactoryAbstract->createDuck($validationResult->getValidatedData());

        $this->em->persist($duck);
        $this->em->flush();

        return new JsonResponse(['id' => $duck->getId()]);
    }
}