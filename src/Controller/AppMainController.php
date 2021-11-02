<?php 
namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;



class AppMainController extends AbstractController
{
    public function index():Response
    {
       //echo $this->render('home/home.html.twig', ['age'=> 26]);

       return $this->render('mytemplates/default/page.html.twig', ['title'=> 'Home', 'core'=>'home/home.html.twig', 'age'=>30, 'name'=>'Franck']);
    }
    public function blog():Response 
    {
        return $this->render('mytemplates/default/page.html.twig', ['title'=> 'Blog', 'core'=>'blog/blog.html.twig' ]);

    }
    public function number():Response 
    {
        $number= random_int(0,100);

        return new Response('<html><body>Lucky number: '.$number.'</body></html>');
    }
}