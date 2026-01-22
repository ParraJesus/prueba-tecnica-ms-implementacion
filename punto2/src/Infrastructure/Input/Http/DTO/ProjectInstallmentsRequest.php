<?php

namespace App\Infrastructure\Input\Http\DTO;

use Symfony\Component\HttpFoundation\Request;

class ProjectInstallmentsRequest
{
    public function __construct(
        public string $contractId,
        public int $months
    ) {}

    public static function fromRequest(Request $request): self
    {
        $data = json_decode($request->getContent(), true);

        return new self(
            $data['contractId'],
            $data['months']
        );
    }
}
