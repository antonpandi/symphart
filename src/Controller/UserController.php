<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class UserController extends AbstractController
{

    /**
     * @Route("/user/all", name="user_all")
     * @Method({"GET"})
     */
    public function users() {

      $users = $this
      ->getDoctrine()
      ->getRepository(User::class)
      ->findAll();

      return $this->render('users/all.html.twig', array(
        'users' => $users
      ));
    } 


    /**
     * @Route("/user/new", name="user_new")
     * @Method({"GET","POST"})
     */
    public function newUser(Request $request)
    {

        $user = new User();
        
        $form = $this->createFormBuilder($user)
        ->add('pwd', TextType::class, array(
            'attr' => array( 'class' => 'form-control')
        ))
        ->add('Register', SubmitType::class, array(
            'attr' => array( 'class' => 'form-control')
        ))
        ->getForm();

        $form -> handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $user = $form->getData();

            $entitymanger = $this->getDoctrine()->getManager();
            $entitymanger->persist($user);

            $entitymanger->flush();

            return $this->redirectToRoute('user_all');
        }

        return $this->render('users/new.html.twig', array(
            'form' => $form->createView()
        ));
    }

     /**
      * @Route("/user/{username}", name="user_user")
      */
     public function show(ManagerRegistry $doctrine, $username)
     {
        /** @var UserRepository $userRepository*/
        $userRepository = $doctrine->getRepository(User::class);

        $user = $userRepository->findByUsername($username);

        if(is_null($user)){
            return $this->redirect('/');
        }

        /** @var ArticleRepository $articleRepository */
        $articleRepository = $doctrine->getRepository(Article::class);

        $articles = $articleRepository->findByUsername($username);

        try {
            if ($user->getUserIdentifier() == $this->getUser()->getUserIdentifier() or $$this->getUser()->getRoles() == 'ROLE_ADMIN' ) {
                return $this->render('users/user_logged_in.html.twig' , [
                    'articles' => $articles,
                    'user' => $user
                ]);
            };
        } catch (\Throwable $th) {

            return $this->render('users/user.html.twig', array(
                'articles' => $articles,
                'user' => $user
            ));
        }
     }
}
  