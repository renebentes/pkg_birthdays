<?php
/**
 * @package     Birthdays
 * @subpackage	com_birthdays
 * @copyright   Copyright (C) MakeSoft, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');

/**
 * Birthday controller class.
 *
 * @package     Birthdays
 * @subpackage  com_birthdays
 * @since       2.5
 */
class BirthdaysControllerBirthday extends JControllerForm
{
	/**
	 * @var    string  The prefix to use with controller messages.
	 * @since  2.5
	 */
	protected $text_prefix = 'COM_BIRTHDAYS_BIRTHDAY';
}
