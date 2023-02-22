<?php

namespace App\Controller\Api;

use App\Entity\Question;
use App\Entity\Type;
use App\Entity\Variant;
use App\Service\QuestionService;
use App\Service\ValidationService;
use App\Service\VariantService;
use Doctrine\ORM\EntityManagerInterface;
use League\Flysystem\FilesystemException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuestionController extends AbstractController
{
    #[Route('/api/question', name: 'app_api_question')]
    public function index(): Response
    {
        return $this->render('api/question/index.html.twig', [
            'controller_name' => 'QuestionController',
        ]);
    }

    #[Route('/api/question/create', name: 'app_api_question_create', methods: 'POST')]
    public function save(Request $request, EntityManagerInterface $em, QuestionService $questionService, VariantService $variantService, ValidationService $validation): JsonResponse
    {
        $data = $request->request->all();
        $files = $request->files;

        $errors = $validation->questionValidate($data['question'] ?? [], $files);
        if (!is_null($errors) && count($errors) > 0) {
            return $this->json([
                'message' => 'Ошибка при вводе данных',
                'error' => $errors],
                422,
                ['charset=utf-8'],
            )->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        }

        try {
            $question = $questionService->save(new Question(), $data['question'] ?? [], $files);
            foreach ($data['question']['variant'] ?? [] as $variantData) {
                $question->addVariant($variantService->save(new Variant(), $variantData, $question, $files));
            }
            if ($question->getVariant() && isset($data['question']['answer'])) {
                $answerIds = $questionService->getAnswerIds($question, $data['question'] ?? []);
                $question->setAnswer($answerIds);
            }

            $em->persist($question);
            $em->flush();
            $response = ['message' => 'вопрос обновлен'];
            $status = 200;
        } catch (\Exception $e) {
            $response = ['error' => $e->getMessage()];
            $status = 501;
        } catch (FilesystemException $e) {
            $response = ['error' => $e->getMessage()];
            $status = 501;
        } finally {
            return $this->json($response,
                $status,
                ['charset=utf-8'],
            )->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        }

    }
}
