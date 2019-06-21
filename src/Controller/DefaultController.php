<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * Page d'accueil
     * @Route("/", name="default_accueil")
     */
    public function accueil()
    {
        return $this->render('default/accueil.html.twig');
    }

    /**
     * Page de contact
     * @Route("/contact", name="default_contact")
     */
    public function contact()
    {
        return $this->render('default/contact.html.twig');
    }

    /**
     * Page de détail manga
     * @Route("/detail/{slug<[a-zA-Z0-9_/-]+>}", name="default_detail")
     */
    public function detail(Post $post)
    {
        return $this->render('default/detail.html.twig', [
            'post' => $post
        ]);
    }

    /**
     * Page résultat de recherche
     * @Route("/result", name="default_result")
     */
    public function result()
    {
        return $this->render('default/result.html.twig');
    }

    /**
     * Page catégorie ( a conserver par rapport a l'API)
     * @Route("/categorie", name="default_categorie")
     */
    public function categorie()
    {
        return $this->render('default/categorie.html.twig');
    }
}
