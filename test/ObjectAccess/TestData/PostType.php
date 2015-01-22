<?php
namespace Light\ObjectAccess\TestData;

use Light\ObjectAccess\Type\Util\DefaultComplexType;
use Light\ObjectAccess\Type\Util\DefaultProperty;

class PostType extends DefaultComplexType
{
	public function __construct()
	{
		parent::__construct("Light\ObjectAccess\TestData\Post");
		$this->addProperty(new DefaultProperty("id"));
		$this->addProperty(new DefaultProperty("title", "string"));
		$this->addProperty(new DefaultProperty("text"));
		$this->addProperty(new DefaultProperty("author", Author::class));
	}
}