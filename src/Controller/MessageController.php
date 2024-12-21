<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Message;
use App\Form\MessageType;
use App\Repository\MessageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class MessageController extends AbstractController
{
    #[Route('/message', name: 'app_message')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function index(MessageRepository $messageRepository, AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        return $this->render('message/index.html.twig', [
            'controller_name' => 'MessageController',
            'error' => $error,
        ]);
    }

    #[Route('/message/new', name: 'new_message')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $message = new Message();

        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $message->setSender($this->getUser());

            $entityManager->persist($message);
            $entityManager->flush();

            $this->addFlash('success', 'Votre message a bien été envoyé.');

            return $this->redirectToRoute('new_message');
        }

        return $this->render('message/new.html.twig', [
            'formMessage' => $form->createView(),
            'error' => $form->getErrors(true, false),
        ]);
    }

    #[Route('/message/received', name: 'received_message')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function received(MessageRepository $messageRepository): Response
    {
        $user = $this->getUser();
        $correspondents = $messageRepository->findCorrespondents($user);

        return $this->render('message/received.html.twig', [
            'correspondents' => $correspondents,
        ]);
    }

    #[Route('/message/show/{id}', name: 'show_message')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function show(
        int $id,
        EntityManagerInterface $entityManager,
        MessageRepository $messageRepository,
        Request $request
    ): Response {
        $receiver = $entityManager->getRepository(User::class)->find($id);

        if (!$receiver) {
            throw $this->createNotFoundException('Destinataire introuvable.');
        }

        $user = $this->getUser();
        $messages = $messageRepository->findAllMessages($user, $receiver);

        $message = new Message();
        $form = $this->createForm(MessageType::class, $message, [
            'receiver' => $receiver,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $message->setSender($user);
            $message->setReceiver($receiver);

            $entityManager->persist($message);
            $entityManager->flush();

            return $this->redirectToRoute('show_message', ['id' => $id]);
        }

        return $this->render('message/show.html.twig', [
            'receiver' => $receiver,
            'messages' => $messages,
            'formMessage' => $form->createView(),
        ]);
    }
}
