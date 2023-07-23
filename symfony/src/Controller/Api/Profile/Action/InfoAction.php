<?php

namespace App\Controller\Api\Profile\Action;

use App\Controller\Api\BaseAction\NewBaseAction;
use App\Entity\Profile\Profile;
use Symfony\Component\HttpFoundation\JsonResponse;

class InfoAction extends NewBaseAction
{

    public function run():JsonResponse
    {
        return $this->successResponse(Profile::$choices);
    }
}