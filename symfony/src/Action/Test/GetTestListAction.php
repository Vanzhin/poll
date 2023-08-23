<?php

namespace App\Action\Test;

use App\Action\BaseAction;
use App\Repository\Interfaces\TestRepositoryInterface;
use App\Repository\Test\Mapper\TestMapper;
use App\Service\Paginator;
use App\Service\SerializerService;
use App\Service\ValidationService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class GetTestListAction extends BaseAction
{
    public function __construct(
        private readonly ValidationService       $validation,
        private readonly TestRepositoryInterface $testRepository,
        private readonly Paginator               $paginator,
        SerializerService                        $serializer
    )
    {
        parent::__construct($serializer);
    }

    public function __invoke(Request $request)
    {
        $mapper = new TestMapper();
        $data = array_merge($request->query->all() ?? [], json_decode($request->getContent(), true) ?? []);
        $errors = $this->validation->dataValidate($data, $mapper->getValidationCollectionTestList());

        if ($errors) {
            return $this->errorResponse(["errors" => $errors]);
        }
        $filter = $mapper->buildTestFilter($data);
//            return $this->successResponse(array_keys((new \ReflectionClass(Test::class))->getDefaultProperties()));
//            todo убрать $pagination выводить данные самому
//            return $this->successResponse(
//                [
//                    "test" => $this->testRepository->findAllWithFilter($filter),
//                    "pagination" => [
//                        "currentPage" => $filter->getPage(),
//                        "limit" => $filter->getLimit(),
//                        "offset" => $filter->getOffset()
//                    ]
//
//                ],
//                ['result']);

        $pagination = $this->paginator->getPagination($this->testRepository->buildFilter($filter));
        if (!$pagination->count() > 0) {
            throw new NotFoundHttpException('Такой страницы нет', 404);

        }
        return $this->successResponse(
            [
                "test" => $pagination,
                "pagination" => $this->paginator->getInfo($pagination)
            ],
            ['search']);


    }

}