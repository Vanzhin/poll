<?php

namespace App\Console\Handler;

use App\Console\Contract\GenerateProtocolInterface;
use App\Entity\Protocol\Protocol;
use App\Entity\Result;
use App\Entity\User\User;
use App\Response\AppException;
use PhpOffice\PhpWord\TemplateProcessor;

class GenerateProtocolWord implements GenerateProtocolInterface
{
    private TemplateProcessor $templateProcessor;
    private const TEMPLATES = [
        'elektro_bezopasnost' => 'protocols/templates/elektro_bezopasnost.docx',
        'elektro_ustanovki' => 'protocols/templates/elektro_ustanovki.docx'
    ];

    private string $defaultTemplate = 'elektro_bezopasnost';

    private const FILE_PATH = 'protocols/';

    public function __construct()
    {
    }


    public function generate(Protocol $protocol): array
    {
        $filenames = [];
        $users = $protocol->getGroups()->getParticipants();
        if ($protocol->getSettings()->isIgnoreFailedUsers()) {
            //        фильтруем, если нужны только пользователи с положительными результатами
            $filteredUsers = $users
                ->filter(fn(User $user) => ($user->getResults()->filter(fn(Result $result) => ($result->getTest() === $protocol->getTest() && $result->isPass()))->count() > 0));
        } else {
            //        фильтруем пользователей, у которых есть результаты по тесту
            $filteredUsers = $users->filter(fn(User $user) => ($user->getResults()->filter(fn(Result $result) => ($result->getTest() === $protocol->getTest()))->count() > 0));

        }
        if ($protocol->getSettings()->isForEach()) {
            foreach ($filteredUsers as $user) {

                $this->fillProtocolTemplate($protocol, $this->getTemplateProcessor($protocol->getSettings()->getTemplate()), $user);
                $fileName = 'protocol_' . $protocol->getNumber() . '_' . mb_strtolower($this->translate($user->getProfile()->getLastName())) . '_' . $protocol->getCreatedAt()->format('Ymd');
                $fileName .= '.docx';
                $this->save($fileName);
                $filenames[] = $fileName;
            }
        } else {
            $this->fillProtocolTemplate($protocol, $this->getTemplateProcessor($protocol->getSettings()->getTemplate()));
            $fileName = 'protocol_' . $protocol->getNumber() . '_' . $protocol->getCreatedAt()->format('Ymd');
            $fileName .= '.docx';
            $this->save($fileName);
            $filenames[] = $fileName;
        }

        return $filenames;
    }

    private function fillProtocolTemplate(Protocol $protocol, TemplateProcessor $templateProcessor, User $user = null): TemplateProcessor
    {
//      определяем постоянные шаблона, для каждого будут свои
        $templateProcessor->setValues([
            'number' => $protocol->getNumber(),
            'date' => $protocol->getOrderDate()->format('d\.m\.Y'),
            'company' => $protocol->getGroups()->getCompany()->getTitle(),
            'reason' => $protocol->getCheckReason(),
            'head_position' => $protocol->getCommission()->getHead()->getProfile()->getPosition(),
            'head_name' => $protocol->getCommission()->getHead()->getProfile()->getShortName(),
        ]);

//        формируем данные по пользователю или пользователям
        $userData = [];
        $filteredUser = $protocol->getGroups()->getParticipants()->filter(fn(User $item) => (!$user || $item === $user));
        foreach ($filteredUser as $user) {
            $result = $user->getResults()->filter(fn(Result $result) => ($result->getTest() === $protocol->getTest()))->first()->isPass();
            $userData[] = [
                'user_id' => '',
                'user_signature' => '',
                'user_name' => $user->getProfile()->getShortName(),
                'company' => $protocol->getGroups()->getCompany()->getTitle(),
                'user_position' => $user->getProfile()->getPosition(),
                'result' => $result ? 'Удовлетворительно' : 'Не удовлетворительно'
            ];
        }
        $templateProcessor->cloneRowAndSetValues('user_id', $userData);

        $participantData = [];
        foreach ($protocol->getCommission()->getParticipant() as $participant) {
            $participantData[] = [
                'id' => '',
                'participant_position' => $participant->getProfile()->getPosition(),
                'participant_name' => $participant->getProfile()->getShortName()
            ];
        }
        $templateProcessor->cloneRowAndSetValues('participant_position', $participantData);
        $templateProcessor->cloneRowAndSetValues('id', $participantData);
        $templateProcessor->cloneRowAndSetValues('user_signature', $userData);

        return $templateProcessor;
    }

    private function getTemplateProcessor(string $template = ''): TemplateProcessor
    {
        try {
            return $this->templateProcessor = new TemplateProcessor(self::TEMPLATES[$template] ?? 'not_found');

        } catch (\Exception $exception) {
            throw new AppException('Шаблон для протокола не найден.');
        }

    }

    public function save(string $fileName): bool
    {
        try {
            $protocol = $this->templateProcessor;
            $protocol->saveAs(self::FILE_PATH . $fileName);
        } catch (\Exception|\Error $e) {
//            надо будет ошибку кидать в логгер, а эту ошибку показывать клиенту
            throw new AppException(sprintf('Не удалось сохранить файл \'%s\'.', $fileName));
        }
        return true;
    }

    private function translate(string $value): string
    {
        $rus = [
            'А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ё', 'Ж', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т', 'У', 'Ф', 'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Ъ', 'Ы', 'Ь', 'Э', 'Ю', 'Я', 'а', 'б', 'в', 'г', 'д', 'е', 'ё', 'ж', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ч', 'ш', 'щ', 'ъ', 'ы', 'ь', 'э', 'ю', 'я'
        ];
        $lat = [
            'A', 'B', 'V', 'G', 'D', 'E', 'E', 'ZH', 'Z', 'I', 'Y', 'K', 'L', 'M', 'N', 'O', 'P', 'R', 'S', 'T', 'U', 'F', 'H', 'C', 'Ch', 'Sh', 'Sch', 'Y', 'Y', 'Y', 'Ye', 'Yu', 'Ya', 'a', 'b', 'v', 'g', 'd', 'e', 'e', 'zh', 'z', 'i', 'y', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f', 'h', 'c', 'ch', 'sh', 'sch', '', 'y', '', 'ye', 'yu', 'ya'
        ];
        return str_replace($rus, $lat, $value);
    }

}