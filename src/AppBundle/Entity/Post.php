<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PostRepository")
 * @ORM\Table(name="posts")
 */
class Post
{
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 **/
	private $id;

	/**
	 * @ORM\Column(type="string")
	 **/
	private $content;
	
	/**
	 * @ORM\Column(type="datetime")
	 **/
	private $datetime;
	
	/** 
	 * @ORM\Column(type="string")
	 **/
	private $author;
	
	/**
	 * @param integer $id
	 * @param string $content
	 * @param \DateTime $datetime
	 * @param string $author
	 */
	public function __construct($id, $content, \DateTime $datetime, $author)
	{
		$this->id = $id;
		$this->content = $content;
		$this->datetime = $datetime;
		$this->author = $author;
	}
	
    /**
     * Set id
     *
     * @param integer $id
     * @return Post
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Set content
     *
     * @param string $content
     * @return Post
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Set datetime
     *
     * @param \DateTime $datetime
     * @return Post
     */
    public function setDatetime(\DateTime $datetime)
    {
        $this->datetime = $datetime;

        return $this;
    }

    /**
     * Set author
     *
     * @param string $author
     * @return Post
     */
    public function setAuthor($author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get content
     *
     * @return string 
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Get datetime
     *
     * @return \DateTime 
     */
    public function getDatetime()
    {
        return $this->datetime;
    }

    /**
     * Get author
     *
     * @return string 
     */
    public function getAuthor()
    {
        return $this->author;
    }
}
