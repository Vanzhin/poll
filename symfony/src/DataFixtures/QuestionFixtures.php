<?php

namespace App\DataFixtures;

use App\Entity\Question;
use App\Entity\Type;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class QuestionFixtures extends BaseFixtures implements DependentFixtureInterface
{
    private static array $data = [
        [
            'content' => 'Периодический пожарный инструктаж работников учебного заведения проводят',
            'variant' => [
                'ежегодно',
                'ежеквартально',
                'ежемесяно',
                'раз в полгода'

            ],
            'answer' => [
                'раз в полгода'
            ],
            'type' => 'checkbox'
        ],
        [
            'content' => 'Автостоянки могут размещаться',
            'variant' => [
                'на специально оборудованной открытой площадке на уровне земли',
                'под проездами, улицами, площадями, скверами, газонами',
                'встраиваться в здания другого функционального назначения',
                'под знакми класса Ф1.1, Ф4.1'

            ],
            'answer' => [
                'на специально оборудованной открытой площадке на уровне земли',
                'под проездами, улицами, площадями, скверами, газонами',
                'встраиваться в здания другого функционального назначения',
            ],
            'type' => 'checkbox'
        ],
        [
            'content' => 'На выходе установлен турникет. Является ли этот выход эвакуационным?',
            'variant' => [
                'да',
                'нет',

            ],
            'answer' => [
                'нет',
            ],
            'type' => 'radio'
        ],
    ];


    function loadData(ObjectManager $manager)
    {
        foreach (self::$data as $item) {
            $this->create(Question::class, function (Question $question) use ($manager, $item) {
                $question
                    ->setContent($item['content'])
                    ->setVariant($item['variant'])
                    ->setAnswer($item['answer'])
                    ->setType($manager->getRepository(Type::class)->findOneBy(['title'=>$item['type']]));
            });
        }
        $this->manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            TypeFixtures::class,
        ];
    }
}
