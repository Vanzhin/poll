<?php

namespace App\DataFixtures;

use App\Entity\Question;
use App\Entity\Quiz;
use App\Entity\Type;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class QuizFixtures extends BaseFixtures implements DependentFixtureInterface
{

    function loadData(ObjectManager $manager)
    {
        $this->create(Quiz::class, function (Quiz $quiz) use ($manager) {
            $quiz
                ->setTitle('Test Quiz');
            $questions = $manager->getRepository(Question::class)->findAll();
            foreach ($questions as $question){
                $quiz->addQuestion($question);
            }
        });
        $this->manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            QuestionFixtures::class,
        ];
    }
}
