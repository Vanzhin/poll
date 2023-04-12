<?php

namespace App\Service;

use App\Entity\Section;
use App\Entity\Test;
use Doctrine\ORM\EntityManagerInterface;

class SectionService
{

    public function __construct(private readonly EntityManagerInterface $em, private readonly ValidationService $validation)
    {
    }

    public function createIfNotExist(string $title, ?Test $test = null): ?Section
    {
        $section = $this->em->getRepository(Section::class)->findOneBy(['title' => $title, 'test'=>$test]);
        if (!$section) {
            $section = new Section();
            $section->setTitle($title)->setTest($test);

            if ($test) {
                $section->setTest($test);
            }
            $this->em->persist($section);
            $this->em->flush();
        };
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
            $this->em->remove($section);
            $this->em->flush();
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
}