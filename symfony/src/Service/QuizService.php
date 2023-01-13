<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\RequestStack;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service_locator;

class QuizService
{

    const NAME = 'quiz';
    private static int $current;

    public function __construct(private RequestStack $requestStack)
    {
    }

    public function updateOrCreate(int $id): void
    {
        $session = $this->requestStack->getSession();
        $data = $session->get(static::NAME, []);

//        $session->remove('quiz');
//        dd($data);

        if (isset($data[$id])) {
            self::$current = count($data[$id]);
        } else {
            self::$current = 0;
            $session->set(static::NAME, [
                $id => [
                ]
            ]);
        }

    }

    public function answer(int $quiz_id)
    {
        $request = $this->requestStack->getCurrentRequest();
        $session = $this->requestStack->getSession();
        $data = $session->get(static::NAME, []);

        $question_id = $request->request->get('question_id');
        foreach ($request->request->all() as $key => $userAnswer) {
            if ($key !== 'question_id') {
                $data[$quiz_id][$question_id][] = $userAnswer;
            }

        }
        self::$current = count($data[$quiz_id]);
        $session->set(static::NAME, $data);
    }

    public function getCurrent(): int
    {
//        if (self::$current < count($array)) {
////                dd($t, count($array));
//            $array['current'] = $array['current'] + 1;
//        }
        return self::$current;
    }

    private function setCount(int $id): void
    {
        $session = $this->requestStack->getSession();
        $quizInfo = $session->get(static::NAME, [])[$id];
        self::$current = count($quizInfo);
    }

}