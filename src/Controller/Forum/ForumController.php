<?php

namespace App\Controller\Forum;

use App\Entity\Forum\Category;
use App\Entity\Forum\Post;
use App\Entity\Forum\Topic;
use App\Form\Forum\PostType;
use App\Form\Forum\TopicType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/forum')]
class ForumController extends AbstractController
{
    #[Route('/', name: 'app_forum')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $categories = $entityManager->getRepository(Category::class)->findAll();

        return $this->render('forum/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    #[Route('/category/{id}', name: 'app_forum_category')]
    public function category(Category $category): Response
    {
        return $this->render('forum/category.html.twig', [
            'category' => $category,
        ]);
    }

    #[Route('/topic/new', name: 'app_forum_new_topic')]
    #[IsGranted('ROLE_USER')]
    public function newTopic(Request $request, EntityManagerInterface $entityManager): Response
    {
        $topic = new Topic();
        $form = $this->createForm(TopicType::class, $topic);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $topic->setAuthor($this->getUser());
            $entityManager->persist($topic);
            $entityManager->flush();

            return $this->redirectToRoute('app_forum_topic', ['id' => $topic->getId()]);
        }

        return $this->render('forum/new_topic.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/topic/{id}', name: 'app_forum_topic')]
    public function topic(Topic $topic, Request $request, EntityManagerInterface $entityManager): Response
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $post->setAuthor($this->getUser());
            $post->setTopic($topic);

            $entityManager->persist($post);
            $entityManager->flush();

            return $this->redirectToRoute('app_forum_topic', ['id' => $topic->getId()]);
        }

        return $this->render('forum/topic.html.twig', [
            'topic' => $topic,
            'form' => $form,
        ]);
    }

    #[Route('/post/edit/{id}', name: 'app_forum_edit_post')]
    #[IsGranted('ROLE_USER')]
    public function editPost(Post $post, Request $request, EntityManagerInterface $entityManager): Response
    {
        // Vérifie que l'utilisateur connecté est bien l'auteur du message
        if ($post->getAuthor() !== $this->getUser()) {
            $this->addFlash('error', 'Vous ne pouvez modifier que vos propres messages.');
            return $this->redirectToRoute('app_forum_topic', ['id' => $post->getTopic()->getId()]);
        }

        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Votre message a été modifié avec succès.');

            return $this->redirectToRoute('app_forum_topic', ['id' => $post->getTopic()->getId()]);
        }

        return $this->render('forum/edit_post.html.twig', [
            'form' => $form,
            'post' => $post,
        ]);
    }

    #[Route('/topic/edit/{id}', name: 'app_forum_edit_topic')]
    #[IsGranted('ROLE_USER')]
    public function editTopic(Topic $topic, Request $request, EntityManagerInterface $entityManager): Response
    {
        // Vérifie que l'utilisateur connecté est bien l'auteur du topic
        if ($topic->getAuthor() !== $this->getUser()) {
            $this->addFlash('error', 'Vous ne pouvez modifier que vos propres topics.');
            return $this->redirectToRoute('app_forum_category', ['id' => $topic->getCategory()->getId()]);
        }

        $form = $this->createForm(TopicType::class, $topic);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Votre topic a été modifié avec succès.');

            return $this->redirectToRoute('app_forum_category', ['id' => $topic->getCategory()->getId()]);
        }

        return $this->render('forum/edit_topic.html.twig', [
            'form' => $form,
            'topic' => $topic,
        ]);
    }

}