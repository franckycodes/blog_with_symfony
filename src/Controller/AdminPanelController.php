<?php
namespace App\Controller;

use App\Entity\BlogPosts;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;

class AdminPanelController extends AbstractController
{
    private $m_session;
     
    public function __construct(RequestStack $requestStack)
    {
        $this->m_session = $requestStack;
        

       
    }
    public function index(): Response
    {
        $sometest = (int) $this->m_session->getSession()->get('adminonline');
        $em = $this->getDoctrine()->getManager();

        $lastPosts = $em->getRepository(BlogPosts::class)->getAllPosts();

        if ($sometest > 0) {
            return $this->render('mytemplates/adminpanel/index.html.twig', ['title' => 'welcome admin', 'core' => 'adminpanel/welcome/welcome.html.twig', 'articles' => $lastPosts]);
        } else {
            return $this->render('mytemplates/adminpanel/login.html.twig', ['title'=>'Login', 'core'=> 'adminpanel/login/login.html.twig']);
        }
    }

    public function newsession(): RedirectResponse
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

    public function logout(): RedirectResponse
    {
        $this->m_session->getSession()->remove('adminonline');
        // die();
        return $this->redirectToRoute('adminpanel');
    }

    //save new blog
    public function saveNewBlog(): RedirectResponse
    {
        $entityManager = $this->getDoctrine()->getManager();

        echo '<pre>';
        var_dump($_POST);
        echo '</pre>';
        $title = htmlspecialchars($_POST['title']);
        $description = htmlspecialchars($_POST['description']);

        $myblogpost = new BlogPosts();
        $myblogpost->setPostTitle($title);
        $myblogpost->setDescription($description);
        $nowDate = new \DateTime(date('m-d-Y h:m:s'));
        $myblogpost->setDateCreation($nowDate);
        $entityManager->persist($myblogpost);
        $entityManager->flush();
        // die();
        return $this->redirect('/adminpanel/newarticle/'); // $this->redirectToRoute('adminpanel');
    }

    //new blog post
    public function newBlog()
    {
        $em = $this->getDoctrine()->getManager();
        $allPosts = $em->getRepository(BlogPosts::class)->getAllBlog();
        return $this->render('mytemplates/adminpanel/index.html.twig',
            ['title' => 'new blog post',
                'core' => 'adminpanel/blog/new.html.twig',
                'allposts'=>$allPosts]);
    }

    //delete blog
    public function deleteBlog($blogId): RedirectResponse
    {
        echo 'deleting blog id ' . $blogId;
        $entityManager = $this->getDoctrine()->getManager();

        $rep = $entityManager->getRepository(BlogPosts::class)->deleteBlog((int) $blogId);
        echo '<pre>';
        var_dump($rep);
        echo '</pre>';
        echo 'deleted';
        // die();
        return $this->redirect('/adminpanel/newarticle/');//$this->redirectToRoute('adminpanel');
    }

    //update blog
    public function updateBlog($blogId)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $rep = ($entityManager->getRepository(BlogPosts::class)->getBlog((int) $blogId))[0];

        // echo '<pre>';
        // var_dump($rep);

        // die();
        return $this->render('mytemplates/adminpanel/index.html.twig',
            ['blogId' => $blogId,
                'title' => 'update blog post',
                'core' => 'adminpanel/blog/update.html.twig',
                'articleTitle' => $rep['post_title'],
                'articleDescription' => htmlspecialchars_decode($rep['description'])]);

    }
    //save update blog
    public function saveUpdateBlog():RedirectResponse
    {
        $entityManager = $this->getDoctrine()->getManager();
        echo '<pre>';
        var_dump($_POST);
        echo '</pre>';
        $title=htmlspecialchars($_POST['title']);
        $description=htmlspecialchars($_POST['description']);

        $blogId=(int)$_POST['blogId'];
        // die();
        $rep = ($entityManager->getRepository(BlogPosts::class)->updateBlog((int) $blogId, $title, $description))[0];

        // echo '<pre>';
        // var_dump($rep);

        // die();
        // return $this->render('mytemplates/adminpanel/index.html.twig',
        //  ['title'=>'update blog post',
        //  'core'=>'adminpanel/blog/update.html.twig',
        // 'articleTitle'=>$rep['post_title'],
        // 'articleDescription'=>htmlspecialchars_decode($rep['description'])]);
        return $this->redirect('/adminpanel/updatearticle/'.$blogId);//$this->redirectToRoute('adminpanel', ['blogId'=>$blogId]);
    }
}
