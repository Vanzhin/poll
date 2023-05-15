<?php

namespace App\Action\Test;

use App\Action\BaseAction;
use App\Interfaces\TestRepositoryInterface;
use App\Mapper\TestMapper;
use App\Service\Paginator;
use App\Service\SerializerService;
use App\Service\ValidationService;
use Symfony\Component\HttpFoundation\Request;

class GetTestListAction extends BaseAction
{
    public function __construct(
        private readonly ValidationService       $validation,
        private readonly TestRepositoryInterface $testRepository,
        private readonly Paginator               $paginator,
        private readonly SerializerService       $serializer
    )
    {
        parent::__construct($serializer);
    }

    public function __invoke(Request $request)
    {
        try {
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
                throw new \Exception('нет такой страницы', 404);

            }
            return $this->successResponse(
                [
                    "test" => $pagination,
                    "pagination" => $this->paginator->getInfo($pagination)
                ],
                ['result']);


        } catch (\Exception $e) {
            return $this->errorResponse(['error' => $e->getMessage()], $e->getCode());
        }

    }

}