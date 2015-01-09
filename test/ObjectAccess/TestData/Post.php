<?php
namespace Light\ObjectAccess\TestData;

class Post
{
	/** @var integer */
	private $id;
	/** @var Author */
	private $author;
	private $title;
	private $text;

	/**
	 * @return int
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * @param int $id
	 */
	public function setId($id)
	{
		$this->id = $id;
	}

	/**
	 * @return Author
	 */
	public function getAuthor()
	{
		return $this->author;
	}

	/**
	 * @param Author $author
	 */
	public function setAuthor(Author $author)
	{
		$this->author = $author;
	}

	/**
	 * @return mixed
	 */
	public function getTitle()
	{
		return $this->title;
	}

	/**
	 * @param mixed $title
	 */
	public function setTitle($title)
	{
		$this->title = $title;
	}

	/**
	 * @return mixed
	 */
	public function getText()
	{
		return $this->text;
	}

	/**
	 * @param mixed $text
	 */
	public function setText($text)
	{
		$this->text = $text;
	}

	/**
	 * @return DefaultComplexType
	 */
	public static function createType()
	{
		$type = new DefaultComplexType("Light\ObjectAccess\TestData\Post");
		$type->addProperty(new DefaultProperty("id"));
		$type->addProperty(new DefaultProperty("title"));
		$type->addProperty(new DefaultProperty("text"));
		return $type;
	}
}
