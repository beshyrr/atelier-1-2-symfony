<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;// ou managerregistry les deux correct 
use App\Entity\Author;
use App\Form\AuthorType;
use App\Repository\AuthorRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
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

      /* #[Route('/showlist', name: 'app_showlist')]
public function list(): Response
{
    $authors = array(
        array('id' => 1, 'picture' => '/images/Victor_Hugo.jpg', 'username' => 'Victor Hugo', 'email' => 'Victor.Hugo@gmail.com', 'nb_books' => 100),
        array('id' => 2, 'picture' => '/images/William_Shakespeare.jpg', 'username' => 'William Shakespeare', 'email' => 'William.Shakespeare@gmail.com', 'nb_books' => 200),
        array('id' => 3, 'picture' => '/images/Taha_Hussein.jpg', 'username' => 'Taha Hussein', 'email' => 'Taha.Hussein@gmail.com', 'nb_books' => 300)
    );

    return $this->render("author/list.html.twig", ['authors' => $authors]);
}
*/
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

    return $this->render('author/affiche.html.twig', [
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


#[Route('/Add', name: 'app_Add')]
public function Add(Request $request, EntityManagerInterface $em)
{
    $author = new Author();
    $form = $this->createForm(AuthorType::class, $author);
    $form->add('Ajouter', SubmitType::class);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $em->persist($author);
        $em->flush();

        return $this->redirectToRoute('app_Affiche');
    }

    return $this->render('author/Add.html.twig', [
        'f' => $form->createView()
    ]);
}


#[Route(path: '/edit/{id}', name: 'app_edit')]
public function edit(AuthorRepository $repository, EntityManagerInterface $em, $id, Request $request): Response
{
    $author = $repository->find($id);

    if (!$author) {
        throw $this->createNotFoundException('Auteur non trouvé');
    }

    $form = $this->createForm(AuthorType::class, $author);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $em->flush(); // sauvegarde les modifications

        return $this->redirectToRoute('app_Affiche');
    }

    return $this->render('author/edit.html.twig', [
        'form' => $form->createView(),
    ]);
}

#[Route(path: '/delete/{id}', name: 'app_delete')]
public function delete(AuthorRepository $repository, EntityManagerInterface $em, $id, Request $request): Response
{
    $author = $repository->find($id);

    if (!$author) {
        throw $this->createNotFoundException('Auteur non trouvé');
    }

    $em->remove($author); // on passe l'objet à supprimer
    $em->flush();

    return $this->redirectToRoute('app_affiche');
}




}




