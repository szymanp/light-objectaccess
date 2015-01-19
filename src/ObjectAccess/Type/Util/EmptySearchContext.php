<?php
namespace Light\ObjectAccess\Type\Util;

use Light\ObjectAccess\Type\Collection\SearchContext;

/**
 * An empty search context class.
 */
final class EmptySearchContext implements SearchContext
{
	/**
	 * Returns a new empty search context.
	 * @return EmptySearchContext
	 */
	public static function create()
	{
		return new self;
	}

	private function __construct()
	{
		// Nothing here
	}
}