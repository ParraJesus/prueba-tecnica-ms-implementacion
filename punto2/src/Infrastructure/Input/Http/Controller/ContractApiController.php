<?php

namespace App\Infrastructure\Input\Http\Controller;

use App\Application\Contract\DTO\CreateContractCommand;
use App\Application\Contract\DTO\ProjectInstallmentsQuery;
use App\Application\Contract\UseCase\CreateContractUseCase;
use App\Application\Contract\UseCase\ProjectInstallmentsUseCase;
use App\Domain\Contract\Repository\ContractRepositoryInterface;
use App\Infrastructure\Input\Http\DTO\CreateContractRequest;
use App\Infrastructure\Input\Http\DTO\ProjectInstallmentsRequest;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

#[Route('/api/contracts')]
class ContractApiController extends AbstractController
{
    public function __construct(
        private CreateContractUseCase $createContractUseCase,
        private ProjectInstallmentsUseCase $projectInstallmentsUseCase,
        private ContractRepositoryInterface $contractRepository
    ) {}

    #[Route('', name: 'create_contract', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        // Obtener los datos directamente del JSON en el cuerpo de la solicitud
        $data = json_decode($request->getContent(), true);

        // Validar si los campos existen en el JSON
        if (!isset($data['number'], $data['date'], $data['totalAmount'], $data['paymentMethod'], $data['months'])) {
            return new JsonResponse(['error' => 'Missing required fields'], 400);
        }

        // Crear el comando desde los datos recibidos
        $command = new CreateContractCommand(
            $data['number'],
            $data['date'],  // La fecha es un string, lo vamos a convertir a DateTime en el dominio
            $data['totalAmount'],
            $data['paymentMethod'],
            $data['months']
        );

        // Pasar el comando al UseCase
        $contract = $this->createContractUseCase->handle($command);

        // Retornar una respuesta con el ID del contrato
        return new JsonResponse([
            'contractId' => $contract->id()->value()
        ], 201);
    }

    #[Route('/installments', name: 'project_installments', methods: ['POST'])]
    public function projectInstallments(Request $request): JsonResponse
    {
        $dto = ProjectInstallmentsRequest::fromRequest($request);
        $query = ContractHttpMapper::toQuery($dto);

        $installments = $this->projectInstallmentsUseCase->handle($query);

        $data = array_map(fn($i) => [
            'dueDate' => $i->dueDate()->format('Y-m-d'),
            'amount' => $i->amount()
        ], $installments);

        return new JsonResponse($data);
    }

    #[Route('', name: 'contracts_list', methods: ['GET'])]
    public function list(): JsonResponse
    {
        $contracts = $this->contractRepository->findAll();

        $data = array_map(function ($contract) {
            return [
                'id' => $contract->id()->value(),
                'number' => $contract->number(),
                'date' => $contract->date()->format('Y-m-d'),
                'totalAmount' => $contract->totalAmount(),
                'paymentMethod' => $contract->paymentMethod(),
                'installments' => array_map(function ($i) {
                    return [
                        'dueDate' => $i->dueDate()->format('Y-m-d'),
                        'amount' => $i->amount(),
                    ];
                }, $contract->installments())
            ];
        }, $contracts);

        return new JsonResponse($data);
    }

    #[Route('/{id}', name: 'contracts_show', methods: ['GET'])]
    public function show(string $id): JsonResponse
    {
        $contract = $this->contractRepository->findById(
            new \App\Domain\Contract\ValueObject\ContractId($id)
        );

        if (!$contract) {
            return new JsonResponse(['error' => 'Contract not found'], 404);
        }

        $data = [
            'id' => $contract->id()->value(),
            'number' => $contract->number(),
            'date' => $contract->date()->format('Y-m-d'),
            'totalAmount' => $contract->totalAmount(),
            'paymentMethod' => $contract->paymentMethod(),
            'installments' => array_map(function ($i) {
                return [
                    'dueDate' => $i->dueDate()->format('Y-m-d'),
                    'amount' => $i->amount(),
                ];
            }, $contract->installments())
        ];

        return new JsonResponse($data);
    }
}
