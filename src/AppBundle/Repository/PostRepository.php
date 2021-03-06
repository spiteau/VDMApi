<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use AppBundle\Entity\Post;

class PostRepository extends EntityRepository
{
	const FILTER_AUTHOR     = 'author';
	const FILTER_START_DATE = 'startDate';
	const FILTER_END_DATE   = 'endDate';
	
	public function save(Post $post, $flush = true)
	{
		$em = $this->getEntityManager();
		$em->merge($post);
		
		if($flush)
		{
			$em->flush();
		}
	}
	
	public function saveList(array $postList)
	{
		foreach($postList as $post)
		{
			$this->save($post, false);
		}
		
		$this->getEntityManager()->flush();
	}
	
	public function findFilteredResults(array $filters = array())
	{
		$qb = $this->createQueryBuilder('p');
		
		if($this->filterExists(self::FILTER_AUTHOR, $filters))
		{
			$qb->andWhere('p.author = :author');
			$qb->setParameter('author', $filters[self::FILTER_AUTHOR]);
		}
		
		if($this->filterExists(self::FILTER_START_DATE, $filters))
		{
			$qb->andWhere('p.datetime >= :start_date');
			$qb->setParameter('start_date', $filters[self::FILTER_START_DATE]);
		}
		
		if($this->filterExists(self::FILTER_END_DATE, $filters))
		{
			$qb->andWhere('p.datetime <= :end_date');
			$qb->setParameter('end_date', $filters[self::FILTER_END_DATE]);
		}
		
		return $qb->getQuery()->execute();
	}
	
	private function filterExists($key, array $filters)
	{
		return array_key_exists($key, $filters) && !empty($filters[$key]);
	}
}
