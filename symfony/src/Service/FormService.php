<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class FormService
{
    public function handle(FormInterface $form, Request $request, EntityManagerInterface $em): ?FormInterface
    {
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($form->getData());
            $em->flush();
            return $form;

        }
        return null;
    }
}