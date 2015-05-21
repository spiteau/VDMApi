<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Repository\PostRepository;
use AppBundle\Entity\Post;
use Symfony\Component\HttpFoundation\Symfony\Component\HttpFoundation;
use AppBundle\Formatter\PostFormatter;

class PostsController extends Controller
{
	private
		$postFormatter;
	
	public function __construct()
	{
		$this->postFormatter = new PostFormatter();
	}
	
    /**
     * @Route("/api/posts")
     */
    public function listAction()
    {
    	$response = new JsonResponse();
    	
    	$request = $this->getRequest();
    	
//     	try
//     	{
	    	$filters = array(
	    		PostRepository::FILTER_AUTHOR     => $request->get('author', null),
	    		PostRepository::FILTER_START_DATE => $request->get('from', null),
	    		PostRepository::FILTER_END_DATE   => $request->get('to', null),
	    	);
	    	
	    	$postList = $this->getPostRepository()->findFilteredResults($filters);
	    	$response->setData($this->postFormatter->formatList($postList));
//     	}
//     	catch (\Exception $e)
//     	{
//     		$response->setStatusCode(JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
//     	}
    	
        return $response;
    }
    
    /**
     * @Route("/api/posts/{id}", requirements={"id" = "\d+"})
     */
    public function getAction($id)
    {
    	$response = new JsonResponse();
    	
    	try
    	{
	    	$post = $this->getPostRepository()->find($id);
	    	$response->setData($this->postFormatter->format($post));
    	}
    	catch (\Exception $e)
    	{
    		$response->setStatusCode(JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
    	}
    	
        return $response;
    }
    
    /**
     * @return \AppBundle\Repository\PostRepository
     */
    private function getPostRepository()
    {
    	return $this->getDoctrine()->getManager()->getRepository('AppBundle:Post');
    }
}
