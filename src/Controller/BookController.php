<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\Author;
use App\Form\BookType;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookController extends AbstractController
{
    #[Route('/book', name: 'app_book')]
    public function index(): Response
    {
        return $this->render('book/index.html.twig', [
            'controller_name' => 'BookController',
        ]);
    }

    #[Route('/afficheBook', name: 'app_AfficheBook')]
    public function affiche(BookRepository $repository): Response
    {
        $publishedBooks = $repository->findBy(['published' => true]);
        $unpublishedBooks = $repository->findBy(['published' => false]);

        $numPublishedBooks = count($publishedBooks);
        $numUnpublishedBooks = count($unpublishedBooks);

        if ($numPublishedBooks > 0 || $numUnpublishedBooks > 0) {
            return $this->render('book/affiche.html.twig', [
                'publishedBooks' => $publishedBooks,
                'numPublishedBooks' => $numPublishedBooks,
                'numUnpublishedBooks' => $numUnpublishedBooks
            ]);
        } else {
            return $this->render('book/no_books_found.html.twig');
        }
    }

    #[Route('/showBook/{ref}', name: 'app_showBook')]
    public function show(BookRepository $repository, $ref): Response
    {
        $book = $repository->findOneBy(['ref' => $ref]);

        if (!$book) {
            throw $this->createNotFoundException('Livre non trouvé.');
        }

        return $this->render('book/show.html.twig', [
            'book' => $book
        ]);
    }

    #[Route('/AddBook', name: 'app_AddBook')]
    public function Add(Request $request, EntityManagerInterface $em): Response
    {
        $book = new Book();
        $form = $this->createForm(BookType::class, $book);
        $form->add('Ajouter', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $author = $book->getAuthor();
            if ($author instanceof Author) {
                $author->setNbBooks($author->getNbBooks() + 1);
            }

            $em->persist($book);
            $em->flush();

            return $this->redirectToRoute('app_AfficheBook');
        }

        return $this->render('book/Add.html.twig', [
            'f' => $form->createView()
        ]);
    }

    #[Route('/editBook/{ref}', name: 'app_editBook')]
    public function edit(Request $request, EntityManagerInterface $em, BookRepository $repository, $ref): Response
    {
        // récupérer le livre par ref
        $book = $repository->findOneBy(['ref' => $ref]);

        if (!$book) {
            throw $this->createNotFoundException('Livre non trouvé.');
        }

        $form = $this->createForm(BookType::class, $book);
        $form->add('Modifier', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('app_AfficheBook');
        }

        return $this->render('book/edit.html.twig', [
            'f' => $form->createView()
        ]);
    }

    #[Route('/deleteBook/{ref}', name: 'app_deleteBook')]
    public function delete(BookRepository $repository, EntityManagerInterface $em, $ref): Response
    {
        $book = $repository->findOneBy(['ref' => $ref]);

        if (!$book) {
            throw $this->createNotFoundException('Livre non trouvé.');
        }

        $em->remove($book);
        $em->flush();

        return $this->redirectToRoute('app_AfficheBook');
    }
}