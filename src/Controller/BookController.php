<?php

namespace App\Controller;

use App\Entity\Book;
use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
}