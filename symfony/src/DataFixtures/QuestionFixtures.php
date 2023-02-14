<?php

namespace App\DataFixtures;

use App\Entity\Question;
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
        $this->createMany(Question::class, 1000, function (Question $question) use ($manager) {

            $question
                ->setType($this->getRandomReference(Type::class));


            switch ($question->getType()->getTitle()) {
                case 'radio':
                    $question->setTitle($this->faker->realTextBetween(30, 255) . '?')
                        ->addTicket($this->getRandomReference(Ticket::class));
                    break;
                case 'checkbox':
                    $question->setTitle($this->faker->realTextBetween(30, 255) . '?');

                    $question
                        ->addTicket($this->getRandomReference(Ticket::class));
                    break;
                case 'input_one':
                    $question->setTitle($this->faker->realTextBetween(10, 15) . static::BLANK_FIELD . $this->faker->realTextBetween(10, 15) . '?')
                        ->setAnswer([$this->faker->word()])
                        ->addTicket($this->getRandomReference(Ticket::class));
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
                        ->addTicket($this->getRandomReference(Ticket::class));
                    break;
                case 'conformity':
                    $subTitles = [];
                    for ($i = 0; $i < $this->faker->numberBetween(2, 5); $i++) {
                        $subTitles[] = $this->faker->realTextBetween(10, 50) . '?';
                    }
                    $title = $this->faker->realTextBetween(10, 50) . '?';
                    $question->setTitle($title)
                        ->setSubTitle($subTitles);
                    $question
                        ->addTicket($this->getRandomReference(Ticket::class));
                    break;
                case 'order':
                    $title = $this->faker->realTextBetween(10, 50);
                    $question->setTitle($title)
                        ->addTicket($this->getRandomReference(Ticket::class));
                    break;
                case 'checkbox_picture':
                    $variants = $this->faker->words($this->faker->numberBetween(2, 5));
                    $pictures = [];
                    foreach ($variants as $variant) {
                        $pictures[] = static::PICTURE_MARK . 'picture' . $this->faker->numberBetween(1, 10) . '.jpeg' . static::PICTURE_MARK;
                    }
                    $question->setTitle($this->faker->realTextBetween(30, 255) . '?' . static::QUESTION_SEPARATOR . implode('', $pictures))
                        ->addTicket($this->getRandomReference(Ticket::class));
                    break;
                case 'textarea':
                    $question->setTitle($this->faker->realTextBetween(10, 50) . '?')
                        ->addTicket($this->getRandomReference(Ticket::class));
                    break;
            }
        });
        $questionsWithVariants = array_filter($this->referenceRepository->getReferencesByClass()[Question::class], function ($question) {
            return in_array($question->getType()->getTitle(), ['radio', 'checkbox', 'conformity', 'order', 'checkbox_picture']);

        });
        foreach ($questionsWithVariants as $question) {
            switch ($question->getType()->getTitle()) {

                case 'radio':
                    $this->createMany(Variant::class, $this->faker->numberBetween(2, 5), function (Variant $variant) use ($manager, $question) {

                        $variant
                            ->setTitle($this->faker->word())
                            ->setWeight(1)
                            ->setQuestion($question);

                        $question->addVariant($variant);
                    });
                    $question->setAnswer([$this->faker->randomElement($question->getVariant())->getId()]);
                    break;
                case 'checkbox':
                case 'checkbox_picture':

                    $this->createMany(Variant::class, $this->faker->numberBetween(2, 6), function (Variant $variant) use ($manager, $question) {

                        $variant
                            ->setTitle($this->faker->word())
                            ->setWeight(1)
                            ->setQuestion($question);

                        $question->addVariant($variant);

                    });

                    $variants = $question->getVariant()->toArray();
                    $answers = $this->faker->randomElements($variants, $this->faker->numberBetween(1, $question->getVariant()->count()));
                    $answerIds = [];
                    foreach ($answers as $answer) {
                        $answerIds[] = $answer->getId();
                    }
                    $question->setAnswer($answerIds);

                    break;

                case 'conformity':
                    $this->createMany(Variant::class, $this->faker->numberBetween(2, 5), function (Variant $variant) use ($manager, $question) {
//
                        $variant
                            ->setTitle($this->faker->word())
                            ->setWeight(1)
                            ->setQuestion($question);

                        $question->addVariant($variant);

                    });
                    $variants = $question->getVariant()->toArray();
                    $answers = $this->faker->randomElements($variants, count($question->getSubTitle()), true);
                    $answerIds = [];
                    foreach ($answers as $answer) {
                        $answerIds[] = $answer->getId();
                    }
                    $question->setAnswer($answerIds);

                    break;

                case 'order':
                    $this->createMany(Variant::class, $this->faker->numberBetween(3, 8), function (Variant $variant) use ($manager, $question) {
//
                        $variant
                            ->setTitle($this->faker->word())
                            ->setWeight(1)
                            ->setQuestion($question);

                        $question->addVariant($variant);

                    });
                    $variants = $question->getVariant()->toArray();
                    shuffle($variants);
                    $answerIds = [];
                    foreach ($variants as $answer) {
                        $answerIds[] = $answer->getId();
                    }
                    $question->setAnswer($answerIds);

                    break;
            }

        }
    }

    public function getDependencies(): array
    {
        return [
            TypeFixtures::class,
            TicketFixtures::class,
        ];
    }
}
