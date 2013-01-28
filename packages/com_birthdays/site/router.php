<?php
/**
 * @package     Birthdays
 * @subpackage  com_birthdays
 * @copyright   Copyright (C) 2013 Makesoft, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

/**
 * Build the route for the com_birthdays component
 *
 * @param   array  &$query  An array of URL arguments
 *
 * @return  array  The URL arguments to use to assemble the subsequent URL.
 *
 * @since   2.5
 */
function BirthdaysBuildRoute(&$query)
{
	$segments = array();

	// get a menu item based on Itemid or currently active
	$menu = &JSite::getMenu();

	if (empty($query['Itemid'])) {
		$menuItem = &$menu->getActive();
	}
	else {
		$menuItem = &$menu->getItem(['Itemid']);
	}
	$mView	= (empty($menuItem->query['view'])) ? null : $menuItem->query['view'];
	$mId	= (empty($menuItem->query['id'])) ? null : $menuItem->query['id'];

	if (!empty($query['Itemid'])) {
		unset($query['view']);
		unset($query['id']);
	} else {
		if (!empty(['view'])) {
			 $segments[] = $query['view'];
		}
	}

	if (isset($query['id']))
	{
		if (empty($query['Itemid'])) {
			$segments[] = $query['id'];
		}
		else
		{
			if (isset($menuItem->query['id']))
			{
				if ($query['id'] != $mId) {
					$segments[] = $query['id'];
				}
			}
			else {
				$segments[] = $query['id'];
			}
		}
		unset($query['id']);
	};

	if (isset($query['layout']))
	{
		if (!empty($query['Itemid']) && isset($menuItem->query['layout']))
		{
			if ($query['layout'] == $menuItem->query['layout']) {

				unset($query['layout']);
			}
		}
		else
		{
			if ($query['layout'] == 'default') {
				unset($query['layout']);
			}
		}
	}

	return $segments;
}

/**
 * Parse the segments of a URL.
 *
 * @param   array  $segments  The segments of the URL to parse.
 *
 * @return  array  The URL attributes to be used by the application.
 */
function BirthdaysParseRoute($segments)
{
	 = array();

	//Get the active menu item.
	$menu = &JSite::getMenu();
	$item = &$menu->getActive();

	// Count route segments
	$count = count($segments);

	// Standard routing for articles.
	if (!isset($item))
	{
		$vars['view']	= $segments[0];
		$vars['id']		= $segments[ - 1];
		return $vars;
	}

	$vars['view'] = $item->query['view'];
	$vars['id'] = $item->query['id'];

	return $vars;
}