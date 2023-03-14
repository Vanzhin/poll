<?php

namespace App\DataFixtures;

use App\Entity\Question;
use App\Entity\Test;
use App\Entity\Ticket;
use App\Entity\Type;
use App\Entity\Variant;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class QuestionFixtures extends BaseFixtures implements DependentFixtureInterface
{
    const BLANK_FIELD = '[ ... ]';
    const QUESTION_SEPARATOR = '[***]';
    const PICTURE_MARK = '[pic]';

    function loadData(ObjectManager $manager)
    {
        $this->createMany(Question::class, 500, function (Question $question) use ($manager) {

            $question
                ->setType($this->getRandomReference(Type::class))
                ->addTicket($this->getRandomReference(Ticket::class));


            switch ($question->getType()->getTitle()) {
                case 'radio':
                case 'checkbox':
                case 'input_one':
                    $question->setTitle($this->faker->realTextBetween(30, 255) . '?')
                        ->setTest($this->getRandomReference(Test::class));
                    break;
                case 'input_many':
                case 'blank':
                    $answers = $this->faker->words($this->faker->numberBetween(2, 5));
                    $title = $this->faker->realTextBetween(10, 50) . '?' . static::QUESTION_SEPARATOR;
                    $inputs = [];
                    foreach ($answers as $answer) {
                        $inputs[] = $this->faker->realTextBetween(10, 35) . '?' . static::BLANK_FIELD;
                    }

                    $question->setTitle($title . implode('', $inputs))
                        ->setAnswer($answers)
                        ->setTest($this->getRandomReference(Test::class));
                    break;
                case 'conformity':
//                    $subTitles = [];
//                    for ($i = 0; $i < $this->faker->numberBetween(2, 5); $i++) {
//                        $subTitles[] = $this->faker->realTextBetween(10, 50) . '?';
//                    }
                    $title = $this->faker->realTextBetween(10, 50) . '?';
                    $question->setTitle($title)
                        ->setTest($this->getRandomReference(Test::class));
                    break;
                case 'order':
                    $title = $this->faker->realTextBetween(10, 50);
                    $question->setTitle($title)
                        ->setTest($this->getRandomReference(Test::class));
                    break;
                case 'checkbox_picture':
                    $variants = $this->faker->words($this->faker->numberBetween(2, 5));
                    $pictures = [];
                    foreach ($variants as $variant) {
                        $pictures[] = static::PICTURE_MARK . 'picture' . $this->faker->numberBetween(1, 10) . '.jpeg' . static::PICTURE_MARK;
                    }
                    $question->setTitle($this->faker->realTextBetween(30, 255) . '?' . static::QUESTION_SEPARATOR . implode('', $pictures))
                        ->setTest($this->getRandomReference(Test::class));
                    break;
                case 'textarea':
                    $question->setTitle($this->faker->realTextBetween(10, 50) . '?')
                        ->setTest($this->getRandomReference(Test::class));
                    break;
            }
        });
        $questionsWithSubQuestions = array_filter($this->referenceRepository->getReferencesByClass()[Question::class], function ($question) {
            return in_array($question->getType()->getTitle(), ['conformity']);

        });
        foreach ($questionsWithSubQuestions as $question) {
            $this->createMany(Question::class, $this->faker->numberBetween(2, 5), function (Question $subQuestion) use ($manager, $question) {

                $subQuestion->setTitle($this->faker->realTextBetween(10, 100) . '?')
                    ->setTest($question->getTest())
                    ->setType($manager->getRepository(Type::class)->findOneBy(['title' => 'input_one']))
                    ->setParent($question);
            });
        }
    }

    public function getDependencies(): array
    {
        return [
            TypeFixtures::class,
            TestFixtures::class,
        ];
    }
}
