<?php

namespace AppBundle\Model;

class Post
{
	private
		$id,
		$content,
		$datetime,
		$author
	;
	
	public function __construct($id, $content, $datetime, $author)
	{
		$this->id = $id;
		$this->content = $content;
		$this->datetime = $datetime;
		$this->author = $author;
	}
	
	public function getId()
	{
		return $this->id;
	}
	
	public function getContent()
	{
		return $this->content;
	}
	
	public function getDatetime()
	{
		return $this->datetime;
	}
	
	public function getAuthor()
	{
		return $this->author;
	}
	
}
