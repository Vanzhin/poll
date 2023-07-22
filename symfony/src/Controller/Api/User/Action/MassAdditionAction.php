<?php

namespace App\Controller\Api\User\Action;

use App\Controller\Api\BaseAction\NewBaseAction;
use App\Factory\Profile\ProfileFactory;
use App\Factory\User\UserFactory;
use App\Response\AppException;
use App\Service\SerializerService;
use App\Service\ValidationService;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class MassAdditionAction extends NewBaseAction
{
    private static array $userAliases = [
        "Логин" => "login",
        "Пароль" => "password",
        "E-Mail" => 'email',

    ];
    private static array $profileAliases = [
        "Фамилия" => "lastName",
        "Имя" => "firstName",
        "Отчество" => "middleName",
        "Телефон" => 'phone',
        "Телефон 2" => 'phone2',
        "E-Mail 2" => 'email2',
        "Адрес" => 'address',
        "Подразделение" => 'department',
        "Должность" => 'position',
        "Контактная информация" => 'info',
        "Дополнительная информация" => 'comment'
    ];


    public function __construct(
        SerializerService                       $serializer,
        private readonly EntityManagerInterface $entityManager,
        private readonly ValidationService      $validator,
        private readonly UserFactory            $userFactory,
        private readonly ProfileFactory         $profileFactory,
        private readonly ValidationService      $validation,
        private readonly Security               $security,

    )
    {
        parent::__construct($serializer);
    }

    public function run(Request $request): JsonResponse
    {

        /**
         * @var UploadedFile $uploadFile
         */
        $uploadFile = $request->files->get('file');
        if (!$uploadFile) {
            throw new AppException('Файл не загружен');
        }
        if ($this->validation->excelFileValidate($uploadFile, '1M')) {
            return $this->errorResponse([
                'message' => 'Ошибка загрузки файла',
                'error' => $this->validation->excelFileValidate($uploadFile, '1M')
            ]);
        }
//        todo убрать все это, пока не знаю куда
        $reader = new Xlsx();
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($uploadFile->getRealPath());
        $sheet = $spreadsheet->getSheet($spreadsheet->getFirstSheetIndex());
        $data = $sheet->toArray();
        $headers = array_filter(current($data), fn($value) => !is_null($value));

        $missingHeaders = array_diff(array_flip(array_merge(self::$userAliases, self::$profileAliases)), $headers);

        if (count($missingHeaders) > 0) {
            throw new AppException(sprintf('Не верный формат таблицы. Не хватает следующих полей: %s', implode(', ', $missingHeaders)));

        }
        unset($data[0]);
        $userItems = [];
        foreach ($data as $userData) {
            if (count(array_unique($userData)) === 1 && is_null(current(array_unique($userData)))) {
                continue;
            };
            $userItem = [];
            foreach ($headers as $column => $header) {
                if (isset(self::$profileAliases[$header])) {
                    $userItem['profile'][self::$profileAliases[$header]] = $userData[$column];
                }
                if (isset(self::$userAliases[$header])) {
                    if (self::$userAliases[$header] === 'password') {
                        $userItem['confirmPassword'] = $userData[$column];
                    }
//                    todo убрать когда заменим почту на логин
                    if (self::$userAliases[$header] === 'email' && !isset($userData[$column])) {
                        $userItem[self::$userAliases[$header]] = rand(1, 1000) . $this->security->getUser()->getEmail();
                        continue;
                    }
                    $userItem[self::$userAliases[$header]] = $userData[$column];
                }
            }
            $userItems[] = $userItem;
        }
        $users = [];
        foreach ($userItems as $item) {
            $profile = null;
            if (isset($item['profile'])) {
                $profile = $this->profileFactory->createBuilder()->buildProfile($item['profile']);
                if (!empty($this->validator->validate($profile))) {
                    throw new AppException(implode(', ', $this->validator->validate($profile)));
                }
            }

            $user = $this->userFactory->createBuilder()->buildCompanyUser($item, $this->security->getUser()->getCompany(), null, $profile);
            if (!empty($this->validator->validate($user))) {
                throw new AppException(implode(', ', $this->validator->validate($user)));
            }
            $errors = [];
            if ($this->validator->userPasswordValidate($item['password'])) {
                foreach ($this->validator->userPasswordValidate($item['password']) as $error) {
                    $errors[] = $error;
                }
            }
            if ($item['password'] !== $item['confirmPassword']) {
                $errors[] = 'Пароли не совпадают.';
            }
            if ($errors) {
                throw new AppException(implode(', ', $errors));
            }
            $this->entityManager->persist($user);
            $users[] = $user;

        }
        $this->entityManager->flush();

        return $this->successResponse([], [], sprintf('Создано %d пользователей.', count($users)));
    }
}