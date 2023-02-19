<?php

namespace App\Form;

use App\Entity\Question;
use App\Entity\Ticket;
use App\Entity\Type;
use App\Entity\Variant;
use App\Repository\TicketRepository;
use App\Repository\TypeRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuestionFormType extends AbstractType
{


    public function __construct(
        private readonly TypeRepository $typeRepository,
        private readonly TicketRepository $ticketRepository,
    )
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /**
         * @var Question $question
         */
        $question = $options['data'] ?? null;

        $builder
            ->add('title', TextType::class, [
                'label' => 'Вопрос',
                'attr' => ['placeholder' => 'Вопрос '],
            ])
//            ->add('answer')
//            ->add('subTitle')
            ->add('type', EntityType::class, [
                'label' => 'Тип',
                'class' => Type::class,
                'choice_label' => function (Type $type) {
                    return "{$type->getTitle()}";
                },
                'choices' => $this->typeRepository->findAll(),

            ])
            ->add("variant", EntityType::class, [
                'label' => 'вариант',
                'mapped' => false,
                'required' => false,
                'class'=>Variant::class,
                'choice_label' => function (Variant $variant) {
                    return "{$variant->getTitle()}";
                },
                'choices' => $question->getVariant()->toArray(),
                'multiple' =>true,
                'choice_attr' => function ($variant) use($question) {
                    // adds a class like attending_yes, attending_no, etc
                    return ['checked' => in_array($variant->getId(),$question->getAnswer())];
                },

            ])
            ->add('tickets', EntityType::class, [
                'label' => 'Билеты',
                'class' => Ticket::class,
                'choice_label' => function (Ticket $ticket) {
                    return "{$ticket->getTitle()}";
                },
                'choices' => $question->getTickets(),

                'multiple' => true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Question::class,
        ]);
    }
}
