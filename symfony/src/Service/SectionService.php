<?php

namespace App\Service;

use App\Entity\Question;
use App\Entity\Section;
use App\Entity\Test;
use App\Factory\Section\SectionFactory;
use App\Repository\Question\QuestionRepository;
use Doctrine\ORM\EntityManagerInterface;

class SectionService
{

    public function __construct(private readonly EntityManagerInterface $em,
                                private readonly ValidationService      $validation,
                                private readonly SectionFactory         $sectionFactory,
                                private readonly QuestionRepository     $questionRepository,
    )
    {
    }

    public function createIfNotExist(string $title, ?Test $test = null): ?Section
    {
        $section = $this->sectionFactory->createBuilder()->buildSection(['title' => 'Без секции', 'test' => $test], $this->em->getRepository(Section::class)->findOneBy(['title' => $title, 'test' => $test]));

        $this->em->persist($section);
        $this->em->flush();

        return $section;
    }

    public function saveIfValid(Section $section): array
    {
        if (count($this->validation->validate($section)) > 0) {
            $response = [
                'message' => 'Ошибка при вводе данных',
                'error' => $this->validation->validate($section)
            ];
            $status = 422;
            return ['response' => $response, 'status' => $status];
        }
        return $this->saveResponse($section);
    }

    public function saveResponse(Section $section): array
    {
        try {
            if ($section->getId()) {
                $message = 'Секция обновлена';
            } else {
                $message = 'Секция создана';

            }
            $this->em->persist($section);
            $this->em->flush();
            $response = [
                'message' => $message,
                'sectionId' => $section->getId()
            ];
            $status = 200;
//  вопросов без секции быть не должно, если в тесте вопрос без секции, я его отправляю в дефолтную секцию
            $this->attachDefaultSectionToQuestion($section->getTest());
        } catch (\Exception $e) {
            $response = ['error' => $e->getMessage()];
            $status = 501;
        } finally {
            return ['response' => $response, 'status' => $status];
        }
    }

    public function deleteResponse(Section $section): array
    {
        try {
            if ($section->getTitle() === 'Без секции') {
                throw new \Exception('Нельзя удалить эту секцию');
            }
            //  вопросов без секции быть не должно, если в тесте вопрос без секции, я его отправляю в дефолтную секцию
            $test = $section->getTest();
            $this->em->remove($section);
            $this->em->flush();
            $this->attachDefaultSectionToQuestion($test);

            $response = [
                'message' => 'Секция удалена',
            ];
            $status = 200;
        } catch (\Exception $e) {
            $response = ['error' => $e->getMessage()];
            $status = 501;
        } finally {
            return ['response' => $response, 'status' => $status];
        }
    }

    private function attachDefaultSectionToQuestion(Test $test): void
    {
        $questions = $this->questionRepository->getQuestionWithNoSection($test);
        $defaultSection = $this->createIfNotExist('Без секции', $test);
        if (count($questions) > 0) {
            foreach ($questions as $question) {
                /**
                 * @var Question $question
                 */
                $question->setSection($defaultSection);
                $this->em->persist($question);
            }

            $this->em->flush();
        }
    }
}