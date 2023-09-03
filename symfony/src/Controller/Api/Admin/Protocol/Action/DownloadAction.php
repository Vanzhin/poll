<?php

namespace App\Controller\Api\Admin\Protocol\Action;

use App\Controller\Api\BaseAction\NewBaseAction;
use App\Entity\Protocol\Protocol;
use App\Service\FileHandler;
use App\Service\FileUploader;
use App\Service\SerializerService;
use PhpOffice\PhpWord\Shared\ZipArchive;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DownloadAction extends NewBaseAction
{
    private const DIR_PATH = 'protocols';

    public function __construct(SerializerService             $serializer,
                                private readonly FileHandler  $fileHandler,
                                private readonly FileUploader $protocolFileUploader,

    )
    {
        parent::__construct($serializer);
    }

    public function run(Protocol $protocol, Request $request): StreamedResponse
    {
        $data = array_merge($request->query->all() ?? [], json_decode($request->getContent(), true) ?? []);
        if (!empty($data['list']) && is_array($data['list'])) {
            $protocolsForDownload = array_filter($protocol->getFiles(), fn(string $file) => in_array($file, $data['list']));
        } else {
            $protocolsForDownload = $protocol->getFiles();
        }
        if (count($protocolsForDownload) > 1) {
            // Create new Zip Archive.
            $zip = new ZipArchive();

            // The name of the Zip documents.
            $zipName = 'protocol_' . $protocol->getNumber() . '_' . $protocol->getCreatedAt()->format('Ymd') . '.zip';

            $zip->open(static::DIR_PATH . DIRECTORY_SEPARATOR . $zipName, $zip::CREATE);
            foreach ($this->fileHandler->getFilesFromDir($protocolsForDownload, static::DIR_PATH) as $file) {
                /** @var SplFileInfo $file */
                $zip->addFromString($file->getFilename(), $file->getContents());
            };
            $zip->close();

            $out = current($this->fileHandler->getFilesFromDir([$zipName], static::DIR_PATH));
            return $this->streamFileResponse($out, $this->protocolFileUploader);
        }
        return $this->streamFileResponse(current($this->fileHandler->getFilesFromDir($protocolsForDownload, static::DIR_PATH)), $this->protocolFileUploader);
    }
}