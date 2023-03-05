<?php

namespace App\Controller\Api\Admin;

use App\Entity\Question;
use App\Entity\Test;
use App\Service\FileHandler;
use App\Service\QuestionService;
use App\Service\SectionService;
use App\Service\ValidationService;
use App\Service\VariantService;
use League\Flysystem\FilesystemException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    #[Route('/api/admin/test', name: 'app_api_admin_test')]
    public function index(): Response
    {
        return $this->render('api/admin/test/index.html.twig', [
            'controller_name' => 'TestController',
        ]);
    }

    #[Route('/api/admin/test/{id}/upload', name: 'app_api_admin_test_upload', methods: 'POST')]
    public function upload(Test              $test,
                           Request           $request,
                           ValidationService $validation,
                           FileHandler       $handler,
                           QuestionService   $questionService,
                           VariantService    $variantService,
                           SectionService    $sectionService
    ): Response
    {
//        $questions = $test->getQuestion()->toArray();
//        $response = $questionService->getUploadedQuestionsSummary($questions);
//        return $this->json($response,
//                200,
//                ['charset=utf-8'],
//                ['groups'=> 'create']
//            )->setEncodingOptions(JSON_UNESCAPED_UNICODE);

        $file = $request->files->get('file');
        $errors = $validation->fileValidate($file);
        if (!is_null($errors) && count($errors) > 0) {
            return $this->json([
                'message' => 'Ошибка при вводе данных',
                'error' => $errors],
                422,
                ['charset=utf-8'],
            )->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        }
        try {
            $questionData = $handler->getQuestion($file);
            $status = 200;

            $total = [];
            foreach ($questionData as $key => $question) {

                if ($validation->questionValidate($question)) {
                    $total[$key]['error']['question'] = $validation->questionValidate($question);
                }
                if ($validation->manyVariantsValidate($question)) {
                    $total[$key]['error']['variant'] = $validation->manyVariantsValidate($question);
                    $total[$key]['error']['variant']['question'] = $question;
                }

            }
            if ($total) {
                $response = $total;
                $status = 422;
            } else {
                $questions = [];
                foreach ($questionData as $data) {
                    $data['test'] = $test->getId();
                    if ($data['section']) {
                        $data['section'] = $sectionService->createIfNotExist($data['section'], $test)->getId();
                    }
                    $questions[] = $questionService->saveWithVariant(new Question(), $data, $data['variant']);
                };
                $response = $questionService->getUploadedQuestionsSummary($questions);
            }

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
                ['groups' => 'create']
            )->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        }

    }
}
