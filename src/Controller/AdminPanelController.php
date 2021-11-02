<?php 
namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;





class AdminPanelController extends AbstractController 
{

    public function index():Response 
    {
 
        return $this->render('adminpanel/login/login.html.twig');
    }

    public function newsession() 
    {
        echo '<pre>';
        var_dump($_POST);
        echo '</pre>';
        die();
        return ;
    }
}