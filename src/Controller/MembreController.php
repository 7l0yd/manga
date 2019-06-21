<?php

namespace App\Controller;


use App\Entity\Membre;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class MembreController extends AbstractController
{
    /**
     * @Route("/inscription", name="membre_inscription")
     */
    public function inscription(Request $request, UserPasswordEncoderInterface $encoder)
    {

        $membre = new Membre();
        $membre->setDateInscription(new\DateTime());
        $membre->setRoles(['ROLE_MEMBRE']);


        $form = $this->createFormBuilder($membre)
            ->add('pseudo', TextType::class, [
                'label' => "Saisissez votre pseudo",
                'attr'  => [
                    'placeholder' => "Saisissez votre pseudo"
                ]
            ])
            ->add('prenom', TextType::class, [
                'label' => "Saisissez votre prenom",
                'attr'  => [
                    'placeholder' => "Saisissez votre prenom"
                ]
            ])
            ->add('nom', TextType::class, [
                'label' => "Saisissez votre nom",
                'attr'  => [
                    'placeholder' => "Saisissez votre nom"
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => "Saisissez votre email",
                'attr' => [
                    'placeholder' => "Saisissez votre email"
                ]
            ])
            ->add('password', PasswordType::class, [
                'label' => "Saisissez votre mot de passe",
                'attr' => [
                    'placeholder' => "Saisissez votre mot de passe"
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => "Je m'inscris!"
            ])
            ->getForm();

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {

            #Encodage du mot de passe
            $membre->setPassword(
                $encoder->encodePassword(
                    $membre,
                    $membre->getPassword()
                )
            );

            #3. Insertion dans le BDD (EntityManager $em)
            $em = $this->getDoctrine()->getManager();
            $em->persist($membre);
            $em->flush();

            #Notification
            $this->addFlash('notice', 'Felicitation, vous pouvez vous connecter!');

            #Redirection
            return $this->redirectToRoute('membre_connexion');

        }


        return $this->render('membre/inscription.html.twig', [
            'form'=>$form->createView()
        ]);
    }

    /**
     * @Route("/connexion", name="membre_connexion")
     */
    public function connexion(AuthenticationUtils $authenticationUtils)
    {
        $form = $this->createFormBuilder([
            'email' => $authenticationUtils->getLastUsername()
        ])
            ->add('email', EmailType::class, [
                'label' => "Saisissez votre email",
                'attr' => [
                    'placeholder' => "Saisissez votre email"
                ]
            ])
            ->add('password', PasswordType::class, [
                'label' => "Saisissez votre mot de passe",
                'attr' => [
                    'placeholder' => "Saisissez votre mot de passe"
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => "Se connecter"
            ])
            ->getForm();

        return $this->render('membre/connexion.html.twig', [
            'form'=>$form->createView(),
            'error'=>$authenticationUtils->getLastAuthenticationError()
        ]);
    }

//    /**
//     * @Route("/admin", name="admin")
//     */
//    public function admin()
//    {
//        return $this->render('membre/admin.html.twig');
//    }

    /**
     * @Route("/profil", name="membre_profil")
     */
    public function profil()
    {
        /**
         *
         * Manque avatar
         *
         */
        $form = $this->createFormBuilder()

            ->add('pseudo', TextType::class, [
                'label' => "Saisissez votre pseudo",
                'attr'  => [
                    'placeholder' => "Saisissez votre pseudo"
                ]
            ])
            ->add('prenom', TextType::class, [
                'label' => "Saisissez votre prenom",
                'attr'  => [
                    'placeholder' => "Saisissez votre prenom"
                ]
            ])
            ->add('nom', TextType::class, [
                'label' => "Saisissez votre nom",
                'attr'  => [
                    'placeholder' => "Saisissez votre nom"
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => "Saisissez votre email",
                'attr' => [
                    'placeholder' => "Saisissez votre email"
                ]
            ])
            ->add('password', PasswordType::class, [
                'label' => "Saisissez votre mot de passe",
                'attr' => [
                    'placeholder' => "Saisissez votre mot de passe"
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => "Mettre à jour"
            ])
            ->getForm();


        return $this->render('membre/profil.html.twig', [
            'form'=>$form->createView()
        ]);
    }

    /**
     * @Route("/deconnexion", name="deconnexion")
     */
    public function deconnexion()
    {

    }

}
