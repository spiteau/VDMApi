<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PostsController extends Controller
{
    /**
     * @Route("/api/posts")
     */
    public function listAction()
    {
        return $this->render('default/index.html.twig');
    }
    
    /**
     * @Route("/api/posts/{id}", requirements={"id" = "\d+"})
     */
    public function getAction($id)
    {
        return $this->render('default/index.html.twig');
    }
}
