<?php

namespace App\DataFixtures;

use App\Entity\Question;
use App\Entity\Variant;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class VariantFixtures extends BaseFixtures implements DependentFixtureInterface
{

    function loadData(ObjectManager $manager)
    {
        $this->createMany(Variant::class, 3000, function (Variant $variant) use ($manager) {

            $variant
                ->setTitle($this->faker->word())
                ->setWeight(1)
                ->setQuestion($this->getRandomQuestionByTypes(['radio', 'checkbox', 'conformity', 'order', 'checkbox_picture']));

//            switch ($question->getType()->getTitle()) {
//                case 'radio':
//                case 'checkbox':
//                case 'conformity':
//                case 'order':
//                case 'checkbox_picture':
//
//                    break;
//                case 'input_one':
//                case 'input_many':
//                case 'blank':
//                case 'textarea':
//
//                    break;
//
//            }
        });
        $questions = $this->referenceRepository->getReferencesByClass()[Question::class];
        foreach ($questions as $question) {
            if (count($question->getVariant()) > 0) {
                switch ($question->getType()->getTitle()) {
                    case 'radio':
//                        dd($this->faker->randomElement($question->getVariant())->getId());
                        $question->setAnswer([$this->faker->randomElement($question->getVariant())->getId()]);
                        break;

                    case 'checkbox':
                        $answers = $this->faker->eleme($question->getVariant(), $this->faker->numberBetween(1, count($question->getVariant())));
                        $answerIds = [];
                        foreach ($answers as $answer) {
                            $answerIds[] = $answer->getId();
                        }
                        $question->setAnswer($answerIds);
                        break;

                    case 'conformity':
                    case 'order':
                    case 'checkbox_picture':

                        break;
                    case 'input_one':
                    case 'input_many':
                    case 'blank':
                    case 'textarea':

                        break;
                }
                $manager->persist($question);
                $manager->flush();
            };

        }
    }

    /**
     * @throws \Exception
     */

    //todo убрать костыль
    private function getRandomQuestionByTypes(array $types): object
    {
        $question = $this->getRandomReference(Question::class);
        if (in_array($question->getType()->getTitle(), $types)) {
            return $question;
        } else {
            return $this->getRandomQuestionByTypes($types);
        }
    }

    public function getDependencies(): array
    {
        return [
            QuestionFixtures::class,

        ];
    }

}
