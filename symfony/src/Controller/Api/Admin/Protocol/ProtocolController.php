<?php

namespace App\Controller\Api\Admin\Protocol;

use App\Controller\Api\Admin\Protocol\Action\CreateAction;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ProtocolController extends AbstractController
{
    public function __construct(
        private readonly CreateAction $createAction,
    )
    {
    }

    public function run(Request $request):JsonResponse
    {
        return $this->createAction->run($request);
    }

}