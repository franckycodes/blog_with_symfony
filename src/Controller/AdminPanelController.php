<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use App\Repository\BlogPostsRepository;
use App\Entity\BlogPosts;

class AdminPanelController extends AbstractController
{
    private $m_session;
    public function __construct(RequestStack $requestStack)
    {
        $this->m_session = $requestStack;
    }
    public function index(): Response
    {
        $sometest=(int)$this->m_session->getSession()->get('adminonline');
        $em= $this->getDoctrine()->getManager( );

        $lastPosts=$em->getRepository(BlogPosts::class)->getAllPosts();

        if ($sometest  >0) {
            return $this->render('mytemplates/adminpanel/index.html.twig', ['title'=>'welcome admin', 'core'=>'adminpanel/welcome/welcome.html.twig', 'articles'=>$lastPosts]);
        } else {
            return $this->render('adminpanel/login/login.html.twig');
        }
    }

    public function newsession():RedirectResponse
    {
        echo '<pre>';
        var_dump($_POST);
        echo '</pre>';
        $login = $_POST['login'];
        $pass = $_POST['password'];
        $session = $this->m_session->getSession();
        if (isset($_POST['login'], $_POST['password'])) {
            if ($login === 'root' && $pass === 'hello world') {
                echo 'ok';
                $session->set('adminonline', 1);
                return $this->redirectToRoute('adminpanel');
            } else {
                echo 'wrong';
            }
        }
        die();
        return $this->redirectToRoute('adminpanel');
    }

    public function logout():RedirectResponse
    {
        $this->m_session->getSession()->remove('adminonline' );
        // die();
        return $this->redirectToRoute('adminpanel');
    }

    //save new blog 
    public function saveNewBlog():RedirectResponse
    {
        $entityManager=$this->getDoctrine()->getManager();

        echo '<pre>';
        var_dump($_POST);
        echo '</pre>';
        $title=htmlspecialchars($_POST['title']);
        $description=htmlspecialchars($_POST['description']);

        $myblogpost=new BlogPosts();
        $myblogpost->setPostTitle($title);
        $myblogpost->setDescription($description);
        $nowDate=new \DateTime(date('m-d-Y h:m:s') );
        $myblogpost->setDateCreation($nowDate );
        $entityManager->persist($myblogpost);
        $entityManager->flush();
        // die();
        return $this->redirectToRoute('adminpanel');
    }
    //new blog post 
    public function newBlog(){

        return $this->render('mytemplates/adminpanel/index.html.twig',
         ['title'=>'new blog post', 
         'core'=>'adminpanel/blog/new.html.twig']);
    }
}
