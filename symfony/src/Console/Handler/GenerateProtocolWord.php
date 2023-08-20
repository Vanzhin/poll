<?php

namespace App\Console\Handler;

use App\Console\Contract\GenerateProtocolInterface;
use App\Entity\Protocol;
use App\Response\AppException;
use PhpOffice\PhpWord\TemplateProcessor;

class GenerateProtocolWord implements GenerateProtocolInterface
{
    private TemplateProcessor $templateProcessor;
    private const TEMPLATES = [
        'eb' => 'protocols/templates/eb.docx',
        'ot' => 'protocols/templates/ot.docx'
    ];

    private string $defaultTemplate = 'eb';

    private const FILE_PATH = 'protocols/';

    public function __construct()
    {
    }


    public function generate(Protocol $protocol, string $template = null): string
    {
        if (is_null($template)) {
            $template = $this->defaultTemplate;
        }
        $this->fillProtocolTemplate($protocol, $this->getTemplateProcessor($template));

        $fileName = 'protocol_' . $protocol->getNumber() . '_' . $protocol->getCreatedAt()->format('Ymd');
        $this->save($fileName);
        return $fileName;
    }

    private function fillProtocolTemplate(Protocol $protocol, TemplateProcessor $templateProcessor): TemplateProcessor
    {
        $templateProcessor->setValue('number', $protocol->getNumber());
        $templateProcessor->setValue('date', $protocol->getOrderDate()->format('d\.m\.Y'));
        $profiles = [];
        foreach ($protocol->getGroups()->getParticipants() as $count => $user) {
            $profiles[] = [
                'count' => ++$count,
                'name' => $user->getProfile()->getLastName() . ' ' . $user->getProfile()->getFirstName() . ' ' . $user->getProfile()->getMiddleName(),
                'position' => $user->getProfile()->getPosition(),
                'company' => $user->getCompany()->getTitle(),
                'result' => 'удовл.',
                'date' => $protocol->getUpdatedAt()->format('d-m-Y'),
                'regNumber' => '1234567890'
            ];
        }

        $templateProcessor->cloneRowAndSetValues('count', $profiles);
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
        $protocol = $this->templateProcessor;
        $protocol->saveAs(self::FILE_PATH . $fileName . '.docx');
        return true;
    }
}