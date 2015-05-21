<?php

namespace AppBundle\Test\Parser;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use AppBundle\Entity\Post;
use AppBundle\Formatter\PostFormatter;

class PostFormatterTest extends KernelTestCase
{
	public function testFormat()
	{
		$post = new Post(42, 'content$é@!', \Datetime::createFromFormat('d/m/Y H:i', '06/07/2006 13:37'), 'sylvain44');
		
		$formatter = new PostFormatter();
		$formattedArray = $formatter->format($post);
		
		$this->assertArrayHasKey('post', $formattedArray, 'Check "post" key');
		$this->checkPost($post, $formattedArray['post'], '2006-07-06 13:37:00');
	}
	
	public function testFormatList()
	{
		$post1 = new Post(42,  'azertyuiop', \Datetime::createFromFormat('d/m/Y H:i', '06/07/2006 13:37'), 'sylvain44');
		$post2 = new Post(666, 'œ&é"(-è_çà)=', \Datetime::createFromFormat('d/m/Y H:i', '21/05/2015 18:35'), '8dfsdfds!!!&&88');
		$postList = array($post1, $post2);
		
		$formatter = new PostFormatter();
		$formattedArray = $formatter->formatList($postList);
		
		$this->assertArrayHasKey('posts', $formattedArray, 'Check "posts" key');
		$this->assertArrayHasKey('count', $formattedArray, 'Check "count" key');
		$this->assertCount(2, $formattedArray['posts'], 'Check item number in posts');
		$this->assertEquals(2, $formattedArray['count'], 'Check count value');
		$this->checkPost($post1, $formattedArray['posts'][0], '2006-07-06 13:37:00');
		$this->checkPost($post2, $formattedArray['posts'][1], '2015-05-21 18:35:00');
	}
	
	private function checkPost(Post $post, array $formattedArray, $datetime)
	{
		$this->assertArrayHasKey('id',      $formattedArray, 'Check "id" key');
		$this->assertArrayHasKey('content', $formattedArray, 'Check "content" key');
		$this->assertArrayHasKey('date',    $formattedArray, 'Check "datet" key');
		$this->assertArrayHasKey('author',  $formattedArray, 'Check "author" key');
		
		$this->assertEquals($formattedArray['id'],      $post->getId(),       'Check "id" value');
		$this->assertEquals($formattedArray['content'], $post->getContent(),  'Check "content" value');
		$this->assertEquals($formattedArray['date'],    $datetime,            'Check "date" value');
		$this->assertEquals($formattedArray['author'],  $post->getAuthor(),   'Check "author" value');
	}
}
