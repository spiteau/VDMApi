<?php

namespace AppBundle\Parser;

use Symfony\Component\DomCrawler\Crawler;
use AppBundle\Entity\Post;

/**
 * Parse html page for blocks like this :
 * 
 * <div class="post article" id="8554262">
 * 		<p><a href="/amour/8554262" class="fmllink">Aujourd'hui, mon grand-père explique à ma grand-mère les techniques de pêche à la mouche, elle lui répond avec les techniques de cuisson des lasagnes.</a><a href="/amour/8554262" class="fmllink"> Ils sont pourtant persuadés de parler du même sujet, depuis une heure.</a><a href="/amour/8554262" class="fmllink"> VDM</a></p>
 * 		<div class="date">
 * 			<div class="left_part"><a href="/amour/8554262" id="article_8554262" name="/resume/article/8554262" class="jTip">#8554262</a><br><span class="dyn-comments">41 commentaires</span></div>
 * 			<div class="right_part">
 * 				<p><span class="dyn-vote-j" id="vote8554262"><a href="javascript:;" onclick="vote('8554262','4505','agree');">je valide, c'est une VDM</a> (<span class="dyn-vote-j-data">4505</span>)</span> - <span class="dyn-vote-t" id="votebf8554262"><a href="javascript:;" onclick="vote('8554262','341','deserve');" class="bf">tu l'as bien mérité</a> (<span class="dyn-vote-t-data">341</span>)</span></p>
 * 				<p>Le 20/05/2015 à 23:00 - <a class="liencat" href="/amour">amour</a> - par papytété </p>
 * 			</div>
 * 		</div>
 * 		<div class="more" id="more8554262"><div class="fb-like" data-href="http://www.viedemerde.fr/amour/8554262" data-send="false" data-width="100" data-height="21" data-layout="button_count" data-show-faces="false" data-font="lucida grande"></div><a href="javascript:;" onclick="return twitter_click('http://www.viedemerde.fr/amour/8554262#new','8554262');" class="tooltips t_twitter"></a></div>
 * </div>
 */
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
