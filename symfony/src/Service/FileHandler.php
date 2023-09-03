<?php

namespace App\Service;

use FilesystemIterator;
use RecursiveIteratorIterator;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\Iterator\RecursiveDirectoryIterator;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use ZipArchive;

class FileHandler
{
    public function __construct(private readonly string $tempDir)
    {
    }

    public function getQuestion(File $file): array
    {
        $response = [];
        $encoding = $this->detectEncoding($file);
        static $section = null;
        static $string = 'question';
        static $questionKey = 0;
        static $variantKey = 0;
        $sectionList = [];
        $handle = fopen($file->getPathname(), "r");
        if ($handle) {
            while (($line = fgets($handle)) !== false) {
                // process the line read.

                if (strlen(trim($line)) > 0) {
                    if ($encoding !== 'utf8') {
                        $line = mb_convert_encoding($line, 'utf8', $encoding);
                    }
                    $lower = trim(mb_strtolower($line));
                    if (str_starts_with($lower, 'секция')) {
                        $t = str_replace(['секция', 'Секция', 'СЕКЦИЯ', '«', '»', '"'], '', $line);
                        $section = trim(preg_replace("/^([(|.]?([[:alnum:]])+(?)[)|.]+)|((([.:;]|[[:space:]])*)$)/iu", "", $t));
                        $sectionList[] = $section;
                        continue;
                    }
                    if ($string === 'question') {
                        if (preg_match("/@@(\d+).([[:alpha:]]+)/m", $line, $matches)) {
                            $line = str_replace($matches[0], '', $line);
                            $response['question'][$questionKey]['image'] = str_replace('@@', '', $matches[0]);
                        };
                        $response['question'][$questionKey]['title'] = trim(preg_replace("/^[(|.]?([[:alnum:]])+(?)[).]+/iu", "", $line));
                        if ($section) {
                            $response['question'][$questionKey]['section'] = array_search($section, $sectionList);

                        }
                        $string = 'variant';
                    } else {
                        //
                        if (str_starts_with($lower, '*')) {
                            $response['question'][$questionKey]['variant'][$variantKey]['correct'] = 1;
                            $line = str_replace('*', '', $line);
                        }

                        if (str_starts_with($lower, '#')) {
                            if (preg_match("/^(#+\d+)/m", $line, $matches) > 0) {
                                $correct = (trim($matches[0], '#'));
                                $response['question'][$questionKey]['variant'][$variantKey]['correct'] = ($correct - 1);

                            };
                        }

                        if (preg_match("/@@(\d+).([[:alpha:]]+)/m", $line, $matches)) {
                            $line = str_replace($matches[0], '', $line);
                            $response['question'][$questionKey]['variant'][$variantKey]['image'] = str_replace('@@', '', $matches[0]);
                        };

                        $response['question'][$questionKey]['variant'][$variantKey]['title'] = trim(preg_replace("/(^[*).#]+\d?)|((([.:;]|[[:space:]])*)$)/iu", "", $line));
                        $variantKey++;

                    }

                } else {
                    $string = 'question';
                    $questionKey++;
                    $variantKey = 0;

                }
            }

            fclose($handle);
        }
        foreach ($response['question'] as $key => $question) {
            $correctCount = [];
            if (key_exists('variant', $question)) {

                foreach ($question['variant'] as $variant) {
                    if (isset($variant['correct']) && $variant['correct'] >= 0) {
                        $correctCount[] = $variant['correct'];
                    }
                }
                if (count($correctCount) === 1 && count($question['variant']) > count($correctCount)) {
                    $question['type'] = 'radio';
                    $response['question'][$key] = $question;

                    continue;
                }
                if (count($correctCount) > 1 && count($question['variant']) >= count($correctCount) && count(array_unique($correctCount)) === 1) {
                    $question['type'] = 'checkbox';
                    $response['question'][$key] = $question;
                    continue;

                }
                if (count($correctCount) === 1 && count($question['variant']) === count($correctCount)) {
                    $question['type'] = 'input_one';
                    $question['answer'] = $question['variant'][0]['title'];
                    unset($question['variant']);

                    $response['question'][$key] = $question;
                    continue;

                }
                if (count($question['variant']) > 1 && count($correctCount) === count($question['variant']) && count(array_unique($correctCount)) == count($correctCount)) {

                    $question['type'] = 'order';

                    $response['question'][$key] = $question;
                    continue;

                }
            }
        }
        $response['section'] = $sectionList;
        return $response;
    }


    public function emptyDirectory(string $dirPath = null): void
    {
        $dirPath = $dirPath ?? $this->tempDir;
        if (is_dir($dirPath)) {
            $filePaths = glob($dirPath . '*');
            foreach ($filePaths as $filePath) {
                if (is_dir($filePath)) {
                    $this->removeDir($filePath);

                } else {
                    unlink($filePath);
                }
            }
        } else {
            throw new \Exception(sprintf('Директории %s не найдено', $dirPath), 422);
        }

    }

    private function removeDir(string $dirPath): void
    {
        $it = new RecursiveDirectoryIterator($dirPath, FilesystemIterator::SKIP_DOTS);
        $files = new RecursiveIteratorIterator($it,
            RecursiveIteratorIterator::CHILD_FIRST);
        foreach ($files as $file) {
            if ($file->isDir()) {
                rmdir($file->getRealPath());
            } else {
                unlink($file->getRealPath());
            }
        }
        rmdir($dirPath);
    }

    public function unzip(UploadedFile $file, string $dirPath = null): void
    {
        $dirPath = $dirPath ?? $this->tempDir;

        if ($file->guessExtension() !== 'zip') {
            throw new \Exception(sprintf('Не допустимый формат файла %s', $file->getFilename()), 422);

        }
        try {
            $zip = new ZipArchive();
            if ($zip->open($file->getRealPath(), ZipArchive::CREATE)) {
                $zip->extractTo($dirPath);
                $zip->close();
            }
        } catch (\Exception $exception) {
            throw new \Exception(sprintf('Не удалось разархивировать файл %s', $file->getFilename()), 422);

        }
    }

    public function getImagesFromDir(string $dirPath = null): array
    {
        $dirPath = $dirPath ?? $this->tempDir;

        $images = [];
        $finder = new Finder();
        $finder->files()->in($dirPath);
        foreach ($finder as $imageFile) {
            if (str_starts_with(mime_content_type($imageFile->getRealPath()), 'image')) {
                $image = new File($imageFile->getRealPath(), $imageFile->getFilename());
                $images[] = $image;
            }

        }
        return $images;

    }

    public function isTextFile(File $file): bool
    {
        if (!str_starts_with(mime_content_type($file->getRealPath()), 'text/plain')) {
            return false;
        }
        return true;
    }

    public function getTextFilesFromDir(string $dirPath = null): array
    {
        $dirPath = $dirPath ?? $this->tempDir;

        $files = [];
        $finder = new Finder();
        $finder->files()->in($dirPath);
        foreach ($finder as $imageFile) {
            if (str_starts_with(mime_content_type($imageFile->getRealPath()), 'text/plain')) {
                $image = new File($imageFile->getRealPath(), $imageFile->getFilename());
                $files[] = $image;
            }

        }
        return $files;
    }

    private function detectEncoding(File $file, array $encoding = ['utf8', 'windows-1251']): string
    {
        return mb_detect_encoding($file->getContent(), $encoding);
    }

    public function getFilesList(string $dirPath, string $extension): array
    {
        $filesList = [];
        $finder = new Finder();
        $finder->files()->in($dirPath);
        foreach ($finder as $file) {
            if (str_ends_with($file->getRealPath(), '.' . $extension) && str_starts_with(mime_content_type($file->getRealPath()), 'application/octet-stream')) {
                $filesList[] = $file->getFilename();
            }
        }
        return $filesList;
    }

    public function getFilesFromDir(array $fileNames, string $dirPath): array
    {
        $files = [];
        $finder = new Finder();
        $finder->files()->in($dirPath)
            ->ignoreDotFiles(true)
            ->filter(fn(SplFileInfo $fileInfo) => in_array($fileInfo->getFilename(), $fileNames));
        foreach ($finder as $file) {
            $files[] = $file;
        }
        return $files;
    }

}