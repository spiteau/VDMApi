<?php
namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use AppBundle\Parser\PostListHtmlParser;

class PostsDownloadCommand extends ContainerAwareCommand
{
	private
		$postListHtmlParser;
	
	public function __construct($name = null)
	{
		parent::__construct($name = null);
		
		$this->postListHtmlParser = new PostListHtmlParser();
	}
	
	protected function configure()
	{
		$this
			->setName('posts:download')
			->setDescription('Download posts from VDM site')
		;
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$html = $this->getContent();
		$posts = $this->postListHtmlParser->parse($html);
		
// 		var_dump($posts);die;
	}
	
	private function getContent()
	{
		$url = "http://www.viedemerde.fr/?page=0";

		return file_get_contents($url);
	}
}