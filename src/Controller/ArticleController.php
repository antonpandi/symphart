<?php
 
  namespace App\Controller;

  use App\Entity\Article;

  use Symfony\Component\HttpFoundation\Response;
  use Symfony\Component\HttpFoundation\Request;
  use Symfony\Component\Routing\Annotation\Route;
  use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
  use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

  use Symfony\Component\Form\Extension\Core\Type\TextType;
  use Symfony\Component\Form\Extension\Core\Type\TextareaType;
  use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\All;

class ArticleController extends AbstractController{
    /**
     * @Route("/", name="article_list")
     * @Method({"GET"})
     */
    public function index() {

      $articles = $this
      ->getDoctrine()
      ->getRepository(Article::class)
      ->findAll();

      return $this->render('articles/index.html.twig', array(
        'articles' => $articles
      ));
    } 
    
    /**
     * @Route("/article/new", name="new_article")
     * @Method({"GET","POST"})
     */
    public function new(Request $request){
      $article = new Article();

      $form = $this->createFormBuilder($article)
      ->add('title', TextType::class, array(
        'attr' => array('class' => 'form-control')
      ))
      ->add('body', TextareaType::class, array(
        'required' => false,
        'attr' => array('class' => 'form-control')
      ))
      ->add('save', SubmitType::class, array(
        'label' => 'Create',
        'attr' => array('class' => 'btn btn-primary mt-3')
      ))
      ->getForm();

      $form->handleRequest($request);

      if($form->isSubmitted() && $form->isValid()){
        $article = $form->getData();
        

        $email = $request->request->get('email');
        $username = $this->getUser()->getUsername();
        $article->setUsername($username);

        $entityMamager = $this->getDoctrine()->getManager();
        $entityMamager->persist($article);

        $entityMamager->flush();

        return $this->redirectToRoute('article_list');
      }

      return $this->render('articles/new.html.twig', array(
        'form' => $form->createView()
      ));
    }


    /**
     * @Route("/article/edit/{id}", name="edit_article")
     * @Method({"GET","POST"})
     */
    public function edit(Request $request, $id){
      $article = new Article();
      $article = $this->getDoctrine()->getRepository
      (Article::class)->find($id);

      $form = $this->createFormBuilder($article)
      ->add('title', TextType::class, array(
        'attr' => array('class' => 'form-control')
      ))
      ->add('body', TextareaType::class, array(
        'required' => false,
        'attr' => array('class' => 'form-control')
      ))
      ->add('username', TextType::class, array(
        'attr' => array('class' => 'from-control')
      ))
      ->add('save', SubmitType::class, array(
        'label' => 'Update',
        'attr' => array('class' => 'btn btn-primary mt-3')
      ))
      ->getForm();

      $form->handleRequest($request);

      if($form->isSubmitted() && $form->isValid()){

        $entityMamager = $this->getDoctrine()->getManager();
        $entityMamager->flush();

        return $this->redirectToRoute('article_list');
      }

      return $this->render('articles/edit.html.twig', array(
        'form' => $form->createView()
      ));
    }


    /**
     * @Route("article/{id}", name="article_show")
     */
    public function show($id){
      $article = $this->getDoctrine()->getRepository
      (Article::class)->find($id);
      
      return $this->render('articles/show.html.twig', array(
        'article' => $article,
      ));
    }

    /**
     * @Route("/article/delete/{id}")
     * @Method({"DELETE"})
     */
    public function delete(Request $request, $id){
      $article = $this->getDoctrine()->getRepository
      (Article::class)->find($id);

      
      $entityMamager = $this->getDoctrine()->getManager();
      $entityMamager->remove($article);
      $entityMamager->flush();

      $response = new Response();
      $response->send();
    }


    // /**
    //  * @Route("/article/save")
    //  */
    // public function save(){
    //     $entitymanager = $this->getDoctrine()->getManager();

    //     $article = new Article();
    //     $article->setTitle('Article Two');
    //     $article->setBody('This is the body for article Two');
    //   #Prepares article to be saved
    //     $entitymanager->persist($article);

    //   #Saves article to database
    //     $entitymanager->flush();

    //     return new Response('Saves an article with the id of ' . $article->getId());
    // 
}