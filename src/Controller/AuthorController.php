<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Author;
use App\Repository\AuthorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AuthorController extends AbstractController
{
    #[Route('/author', name: 'app_author')]
    public function index(): Response
    {
        return $this->render('author/index.html.twig', ['controller_name' => 'AuthorController',]);
    }

    #[Route('/showAuthor/{name}', name: 'app_showAuthor')]
    public function showAuthor(string $name): Response
    {
        return $this->render('author/show.html.twig', ['n' => $name]);
    }

    #[Route('/authorDetails/{id}' , name: 'app_authorDetails')]
    public function authorDetails($id): Response
    {
        $author = [
            'id'=> $id,
            'picture'=> 'images',
            'username'=>'author.email',
            'nb_books' => 10,
        ];
        return $this->render("author/showAuthor.html.twig", ['author' => $author]);
    }

    #[Route('/affiche', name: 'app_Affiche')]
    public function affiche(AuthorRepository $repository): Response
    {
        $authors = $repository->findAll();

        return $this->render('author/Affiche.html.twig', [
            'author' => $authors
        ]);
    }

    #[Route('/addstatique', name: 'app_addstatiques')]
    public function addstatique(EntityManagerInterface $em)
    {
        // Création de l'auteur
        $author = new Author();
        $author->setUsername("newtest");
        $author->setEmail("newtest@gmail.com");

        // Persistance et flush
        $em->persist($author);
        $em->flush();

        // Redirection
        return $this->redirectToRoute('app_Affiche');
    }
}