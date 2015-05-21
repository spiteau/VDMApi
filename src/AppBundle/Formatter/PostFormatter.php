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
	
	public function formatList(\Iterator $postList)
	{
		return array(
			'posts' => $postList,
			'count' => count($postList),
		);
	}
	
	private function formatPost()
	{
		return array (
			'id'      => $post->getId(),
			'content' => $post->getContent(),
			'date'    => $post->getDatetime()->format('Y-m-d H:i:s'),
			'author'  => $post->getAuthor(),
		);
	}
}
