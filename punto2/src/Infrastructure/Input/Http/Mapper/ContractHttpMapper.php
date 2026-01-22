<?php

namespace App\Infrastructure\Input\Http\Mapper;

use App\Application\Contract\DTO\CreateContractCommand;
use App\Application\Contract\DTO\ProjectInstallmentsQuery;
use App\Infrastructure\Input\Http\DTO\CreateContractRequest;
use App\Infrastructure\Input\Http\DTO\ProjectInstallmentsRequest;

class ContractHttpMapper
{
    public static function toCommand(CreateContractRequest $request): CreateContractCommand
    {
        return new CreateContractCommand(
            $request->number,
            new \DateTime($request->date),
            $request->totalAmount,
            $request->paymentMethod
        );
    }

    public static function toQuery(ProjectInstallmentsRequest $request): ProjectInstallmentsQuery
    {
        return new ProjectInstallmentsQuery(
            $request->contractId,
            $request->months
        );
    }
}
