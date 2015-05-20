<?php

namespace AppBundle\Parser;

use Symfony\Component\DomCrawler\Crawler;
use AppBundle\Model\Post;

class PostListHtmlParser
{
	public function parse($html)
	{
		$crawler = new Crawler();
		$crawler->addContent($html);
		
		$posts = $crawler->filter('div.post.article')->each(function($postCrawler, $index) {
			return $this->parsePost($postCrawler);
		});
		
		return $posts;
	}
	
	private function parsePost(Crawler $postCrawler)
	{
		$id = $this->parseId($postCrawler);
		$content = $postCrawler->filter('p')->text();
		$datetime = $this->parseDatetime($postCrawler);
		$author = $this->parseAuthor($postCrawler);
		
		return new Post($id, $content, $datetime, $author);
	}
	
	private function parseId(Crawler $postCrawler)
	{
		return (int) $postCrawler->getNode(0)->getAttribute("id");
	}
	
	private function parseContent(Crawler $postCrawler)
	{
		return $postCrawler->filter('p')->text();
	}
	
	private function parseDatetime(Crawler $postCrawler)
	{
		$dataString = $this->parseDataString($postCrawler);
		$matches = array();
		
		if(preg_match("%^Le (\d{2}/\d{2}/\d{4}) à (\d{2}:\d{2}) .*$%", $dataString, $matches) !== 1)
		{
			return null;
		}
		
		$datetime = $matches[1] . " " . $matches[2];
		
		return \Datetime::createFromFormat("d/m/Y H:i", $datetime);
	}
	
	private function parseAuthor(Crawler $postCrawler)
	{
		$dataString = $this->parseDataString($postCrawler);
		$matches = array();
		
		if(preg_match("%^Le .* par (.*) .*$%", $dataString, $matches) !== 1)
		{
			return null;
		}
		
		return $matches[1];
	}
	
	private function parseDataString(Crawler $postCrawler)
	{
		return $postCrawler->filter('div.date div.right_part p')->eq(1)->text();
	}
}
