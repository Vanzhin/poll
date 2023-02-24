<?php

namespace App\Controller\Api;

use App\Entity\Question;
use App\Service\QuestionService;
use App\Service\ValidationService;
use App\Twig\Extension\AppUpLoadedAsset;
use League\Flysystem\FilesystemException;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Liip\ImagineBundle\Imagine\Cache\Resolver\ResolverInterface;
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

    #[Route('/api/question/{id}', name: 'app_api_question_show', methods: ['GET'])]
    public function show(Question $question, AppUpLoadedAsset $upLoadedAsset, CacheManager $imagineCacheManager): JsonResponse
    {

//        dd($upLoadedAsset->asset('question_upload',$question->getImage()));
        $resolvedPath = $imagineCacheManager->getBrowserPath($question->getImage(), 'question_thumb');

        dd($resolvedPath);
        return $this->json(
            $question,
            200,
            ['charset=utf-8'],
            ['groups' => 'admin'],
        )->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }

    #[Route('/api/question/create', name: 'app_api_question_create', methods: 'POST')]
    public function create(Request $request, QuestionService $questionService, ValidationService $validation): JsonResponse
    {
        $data = $request->request->all();
        $image = $request->files->get('questionImage');

        $errors = $validation->questionValidate($data['question'] ?? [], $image);
        if (!is_null($errors) && count($errors) > 0) {
            return $this->json([
                'message' => 'Ошибка при вводе данных',
                'error' => $errors],
                422,
                ['charset=utf-8'],
            )->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        }

        try {
            $question = $questionService->save(new Question(), $data['question'] ?? [], $image);
            $response = [
                'message' => 'Вопрос создан',
                'questionId' => $question->getId()
            ];
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

    #[Route('/api/question/{id}/edit', name: 'app_api_question_edit', methods: 'POST')]
    public function edit(Question $question, Request $request, QuestionService $questionService, ValidationService $validation): JsonResponse
    {
        $data = $request->request->all();
        $image = $request->files->get('questionImage');

        $errors = $validation->questionValidate($data['question'] ?? [], $image);
        if (!is_null($errors) && count($errors) > 0) {
            return $this->json([
                'message' => 'Ошибка при вводе данных',
                'error' => $errors],
                422,
                ['charset=utf-8'],
            )->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        }

        try {
            $updated = $questionService->save($question, $data['question'] ?? [], $image);
            $response = [
                'message' => 'Вопрос обновлен',
                'questionId' => $updated->getId()
            ];
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

    #[Route("api/question/{id}/delete", name: 'app_api_question_delete')]
    public function delete(Question $question, QuestionService $questionService): Response
    {
        try {
            $questionService->delete($question);

            $response = [
                'message' => 'Вопрос удален',
            ];
            $status = 200;
        } catch (\Exception $e) {
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
