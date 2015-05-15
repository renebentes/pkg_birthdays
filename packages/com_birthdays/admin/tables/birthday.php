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

// no direct access
defined('_JEXEC') or die;

/**
 * Birthday Table class.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_birthdays
 * @since       0.0.1
 */
class BirthdaysTableBirthday extends JTable
{
	/**
	 * Constructor.
	 *
	 * @param JDatabaseDriver  $db  A database connector object.
	 *
	 * @since 0.0.1
	 */
	public function __construct(JDatabaseDriver $db)
	{
		parent::__construct('#__birthdays', 'id', $db);
	}

	/**
	 * Overloaded check method to ensure data integrity.
	 *
	 * @return boolean True on success, false on failure.
	 *
	 * @see    JTable::check()
	 * @since  0.0.1
	 */
	public function check()
	{
		// Check for valid name.
		if (trim($this->name) == '')
		{
			$this->setError(JText::_('COM_BIRTHDAYS_ERROR_TABLE_NAME'));

			return false;
		}

		// Set alias.
		if (trim($this->alias) == '')
		{
			$this->alias = $this->name;
		}

		$this->alias = JApplication::stringURLSafe($this->alias);
		if (trim(str_replace('-', '', $this->alias)) == '')
		{
			$this->alias = JFactory::getDate()->format('Y-m-d-H-i-s');
		}

		// Check the publish down date is not earlier than publish up.
		if ($this->publish_down > $this->_db->getNullDate() && $this->publish_down < $this->publish_up)
		{
			// Swap the dates.
			$temp               = $this->publish_up;
			$this->publish_up   = $this->publish_down;
			$this->publish_down = $temp;
		}

		return true;
	}

	/**
	 * Overload the store method for the Birthdays table.
	 *
	 * @param  boolean $updateNulls Toggle whether null values should be updated.
	 *
	 * @return boolean True on success, false on failure.
	 *
	 * @since  0.0.1
	 */
	public function store($updateNulls = false)
	{
		// Initialiase variables.
		$date = JFactory::getDate();
		$user = JFactory::getUser();

		if ($this->id)
		{
			// Existing item.
			$this->modified    = $date->toSql();
			$this->modified_by = $user->get('id');
		}
		else
		{
			// New birthday. A birthday created and created_by field can be set by the user,
			// so we do not touch either of these if they are set.
			if (!(int) $this->created)
			{
				$this->created = $date->toSql();
			}
			if (empty($this->created_by))
			{
				$this->created_by = $user->get('id');
			}
		}

		// Set publish_up to null date if not set
		if (!$this->publish_up)
		{
			$this->publish_up = $this->_db->getNullDate();
		}

		// Set publish_down to null date if not set
		if (!$this->publish_down)
		{
			$this->publish_down = $this->_db->getNullDate();
		}

		$table = JTable::getInstance('Birthday', 'BirthdaysTable');

		// Verify that the name is unique
		if (($table->load(array('name' => $this->name, 'grade' => $this->grade, 'nickname' => $this->nickname))) && ($table->id != $this->id || $this->id == 0))
		{
			$this->setError(JText::_('COM_BIRTHDAYS_ERROR_TABLE_UNIQUE_BIRTHDAY'));
			return false;
		}

		// Verify that the alias is unique.
		if ($table->load(array('alias' => $this->alias)) && ($table->id != $this->id || $this->id == 0))
		{
			$this->setError(JText::_('COM_BIRTHDAYS_ERROR_TABLE_UNIQUE_ALIAS'));
			return false;
		}

		return parent::store($updateNulls);
	}

	/**
	 * Method to set the publishing state for a row or list of rows in the database
	 * table. The method respects checked out rows by other users and will attempt
	 * to checkin rows that it can after adjustments are made.
	 *
	 * @param  mixed   $pks    An optional array of primary key values to update. If not
	 *                          set the instance property value is used.
	 * @param  integer $state  The publishing state. eg. [0 = unpublished, 1 = published]
	 * @param  integer $userId The user id of the user performing the operation.
	 *
	 * @return boolean True on success.
	 *
	 * @since  0.0.1
	 */
	public function publish($pks = null, $state = 1, $userId = 0)
	{
		// Initialiase variables.
		$k = $this->_tbl_key;

		// Sanitize input.
		JArrayHelper::toInteger($pks);
		$userId = (int) $userId;
		$state  = (int) $state;

		// If there are no primary keys set check to see if the instance key is set.
		if (empty($pks))
		{
			if ($this->$k)
			{
				$pks = array($this->$k);
			}
			// Nothing to set publishing state on, return false.
			else
			{
				$this->setError(JText::_('JLIB_DATABASE_ERROR_NO_ROWS_SELECTED'));
				return false;
			}
		}

		// Build the WHERE clause for the primary keys.
		$where = $k . '=' . implode(' OR ' . $k . '=', $pks);

		// Determine if there is checkin support for the table.
		if (!property_exists($this, 'checked_out') || !property_exists($this, 'checked_out_time'))
		{
			$checkin = '';
		}
		else
		{
			$checkin = ' AND (checked_out = 0 OR checked_out = ' . (int) $userId . ')';
		}

		// Get the JDatabaseQuery object.
		$query = $this->_db->getQuery(true);

		// Update the publishing state for rows with the given primary keys.
		$query->update($this->_db->quoteName($this->_tbl))
			->set($this->_db->quoteName('state') . ' = ' . (int) $state)
			->where('(' . $where . ')' . $checkin);
		$this->_db->setQuery($query);

		try
		{
			$this->_db->execute();
		}
		catch (RuntimeException $e)
		{
			$this->setError($e->getMessage());
			return false;
		}

		// If checkin is supported and all rows were adjusted, check them in.
		if ($checkin && (count($pks) == $this->_db->getAffectedRows()))
		{
			// Checkin the rows.
			foreach ($pks as $pk)
			{
				$this->checkin($pk);
			}
		}

		// If the JTable instance value is in the list of primary keys that were set, set the instance.
		if (in_array($this->$k, $pks))
		{
			$this->state = $state;
		}

		$this->setError('');
		return true;
	}
}