<?php
namespace Light\ObjectAccess\TestData;

class Database
{
	private $posts = array();
	private $authors = array();

	public function __construct()
	{
		$author = new Author();
		$author->id = 1010;
		$author->age = 35;
		$author->name = "Max Ray";
		$this->addAuthor($author);

		$author = new Author();
		$author->id = 1020;
		$author->age = 18;
		$author->name = "Johnny Doe";
		$this->addAuthor($author);

		$post = new Post();
		$post->setAuthor($this->getAuthor(1010));
		$post->setId(4040);
		$post->setTitle("First post");
		$post->setText("Lorem ipsum dolor");
		$this->addPost($post);

		$post = new Post();
		$post->setAuthor($this->getAuthor(1010));
		$post->setId(4041);
		$post->setTitle("Second post");
		$post->setText("Lorem lorem");
		$this->addPost($post);

		$post = new Post();
		$post->setAuthor($this->getAuthor(1020));
		$post->setId(4042);
		$post->setTitle("Is this working?");
		$post->setText("Let us test it");
		$this->addPost($post);
	}

	public function getPosts()
	{
		return $this->posts;
	}

	public function getAuthors()
	{
		return $this->authors;
	}

	public function addPost(Post $post)
	{
		$this->posts[$post->getId()] = $post;
	}

	public function addAuthor(Author $author)
	{
		$this->authors[$author->id] = $author;
	}

	/**
	 * @param $id
	 * @return Author
	 */
	public function getAuthor($id)
	{
		return $this->authors[$id];
	}

	/**
	 * @param $id
	 * @return Post
	 */
	public function getPost($id)
	{
		return $this->posts[$id];
	}
}