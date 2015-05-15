<?php
/**
 * @package     Birthdays
 * @subpackage  com_birthdays
 * @copyright   Copyright (C) 2013 Rene Bentes Pinto, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

// Component Helper
jimport('joomla.application.component.helper');
jimport('joomla.application.categories');

/**
 * Birthdays Component Route Helper
 *
 * @package     Birthdays
 * @subpackage  com_birthdays
 * @since       2.5
 */
abstract class BirthdaysHelperRoute
{
	protected static $lookup;

	/**
	 * Get the birthdays route
	 *
	 * @param   int  $id     The route of the birthdays.
	 * @param   int  $catid  The id of the category.
	 *
	 * @return  string
	 *
	 * @since   2.5
	 */
	public static function getBirthdaysRoute($id, $catid)
	{
		$needles = array(
			'birthdays' => array((int) $id)
		);

		// Create the link
		$link = 'index.php?option=com_birthdays&view=birthdays&id=' . $id;

		if ((int) $catid > 1)
		{
			$categories = JCategories::getInstance('Birthdays');
			$category = $categories->get((int) $catid);

			if ($category)
			{
				// TODO Throw error that the category either not exists or is unpublished
				$needles['category'] = array_reverse($category->getPath());
				$needles['categories'] = $needles['category'];
				$link .= '&catid=' . $catid;
			}
		}

		if ($item = self::_findItem($needles))
		{
			$link .= '&Itemid=' . $item;
		}
		elseif ($item = self::_findItem())
		{
			$link .= '&Itemid=' . $item;
		}

		return $link;
	}

	/**
	 * Find the item
	 *
	 * @param   boleam  $needles  The needles.
	 *
	 * @return  void
	 *
	 * @since   2.5
	 */
	protected static function _findItem($needles = null)
	{
		$app   = JFactory::getApplication();
		$menus = $app->getMenu('site');

		// Prepare the reverse lookup array.
		if (self::$lookup === null)
		{
			self::$lookup = array();

			$component = JComponentHelper::getComponent('com_birthdays');
			$items     = $menus->getItems('component_id', $component->id);

			foreach ($items as $item)
			{
				if (isset($item->query) && isset($item->query['view']))
				{
					$view = $item->query['view'];

					if (!isset(self::$lookup[$view]))
					{
						self::$lookup[$view] = array();
					}
					if (isset($item->query['id']))
					{
						self::$lookup[$view][$item->query['id']] = $item->id;
					}
				}
			}
		}

		if ($needles)
		{
			foreach ($needles as $view => $ids)
			{
				if (isset(self::$lookup[$view]))
				{
					foreach ($ids as $id)
					{
						if (isset(self::$lookup[$view][(int) $id]))
						{
							return self::$lookup[$view][(int) $id];
						}
					}
				}
			}
		}
		else
		{
			$active = $menus->getActive();
			if ($active)
			{
				return $active->id;
			}
		}

		return null;
	}
}