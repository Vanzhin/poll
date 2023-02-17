<?php

namespace App\Form;

use App\Entity\Question;
use App\Entity\Test;
use App\Entity\Ticket;
use App\Repository\QuestionRepository;
use App\Repository\TestRepository;
use Doctrine\DBAL\Exception;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TicketFormType extends AbstractType
{
    public function __construct(private readonly TestRepository $testRepository, private readonly QuestionRepository $questionRepository)
    {
    }

    /**
     * @throws Exception
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /**
         * @var Ticket $ticket
         */
        $ticket = $options['data'] ?? null;
        $questions = [];

        if ($ticket->getId()) {
            foreach ($ticket->getTests()->toArray() as $test) {
                $questions += $this->testRepository->getAllQuestions($test);
            };
            $tests = $ticket->getTests()->toArray();
        } else {
            $questions = $this->questionRepository->findAll();
            $tests = $this->testRepository->findAll();
        }

        $builder
            ->add('title', TextType::class, [
                'label' => 'Название',
                'attr' => ['placeholder' => 'Название Билета'],
            ])
            ->add('question', EntityType::class, [
                'label' => 'Вопросы',
                'class' => Question::class,
                'choice_label' => function (Question $question) {
                    return "{$question->getTitle()}";
                },
                'choices' => $questions,

                'multiple' => true
            ])
            ->add('tests', EntityType::class, [
                'label' => 'Тесты',
                'class' => Test::class,
                'choice_label' => function (Test $test) {
                    return "{$test->getTitle()}";
                },
                'choices' => $tests,
                'multiple' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ticket::class,
        ]);
    }
}
