<?php

namespace App\Action\Test;

use App\Action\BaseAction;
use App\Entity\Section;
use App\Entity\Test;
use App\Factory\Question\QuestionFactory;
use App\Factory\Section\SectionFactory;
use App\Service\FileHandler;
use App\Service\QuestionService;
use App\Service\SerializerService;
use App\Service\ValidationService;
use Doctrine\ORM\EntityManagerInterface;
use League\Flysystem\FilesystemException;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class UploadQuestionAction extends BaseAction
{
    public function __construct(
        private readonly ValidationService      $validation,
        private readonly FileHandler            $handler,
        private readonly QuestionService        $questionService,
        private readonly QuestionFactory        $questionFactory,
        private readonly SectionFactory         $sectionFactory,
        private readonly EntityManagerInterface $em,
        private readonly Security               $security,


        private readonly SerializerService      $serializer
    )
    {
        parent::__construct($serializer);
    }

    public function upload(Request $request): JsonResponse
    {
        try {
            $testId = $request->attributes->get('_route_params', [])['id'];
            $test = $this->em->find(Test::class, $testId);

            if (!$test) {
                return $this->errorResponse([
                    'message' => 'Ошибка получения теста',
                    'error' => sprintf('Тест с идентификатором %s не найден', $testId)
                ]);
            }
            /**
             * @var UploadedFile $archive
             */
            $uploadFile = $request->files->get('file');
            if ($this->validation->fileValidate($uploadFile, '25M')) {
                return $this->errorResponse([
                    'message' => 'Ошибка загрузки файла',
                    'error' => $this->validation->fileValidate($uploadFile, '25M')
                ]);
            }
            $preparedImages = [];
            if (!$this->handler->isTextFile($uploadFile)) {
                $this->handler->emptyDirectory();
                $this->handler->unzip($uploadFile);
                $images = $this->handler->getImagesFromDir();
                $textFiles = $this->handler->getTextFilesFromDir();

                if (count($textFiles) !== 1) {
                    return $this->errorResponse(
                        [
                            'message' => 'Ошибка при вводе данных',
                            'error' => 'Архив должен содержать 1 файл с тестами'
                        ]
                    );

                }

                $errors = [];

                foreach ($images as $image) {
                    if ($this->validation->imageValidate($image, '512k')) {
                        $errors[] = implode(", ", $this->validation->imageValidate($image, '512k'));
                    }
                    $preparedImages[$image->getFilename()] = $image;
                }

                if (count($errors) > 0) {
                    return $this->errorResponse(
                        [
                            'message' => 'Ошибка при валидации файлов',
                            'error' => $errors
                        ]
                    );

                }
                $file = current($textFiles);
            } else {
                $file = $uploadFile;
            }


            $questionData = $this->handler->getQuestion($file);

            foreach ($questionData['section'] ?? [] as $key => $title) {
                $section = $this->sectionFactory->createBuilder()->buildSection(['title' => $title, 'test' => $test], $this->em->getRepository(Section::class)->findOneBy(['title' => $title, 'test' => $test]));
                $sections[$key] = $section;
            };
            $total = [];
            $questions = [];
            foreach ($questionData['question'] ?? [] as $key => $data) {
                $data['test'] = $test->getId();

                $question = $this->questionFactory->createBuilder()->buildQuestion($data, $this->security->getUser());
                if (isset($data['section'])) {
                    $question->setSection($sections[$data['section']]);
                };

                $image = key_exists($question->getImage(), $preparedImages) ? $preparedImages[$question->getImage()] : null;
                //                todo перебираю, чтобы ключи не совпадали с айдишниками вариантов из бд
                $preparedData = [];
                if (isset($data['variant'])) {
                    foreach ($data['variant'] as $oldKey => $variant) {
                        $preparedData['variant']['a' . $oldKey] = $variant;
                    };
                }
                $response = $this->questionService->createOrUpdateQuestionIfValid($question, $preparedData, $image, $preparedImages, $preparedImages);
                if ($response['error']) {
                    $total[$key]['type'][] = implode(', ', $response['error']);
                    $total[$key]['question'] = $data;
                } else {
                    $questions[] = $response['question'];
                }

            }

            if ($total) {
                return $this->errorResponse(
                    [
                        'message' => 'Ошибка при создании вопроса',
                        'error' => $total
                    ]
                );
            } else {
                $savedQuestions = [];
                foreach ($questions as $question) {
                    $variantImages = [];
                    foreach ($question->getVariant()->toArray() as $key => $variant) {
                        if ($variant->getImage()) {
                            $variantImages[$key] = $preparedImages[$variant->getImage()];
                        }
                    }
                    $saved = $this->questionService->saveWithVariants($question, $question->getVariant()->toArray(), $question->getSubtitles()->toArray(), $preparedImages[$question->getImage()] ?? null, $variantImages);

                    $savedQuestions[] = $saved;
                };
                $response = $this->questionService->getUploadedQuestionsSummary($savedQuestions);
            }

            return $this->successResponse($response);

        } catch (\Exception|FilesystemException $e) {
            return $this->errorResponse(['error' => $e->getMessage()]);

        } finally {
            $this->handler->emptyDirectory();

        }

    }
}