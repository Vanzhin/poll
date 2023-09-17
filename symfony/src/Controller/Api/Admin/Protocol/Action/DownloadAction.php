<?php

namespace App\Controller\Api\Admin\Protocol\Action;

use App\Controller\Api\BaseAction\NewBaseAction;
use App\Entity\Protocol\Protocol;
use App\Response\AppException;
use App\Service\FileHandler;
use App\Service\FileUploader;
use App\Service\SerializerService;
use PhpOffice\PhpWord\Shared\ZipArchive;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DownloadAction extends NewBaseAction
{
    private const DIR_PATH = 'protocols';

    public function __construct(SerializerService            $serializer,
                                private readonly FileHandler $fileHandler,
    )
    {
        parent::__construct($serializer);
    }

    public function run(Protocol ...$protocols): BinaryFileResponse
    {
        $protocolsWithFile = array_filter($protocols, fn(Protocol $protocol) => $protocol->getFile());
        if (count($protocols) > count($protocolsWithFile)) {
            throw new AppException('Не для всех выбранных протоколов сформирован файл.');
        }
        $filesToDownLoad = array_map(fn(Protocol $protocol) => $protocol->getFile(), $protocolsWithFile);

        if (count($protocolsWithFile) > 1) {
            // Create new Zip Archive.
            $zip = new ZipArchive();

            // The name of the Zip documents.
            $zipName = 'protocols_' . (new \DateTimeImmutable())->format('Ymd_his') . '.zip';

            $zip->open(static::DIR_PATH . DIRECTORY_SEPARATOR . $zipName, $zip::CREATE);
            foreach ($this->fileHandler->getFilesFromDir($filesToDownLoad, static::DIR_PATH) as $file) {
                /** @var SplFileInfo $file */
                $zip->addFromString($file->getFilename(), $file->getContents());
            };
            $zip->close();
            $out = current($this->fileHandler->getFilesFromDir([$zipName], static::DIR_PATH));
//
            return $this->fileResponse($out, true);
        }

        return $this->fileResponse(current($this->fileHandler->getFilesFromDir($filesToDownLoad, static::DIR_PATH)));
    }

    ////    пока оставлю, потом может куда прикрутить выдачу потока
//    public function run(Protocol ...$protocols): StreamedResponse
//    {
//        $protocolsWithFile = array_filter($protocols, fn(Protocol $protocol) => $protocol->getFile());
//        if (count($protocols) > count($protocolsWithFile)) {
//            throw new AppException('Не для всех выбранных протоколов сформирован файл.');
//        }
//        $filesToDownLoad = array_map(fn(Protocol $protocol) => $protocol->getFile(), $protocolsWithFile);
//
//        if (count($protocolsWithFile) > 1) {
//            // Create new Zip Archive.
//            $zip = new ZipArchive();
//
//            // The name of the Zip documents.
//            $zipName = 'protocols_' . (new \DateTimeImmutable())->format('Ymd_his') . '.zip';
//
//            $zip->open(static::DIR_PATH . DIRECTORY_SEPARATOR . $zipName, $zip::CREATE);
//            foreach ($this->fileHandler->getFilesFromDir($filesToDownLoad, static::DIR_PATH) as $file) {
//                /** @var SplFileInfo $file */
//                $zip->addFromString($file->getFilename(), $file->getContents());
//            };
//            $zip->close();
//            $out = current($this->fileHandler->getFilesFromDir([$zipName], static::DIR_PATH));
////
//            return $this->streamFileResponse($out, $this->protocolFileUploader);
//        }
//
//        return $this->streamFileResponse(current($this->fileHandler->getFilesFromDir($filesToDownLoad, static::DIR_PATH)), $this->protocolFileUploader);
//    }
}