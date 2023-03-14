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
        $questions = array_filter($this->referenceRepository->getReferencesByClass()[Question::class], function ($question) {
            return $question->getParent() === null;

        });
        foreach ($questions as $question) {
            switch ($question->getType()->getTitle()) {

                case 'radio':
                    $variants = [];
                    $this->createMany(Variant::class, $this->faker->numberBetween(2, 5), function (Variant $variant) use ($manager, $question, $variants) {

                        $variant
                            ->setTitle($this->faker->word() . ' ' . $this->faker->realTextBetween(5, 10) . $this->faker->word())
                            ->setWeight(100)
                            ->addQuestion($manager->getRepository(Question::class)->find($question->getId()));
                        ;
//                        $question->addVariant($variant);
//                        $manager->persist($question);
//                        $manager->flush();
                        $variants[] = $variant;
                    });
//                    dd($variants);
//                    $this->faker->randomElement($variants);
//                    $variant = $this->faker->randomElement($variants)->setCorrect(true);
////                    dd($variant);
//                    $manager->persist($variant);
//                    $manager->flush();

                    break;
                case 'checkbox':
                case 'checkbox_picture':

//                    $this->createMany(Variant::class, $this->faker->numberBetween(2, 5), function (Variant $variant) use ($manager, $question) {
//
//                        $variant
//                            ->setTitle($this->faker->word() . ' ' . $this->faker->realTextBetween(5, 10) . $this->faker->word())
//                            ->setWeight(100)
//                            ->addQuestion($question)
//                            ->setCorrect($this->faker->boolean());
//
//                        $question->addVariant($variant);
//
//                    });
                    break;

                case 'conformity':
//                    $variants = [];
//                    $this->createMany(Variant::class, $this->faker->numberBetween(2, 5), function (Variant $variant) use ($manager, $question) {
//
//                        $variant
//                            ->setTitle($this->faker->word() . ' ' . $this->faker->realTextBetween(5, 10) . $this->faker->word())
//                            ->setWeight(100)
//                            ->addQuestion($question);
//
//                        $question->addVariant($variant);
//                        $variants[] = $variant;
//                    });
//                    foreach ($question->getSubQuestions() as $subQuestion){
//                        $subQuestion->addVariant($this->faker->randomElement($variants));
//                        $manager->persist($subQuestion);
//                        $manager->flush();
//                    }

                    break;

                case 'order':
//                    $i = 0;
//                    $this->createMany(Variant::class, $this->faker->numberBetween(2, 5), function (Variant $variant) use ($manager, $question, $i) {
//
//                        $variant
//                            ->setTitle($this->faker->word() . ' ' . $this->faker->realTextBetween(5, 10) . $this->faker->word())
//                            ->setWeight(100)
//                            ->addQuestion($question)
//                            ->setSequence($i++);
//
//                        $question->addVariant($variant);
//
//                    });

                    break;
            }

        }

    }

    public function getDependencies(): array
    {
        return [
            QuestionFixtures::class,
        ];
    }
}
