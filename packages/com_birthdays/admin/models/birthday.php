<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_birthdays
 * @since       0.0.1
 *
 * @author      Rene Bentes Pinto <renebentes@yahoo.com.br>
 * @link        http://renebentes.github.io
 * @copyright   Copyright (C) 2012 - 2015 Rene Bentes Pinto, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

// No direct access.
defined('_JEXEC') or die('Restricted access!');

use Joomla\Registry\Registry;

JLoader::register('BirthdaysHelper', JPATH_ADMINISTRATOR . '/components/com_birthdays/helpers/birthdays.php');

/**
 * Item Model for an Birthday.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_birthdays
 * @since       0.0.1
 */
class BirthdaysModelBirthday extends JModelAdmin
{
	/**
	 * Returns a Table object, always creating it.
	 *
	 * @param  type   $type   The table type to instantiate.
	 * @param  string $prefix A prefix for the table class name. Optional.
	 * @param  array  $config Configuration array for model. Optional.
	 *
	 * @return JTable A database object.
	 *
	 * @since  0.0.1
	 */
	public function getTable($type = 'Birthday', $prefix = 'BirthdaysTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	/**
	 * Method to get the record form.
	 *
	 * @param  array   $data     Data for the form.
	 * @param  boolean $loadData True if the form is to load its own data (default case), false if not.
	 *
	 * @return mixed   A JForm object on success, false on failure.
	 *
	 * @since  0.0.1
	 */
	public function getForm($data = array(), $loadData = true)
	{
		// Get the form.
		$form = $this->loadForm('com_birthdays.birthday', 'birthday', array('control' => 'jform', 'load_data' => $loadData));

		if (empty($form))
		{
			return false;
		}

		return $form;
	}

	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return mixed The data for the form.
	 *
	 * @since  0.0.1
	 */
	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$app  = JFactory::getApplication();
		$data = $app->getUserState('com_birthdays.edit.birthday.data', array());

		if (empty($data))
		{
			$data = $this->getItem();
		}

		return $data;
	}

	/**
	 * Prepare and sanitise the table data prior to saving.
	 *
	 * @param  JTable $table  A reference to a JTable object.
	 *
	 * @return void
	 *
	 * @since  0.0.1
	 */
	protected function prepareTable($table)
	{
		// Set the publish date to now.
		$db = $this->getDbo();

		if ($table->state == 1 && (int) $table->publish_up == 0)
		{
			$table->publish_up = JFactory::getDate()->toSql();
		}
		if ($table->state == 1 && intval($table->publish_down) == 0)
		{
			$table->publish_down = $db->getNullDate();
		}

		// Reorder the birthdays so the new birthday is first.
		if (empty($table->id))
		{
			$table->reorder('state >= 0');
		}

		// Increment the birthdays version number.
		$table->version++;
	}
}