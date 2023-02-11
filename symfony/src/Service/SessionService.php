<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\RequestStack;

class SessionService
{
    public function __construct(private readonly RequestStack $requestStack)
    {
    }

    public function add(array $question, string $name): void
    {
        $session = $this->requestStack->getSession();

        if ($session->has($name)) {
            $data = $session->get($name, []);
            $data += $question;
            $session->set($name, $data);

        } else {
            $session->set($name, $question);
        }

    }
    public function set(string $data, string $name): void
    {
        $session = $this->requestStack->getSession();
        $session->set($name, $data);

    }

    public function show(string $name): void
    {
        $session = $this->requestStack->getSession();
//        $session->remove($name);
        dd($session->get($name, []));

    }

    public function get(string $name): mixed
    {
        $session = $this->requestStack->getSession();
        return $session->get($name, []);

    }
    public function remove(string $name): void
    {
        $session = $this->requestStack->getSession();
        $session->remove($name);

    }
}