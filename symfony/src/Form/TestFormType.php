<?php

namespace App\Form;

use App\Entity\Section;
use App\Entity\Test;
use App\Entity\Ticket;
use App\Repository\SectionRepository;
use App\Repository\TicketRepository;
use Doctrine\DBAL\Exception;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Csrf\CsrfTokenManager;

class TestFormType extends AbstractType
{

    public function __construct(
        private readonly SectionRepository $sectionRepository,
        private readonly TicketRepository  $ticketRepository,
    )
    {
    }

    /**
     * @throws Exception
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder
            ->add('title', TextType::class, [
                'label' => 'Название',
                'attr' => ['placeholder' => 'Название Теста'],
            ])
            ->add('ticket', EntityType::class, [
                'label' => 'Билеты',
                'class' => Ticket::class,
                'choice_label' => function (Ticket $ticket) {
                    return "{$ticket->getTitle()}";
                },
                'choices' => $this->ticketRepository->findAllBySection($builder->getData()->getSection()),

                'multiple' => true
            ])
            ->add('section', EntityType::class, [
                'label' => 'Область аттестации',
                'class' => Section::class,
                'choice_label' => function (Section $section) {
                    return "{$section->getTitle()}";
                },
                'choices' => $this->sectionRepository->findAllSortedByName(),
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Test::class,
        ]);
    }
}
