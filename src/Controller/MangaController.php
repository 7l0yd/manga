<?php

namespace App\Controller;


use App\Entity\Membre;
use App\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MangaController extends AbstractController
{


    use PostTrait;

    /**
     * Page pour insérer du contenu
     * @Route("/create", name="manga_create")
     */
    public function create(Request $request)
    {


        $id = $this->getUser();
        $post = new Post();
        $post->setDateCreation(new\DateTime());
        $post->setMembre($this->getDoctrine()->getRepository(Membre::class)->find($id));

        $form = $this->createFormBuilder($post)
            ->add('titre', TextType::class, [
                'label' => "Saisissez un titre",
                'attr' => [
                    'placeholder' => "Saisissez un titre"
                ]
            ])
            ->add('contenu', TextareaType::class, [
                'label' => "Saisissez un contenu",
                'attr' => [
                    'placeholder' => "Saisissez un contenu"
                ]
            ])
            ->add('lien', TextType::class, [
                'label' => "Insérer un lien",
                'attr' => [
                    'placeholder' => "Insérer un lien"
                ]
            ])
            ->add('featured_image', FileType::class, [
                'label' => "Insérer une image",
                'attr' => [
                    'placeholder' => "Insérer une image"
                ]
            ])

            ->add('submit', SubmitType::class, [
                'label' => "S'inscrire"
            ])
            ->getForm();


        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {

            $post->setSlug(
                $this->slugify(
                    $post->getTitre()
                )
            );

            #3. Insertion dans le BDD (EntityManager $em)
            $em = $this->getDoctrine()->getManager();
            $em->persist($post);
            $em->flush();

            #Notification
            $this->addFlash('notice', 'Felicitation, vous pouvez vous connecter!');

            #Redirection
//            return $this->redirectToRoute('connexion');

        }



//        return $this->render('membre/inscription.html.twig', [
//            'form'=>$form->createView()
//        ]);




        return $this->render('manga/create.html.twig', [
            'form'=>$form->createView()
        ]);
    }
}
