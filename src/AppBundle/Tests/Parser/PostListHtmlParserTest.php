<?php

namespace AppBundle\Test\Parser;

use AppBundle\Parser\PostListHtmlParser;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use AppBundle\Entity\Post;

class PostListHtmlParserTest extends KernelTestCase
{
	public function testParse()
	{
		$inputFilesDir = dirname(__FILE__) . '/InputHtml/';
		$html = file_get_contents($inputFilesDir . 'examplePage.html');
		
		$parser = new PostListHtmlParser();
		$postList = $parser->parse($html);
		
		$this->assertCount(13, $postList, 'Check number of post found');
		
		/* @var $post \AppBundle\Entity\Post */
		$post = $postList[0];
		$this->assertInstanceOf(Post::class, $post, 'Check class ' . Post::class);
		$this->assertEquals(8554490, $post->getId(), 'Check post id');
		$this->assertEquals(
			"Aujourd'hui, prof dans un collège, nous attendons une spécialiste qui vient expliquer aux élèves de 3ème les dangers de certaines addictions et les mettre en garde. À son arrivée, nous pouvons constater qu'elle domine vraiment son sujet. Elle est ivre morte, ou pas loin. VDM",
			$post->getContent(), 
			'Check post content'
		);
		$this->assertEquals(\Datetime::createFromFormat('d/m/Y à H:i', '21/05/2015 à 14:29'), $post->getDatetime(), 'Check post datetime');
		$this->assertEquals('Djinx', $post->getAuthor(), 'Check post author');
		
		$html = file_get_contents($inputFilesDir . 'emptyPage.html');
		$postList = $parser->parse($html);
		
		$this->assertCount(0, $postList, 'Check no posts found on empty page');
	}
}
