<?php

namespace App\Controller\Forum;

use App\Entity\Forum\Post;
use App\Entity\Forum\Topic;
use App\Form\Forum\PostType;
use App\Form\Forum\TopicType;
use App\Entity\Forum\Category;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\Forum\TopicRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/forum')]
class ForumController extends AbstractController
{
    #[Route('/', name: 'app_forum')]
    public function index(
        EntityManagerInterface $entityManager,
        PaginatorInterface $paginatorInterface,
        Request $request
    ): Response {
        // Fetch all categories from the repository
        $data = $entityManager->getRepository(Category::class)->findAll();
    
        // Paginate the results
        $categories = $paginatorInterface->paginate(
            $data, // Query or data to paginate
            $request->query->getInt('page', 1), // Current page number
            4 // Number of items per page
        );
    
        return $this->render('forum/index.html.twig', [
            'categories' => $categories,
        ]);
    }
    

    #[Route('/category/{id}', name: 'app_forum_category')]
    public function category(
    Category $category,
    EntityManagerInterface $entityManager,
    Request $request,
    PaginatorInterface $paginatorInterface): Response
    {
        $data = $entityManager->getRepository(Topic::class)->findBy(
            ['category' => $category],
        );
        $topics = $paginatorInterface->paginate(
            $data,
            $request->query->getInt('page', 1),
            4 
        );

        return $this->render('forum/category.html.twig', [
            'category' => $category,
            'topics' => $topics,
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
    public function topic(Topic $topic, Request $request, EntityManagerInterface $entityManager, PaginatorInterface $paginatorInterface): Response
    {
    
        $data = $entityManager->getRepository(Post::class)->findBy(
            ['topic' => $topic],
        );
        $posts = $paginatorInterface->paginate(
            $data,
            $request->query->getInt('page', 1),
            2
        );

    
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
            'form' => $form->createView(),
            'posts' => $posts
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

    #[Route('/post/{id}/delete', name: 'app_forum_delete_post')]
    #[IsGranted('ROLE_USER')]
    public function deletePost(int $id, EntityManagerInterface $entityManager): Response
    {
        $post = $entityManager->getRepository(Post::class)->find($id);

        if (!$post) {
            throw $this->createNotFoundException('Post not found');
        }

        if ($post->getAuthor() !== $this->getUser() && !$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException('You do not have permission to delete this post.');
        }

        $entityManager->remove($post);
        $entityManager->flush();

        return $this->redirectToRoute('app_forum_topic', ['id' => $post->getTopic()->getId()]);
    }

    #[Route('/topic/{id}/delete', name: 'app_forum_delete_topic')]
    #[IsGranted('ROLE_USER')]
    public function deleteTopic(Topic $topic,int $id, EntityManagerInterface $entityManager, UserRepository $userRepository): Response
    {
        $topic = $entityManager->getRepository(Topic::class)->find($id);

        if (!$topic) {
            throw $this->createNotFoundException('Topic not found');
        }

        if ($topic->getAuthor() !== $this->getUser() && !$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException('You do not have permission to delete this topic.');
        }

        $entityManager->remove($topic);
        $entityManager->flush();

        return $this->redirectToRoute('app_forum_index', [
            'topic' => $topic,
        ]);
    }

    #[Route('/topic/lock/{id}', name: 'app_forum_lock_topic')]
    #[IsGranted('ROLE_ADMIN')]
    public function lockTopic(Topic $topic,int $id, TopicRepository $topicRepository, EntityManagerInterface $entityManager): Response
    {
        $topic = $topicRepository -> find($id);
        // Lock the topic
        $topic->setLocked(true);
        $entityManager->flush();
    
        $this->addFlash('success', 'Le topic a été verrouillé.');
    
        // Redirection vers le détail du topic après l'action
        return $this->redirectToRoute('app_forum_category', [
            'id' => $topic->getId(),  // On passe l'ID du topic pour rediriger vers la page du topic
        ]);
    }
    
    #[Route('/topic/unlock/{id}', name: 'app_forum_unlock_topic')]
    #[IsGranted('ROLE_ADMIN')]
    public function unlockTopic(Topic $topic,int $id, TopicRepository $topicRepository, EntityManagerInterface $entityManager): Response
    {
        $topic = $topicRepository -> find($id);
        // Unlock the topic
        $topic->setLocked(false);
        $entityManager->flush();
    
        $this->addFlash('success', 'Le topic a été déverrouillé.');
    
        // Redirection vers le détail du topic après l'action
        return $this->redirectToRoute('app_forum_category', [
            'id' => $topic->getId(),  // On passe l'ID du topic pour rediriger vers la page du topic
        ]);
    }

}