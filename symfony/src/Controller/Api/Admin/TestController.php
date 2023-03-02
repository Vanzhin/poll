<?php

namespace App\Controller\Api\Admin;

use App\Entity\Test;
use App\Service\FileHandler;
use App\Service\ValidationService;
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
    public function upload(Test $test, Request $request, ValidationService $validation, FileHandler $handler): Response
    {
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

        $response = $handler->getQuestion($file);
//        $total = [];
//        foreach ($response as $key =>$question){
//
//            if($validation->manyVariantsValidate($question)){
//                $total[$key] = $validation->manyVariantsValidate($question);
//                $total[$key]['question'] = $question;
//            }
//
//        }
//        if($total){
//            return $this->json(
//                $total,
//                200,
//                ['charset'=>'utf8'],
//            )->setEncodingOptions(JSON_UNESCAPED_UNICODE);
//        }
        return $this->json(
            $response,
            200,
            ['charset'=>'utf8'],
        )->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }
}
