<?php
namespace App\Controller;

use App\Entity\BlogPosts;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class AppMainController extends AbstractController {
	public function index(): Response {
		//echo $this->render('home/home.html.twig', ['age'=> 26]);
		$em = $this->getDoctrine()->getManager();

		$recentPosts = $em->getRepository(BlogPosts::class)->getLatestBlogPosts();

		return $this->render('mytemplates/default/page.html.twig', ['recentsPosts' => $recentPosts,
			'title' => 'Home', 'core' => 'home/home.html.twig', 'age' => 30, 'name' => 'Franck']);
	}
	public function blog(): Response {
		$em = $this->getDoctrine()->getManager();

		$recentPosts = $em->getRepository(BlogPosts::class)->getLatestBlogPosts();
		return $this->render('mytemplates/default/page.html.twig', ['recentsPosts' => $recentPosts,
			'title' => 'Blog', 'core' => 'blog/blog.html.twig']);

	}
	/*
		    *
		    *@Route('/contact', 'name:contact',)
	*/
	public function contact(): Response {
		return $this->render('mytemplates/default/page.html.twig', ['title' => 'Contact', 'core' => 'contact/contact.html.twig']);
	}

	public function showBlogPost($blogId): Response {
		$em = $this->getDoctrine()->getManager();
		$blog = ($em->getRepository(BlogPosts::class)->getBlog($blogId))[0];

		// echo '<pre>';
		// var_dump($blog);
		// die();
		return $this->render('mytemplates/default/page.html.twig', ['blogPost' => $blog,
			'title' => ($blog["post_title"]), 'core' => 'blog/readblog.html.twig']);

	}

	public function number(): Response {
		$number = random_int(0, 100);

		return new Response('<html><body>Lucky number: ' . $number . '</body></html>');
	}
}
