<?php

namespace App\Controller\Api\Admin\Company\Action;

use App\Action\BaseAction;
use App\Repository\Company\Mapper\CompanyFilterMapper;
use App\Service\SerializerService;
use App\Service\ValidationService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ListAction extends BaseAction
{
    public function __construct(
        SerializerService                  $serializer,
        private readonly ValidationService $validation,
    )
    {
        parent::__construct($serializer);
    }

    public function run(Request $request): JsonResponse
    {
        $mapper = new CompanyFilterMapper();
//       это еще пригодится
//        $data = array_merge($request->query->all() ?? [], json_decode($request->getContent(), true) ?? []);
        $data = json_decode($request->getContent(), true);

        $errors = $this->validation->dataValidate($data, $mapper->getValidationCollection());

        if ($errors) {
            throw new \Exception(implode(', ', $errors));
        }

        return $this->successResponse(['test' => 'ok'], ['admin_user']);
    }
}