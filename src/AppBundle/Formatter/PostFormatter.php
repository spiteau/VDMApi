<?php

namespace AppBundle\Formatter;

use Doctrine\ORM\EntityRepository;
use AppBundle\Entity\Post;

class PostFormatter
{
	public function format(Post $post)
	{
		return array(
			'post' => $this->formatPost($post),
		);
	}
	
	public function formatList(array $postList)
	{
		return array(
			'posts' => array_map(function(Post $post){
				return $this->formatPost($post);
			}, $postList),
			'count' => count($postList),
		);
	}
	
	private function formatPost(Post $post)
	{
		return array (
			'id'      => $post->getId(),
			'content' => $post->getContent(),
			'date'    => $post->getDatetime()->format('Y-m-d H:i:s'),
			'author'  => $post->getAuthor(),
		);
	}
}
