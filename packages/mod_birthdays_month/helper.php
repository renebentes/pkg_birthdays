<?php
/**
 * @package     Birthdays Month
 * @subpackage  mod_birthdays_month
 * @copyright   Copyright (C) 2013 Makesoft, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

/**
 * Birthdays Month module helper.
 *
 * @package     Birthdays Month
 * @subpackage  mod_birthdays_month
 * @since       2.5
 */
abstract class modBirthdaysMonthHelper
{
	/**
	 * Get a list of the birthdays items.
	 *
	 * @param   JRegistry  &$params  The module options.
	 *
	 * @return  array
	 *
	 * @since   2.5
	 */
	public static function getList(&$params)
	{
		// Initialiase variables.
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		// Prepare query.
		$query->select('a.id, a.nickname, a.grade, a.birthdate');
		$query->from('#__birthdays AS a');
		$query->where('a.published = 1 AND MONTH(a.birthdate) = ' . (int)JFactory::getDate()->format('m'));
		$query->order('a.birthdate ASC');

		// Inject the query and load the items.
		$db->setQuery($query);
		$items = $db->loadObjectList();

		// Check for a database error.
		if ($db->getErrorNum())
		{
			JError::raiseWarning(500, $db->getErrorMsg());
			return null;
		}

		return $items;
	}
}
