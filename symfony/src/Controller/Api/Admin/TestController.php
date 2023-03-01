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
////        dd(mb_convert_encoding($file->getContent(), 'utf8'));
////        dd($file->getContent());
//        $array=['1. „“ќ ќ«Ќј„ј≈“ “≈–ћ»Ќ ЂЅ≈«ќѕј—Ќџ≈ ”—Ћќ¬»я “–”ƒјї?
//1. ”слови€ труда, при которых работник не должен пользоватьс€ спецодеждой, спецобувью и средствами индивидуальной защиты.
//*2. ”слови€ труда, при которых воздействие на работающих вредных и (или) опасных производственных факторов исключено либо уровни их воздействи€ не превышают установленных нормативов.
//3. —овокупность факторов производственной среды и трудового процесса, оказывающих вли€ние на работоспособность и здоровье работника.'];
////        dd(json_encode($array, JSON_UNESCAPED_UNICODE));
        return $this->json(
            $response,
            200,
            ['charset'=>'utf8'],
        )->setEncodingOptions(JSON_UNESCAPED_UNICODE);
////        $handler->getQuestion($file);
    }
}
