<?php
/**
 * @package     Birthdays
 * @subpackage	com_birthdays
 * @copyright   Copyright (C) MakeSoft, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controller');

/**
 * Birthdays Component Controller
 *
 * @package		Birthdays
 * @subpackage	com_birthdays
 */
class BirthdaysController extends JController
{
	/**
	 * @var		string	The default view.
	 * @since	2.5
	 */
	protected $default_view = 'birthdays';

	/**
	 * Method to display a view.
	 *
	 * @param	boolean			If true, the view output will be cached
	 * @param	array			An array of safe url parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
	 *
	 * @return	JController		This object to support chaining.
	 * @since	2.5
	 */
	public function display($cachable = false, $urlparams = false)
	{
		require_once JPATH_COMPONENT.'/helpers/birthdays.php';

		// Load the submenu.
		BirthdaysHelper::addSubmenu(JRequest::getCmd('view', 'birthdays'));

		$view	= JRequest::getCmd('view', 'birthdays');
		$layout = JRequest::getCmd('layout', 'default');
		$id		= JRequest::getInt('id');

		parent::display();

		return $this;
	}
}
