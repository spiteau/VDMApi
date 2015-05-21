<?php
namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use AppBundle\Parser\PostListHtmlParser;
use AppBundle\Repository\PostRepository;

class PostsDownloadCommand extends ContainerAwareCommand
{
	const NB_POST_TO_DOWNLOAD = 200;
	
	private
		$postListHtmlParser;
	
	public function __construct($name = null)
	{
		parent::__construct($name);
		
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
		$pageIndex = 0;
		$postFound = 0;
		$allPostDownloaded = false;
		
		while($postFound < self::NB_POST_TO_DOWNLOAD && $allPostDownloaded === false)
		{
			$html = $this->getContent($pageIndex);
			$postList = $this->postListHtmlParser->parse($html);
			$this->getPostRepository()->saveList($postList);
			
			$postFound += count($postList);

			if(empty($postList))
			{
				$allPostDownloaded = true;
			}
			
			$pageIndex++;
		}
	}
	
	private function getContent($pageIndex)
	{
		$url = "http://www.viedemerde.fr/?page=" . $pageIndex;

		return file_get_contents($url);
	}
	
	/**
	 * @return \AppBundle\Repository\PostRepository
	 */
	private function getPostRepository()
	{
		return $this->getContainer()->get("doctrine")->getManager()->getRepository('AppBundle:Post');
	}
}