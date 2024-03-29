<?php

namespace App\Controller;

use App\Entity\User;
use App\Factory\UserFactory;
use App\Repository\UserRepository;
use App\Service\Mailer;
use App\Service\QuizService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Http\LoginLink\LoginLinkHandlerInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig',
            [
                'last_username' => $lastUsername,
                'error' => $error,

            ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route('/login_link/{savedEmail}', name: 'app_login_link')]
    public function requestLoginLink(ValidatorInterface        $validator,
                                     UserFactory               $factory,
                                     EntityManagerInterface    $entityManager,
                                     LoginLinkHandlerInterface $loginLinkHandler,
                                     UserRepository            $userRepository,
                                     Request                   $request,
                                     Mailer                    $mailer,
                                     QuizService               $quizService,
                                     string                    $savedEmail = null): Response
    {
        $email = $request->request->get('email');
        $violations = $validator->validate($email, [
            new Email(),
        ]);

        if (0 !== count($violations)) {
            $this->addFlash('security_flash', 'Указан не верный формат email.');
            return $this->render('security/login_link.html.twig', ['savedEmail' => $email]);
        }

        if ($request->isMethod('POST')) {
            $user = $userRepository->findOneBy(['email' => $email]);
            if (!$user) {
                $user = $factory->create($email);
                $entityManager->persist($user);
                $entityManager->flush();
            }

            $loginLinkDetails = $loginLinkHandler->createLoginLink($user);

            try {
                $mailer->sendLoginLinkEmail($user, $loginLinkDetails);
            } catch (TransportExceptionInterface $e) {
                $this->addFlash('email_failure_flash', 'При отправке сообщения произошла ощибка. Пожалуйста, повтортите попытку позже.');
                return $this->render('security/login_link.html.twig', ['savedEmail' => $email]);
            }
            $quizService->saveOneResult($user, $request->getSession()->get('current_quiz'));
            $this->addFlash('email_flash', 'Ссылка для входа в личный кабинет отправлена на ' . $user->getEmail());

            return $this->render('security/login_link_sent.html.twig', ['savedEmail' => $email]);
        }

        return $this->render('security/login_link.html.twig', ['savedEmail' => $savedEmail]);
    }

    #[Route('/login_check', name: 'app_login_check')]
    public function check(Request $request): Response
    {
        // get the login link query parameters
        $expires = $request->query->get('expires');
        $username = $request->query->get('user');
        $hash = $request->query->get('hash');

        // and render a template with the button
        return $this->render('security/process_login_link.html.twig', [
            'expires' => $expires,
            'user' => $username,
            'hash' => $hash,
        ]);
    }


}
