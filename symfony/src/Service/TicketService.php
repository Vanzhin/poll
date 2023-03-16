<?php

namespace App\Service;

use App\Entity\Ticket;
use Doctrine\ORM\EntityManagerInterface;

class TicketService
{
    public function __construct(private readonly EntityManagerInterface $em, private readonly ValidationService $validation)
    {
    }

    public function saveResponse(Ticket $ticket): array
    {
        try {
            if ($ticket->getId()) {
                $message = 'Билет обновлен';
            } else {
                $message = 'Билет создан';

            }
            $this->em->persist($ticket);
            $this->em->flush();
            $response = [
                'message' => $message,
                'ticketId' => $ticket->getId()
            ];
            $status = 200;
        } catch (\Exception $e) {
            $response = ['error' => $e->getMessage()];
            $status = 501;
        } finally {
            return ['response' => $response, 'status' => $status];
        }
    }

    public function saveIfValid(Ticket $ticket): array
    {
        if (count($this->validation->validate($ticket)) > 0) {
            $response = [
                'message' => 'Ошибка при вводе данных',
                'error' => $this->validation->validate($ticket)
            ];
            $status = 422;
            return ['response' => $response, 'status' => $status];
        }
        return $this->saveResponse($ticket);
    }

    public function deleteResponse(Ticket $ticket): array
    {
        try {
            $this->em->remove($ticket);
            $this->em->flush();
            $response = [
                'message' => 'Билет удален',
            ];
            $status = 200;
        } catch (\Exception $e) {
            $response = ['error' => $e->getMessage()];
            $status = 501;
        } finally {
            return ['response' => $response, 'status' => $status];
        }

    }
}