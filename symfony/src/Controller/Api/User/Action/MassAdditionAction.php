<?php

namespace App\Controller\Api\User\Action;

use App\Controller\Api\BaseAction\NewBaseAction;
use App\Entity\Company;
use App\Factory\Profile\ProfileFactory;
use App\Factory\User\UserFactory;
use App\Service\FileHandler;
use App\Service\RoleService;
use App\Service\SerializerService;
use App\Service\ValidationService;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class MassAdditionAction extends NewBaseAction
{
    public function __construct(
        SerializerService                       $serializer,
        private readonly EntityManagerInterface $entityManager,
        private readonly ValidationService      $validator,
        private readonly UserFactory            $userFactory,
        private readonly ProfileFactory         $profileFactory,
        private readonly RoleService            $roleService,
        private readonly FileHandler            $handler,
        private readonly ValidationService      $validation

    )
    {
        parent::__construct($serializer);
    }

    public function run(Request $request, Company $company): JsonResponse
    {
        /**
         * @var UploadedFile $uploadFile
         */
        $uploadFile = $request->files->get('file');
        if (!$uploadFile) {
            throw new \Exception('Файл не загружен');
        }
        if ($this->validation->excelFileValidate($uploadFile, '1M')) {
            return $this->errorResponse([
                'message' => 'Ошибка загрузки файла',
                'error' => $this->validation->excelFileValidate($uploadFile, '1M')
            ]);
        }

//        dd($uploadFile->getRealPath(), $uploadFile->getPath(), $uploadFile->getFilename(), $uploadFile->getPathname(), $uploadFile->getMimeType());
        $reader = new Xlsx();
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($uploadFile->getRealPath());
        $sheet = $spreadsheet->getSheet($spreadsheet->getFirstSheetIndex());
        $data = $sheet->toArray();
        $headers = array_filter(current($data), fn($value) => !is_null($value));
        unset($data[0]);
        $userItems = [];
        foreach ($data as $userData) {
            if (count(array_unique($userData)) === 1 && is_null(current(array_unique($userData)))) {
                continue;
            };
            $userItem = [];
            foreach ($headers as $column => $header) {
                $userItem[$header] = $userData[$column];
            }
            $userItems[] = $userItem;
//
        }

        return $this->successResponse($userItems);
    }
}