<?php
/**
 * @package     Birthdays
 * @subpackage  com_birthdays
 * @copyright   Copyright (C) 2013 Rene Bentes Pinto, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

/**
 * Birthdays Component Birthdays Model
 *
 * @package     Birthdays
 * @subpackage  com_birthdays
 * @since       2.5
 */
class BirthdaysModelBirthdays extends JModelList
{
	/**
	 * Constructor.
	 *
	 * @param   array  $config  An optional associative array of configuration settings.
	 *
	 * @see     JController
	 * @since   2.5
	 */
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				'id', 'a.id',
				'name', 'a.name',
				'published', 'a.published',
				'ordering', 'a.ordering',
			);
		}

		parent::__construct($config);
	}

	/**
	 * Method to get a list of items.
	 *
	 * @return  mixed  An array of objects on success, false on failure.
	 *
	 * @since   2.5
	 */
	public function getItems()
	{
		// Invoke the parent getItems method to get the main list
		$items = parent::getItems();

		return $items;
	}

	/**
	 * Method to build an SQL query to load the list data.
	 *
	 * @return  string  An SQL query
	 *
	 * @since   2.5
	 */
	protected function getListQuery()
	{
		$user   = JFactory::getUser();

		// Create a new query object.
		$db     = $this->getDbo();
		$query  = $db->getQuery(true);

		// Select required fields from the table.
		$query->select($this->getState('list.select', 'a.*'));
		$query->from($db->quoteName('#__birthdays') . ' AS a');

		// Filter by published state
		$published = $this->getState('filter.published');

		if (is_numeric($published))
		{
			$query->where('a.published = ' . (int) $published);
		}

		// Filter by search in name.
		$search = $this->getState('filter.search');
		if (!empty($search)) {
			if (stripos($search, 'id:') === 0) {
				$query->where('a.id = '.(int) substr($search, 3));
			}
			else {
				$search = $db->Quote('%'.$db->escape($search, true).'%');
				$query->where('(a.name LIKE '. $search . ' OR a.alias LIKE ' . $search . ' OR a.nickname LIKE ' . $search . ')');
			}
		}

		// Add the list ordering clause.
		$query->order($db->escape($this->getState('list.ordering', 'a.name')) . ' ' . $db->escape($this->getState('list.direction', 'ASC')));

		return $query;
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @param   string  $ordering   An optional ordering field.
	 * @param   string  $direction  An optional direction (asc|desc).
	 *
	 * @return  void
	 *
	 * @since   2.5
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		// Initialise variables.
		$app    = JFactory::getApplication();
		$params = $app->getParams();

		// List state information
		$search = $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
		$this->setState('filter.search', $search);

		$limitstart = JRequest::getUInt('limitstart', 0);
		$this->setState('list.start', $limitstart);

		$limit = $app->getUserStateFromRequest($this->context.'.list.limit', 'limit', $params->get('display_num'), 'uint');
		$this->setState('list.limit', $limit);

		$orderCol = JRequest::getCmd('filter_order', 'name');

		if (!in_array($orderCol, $this->filter_fields))
		{
			$orderCol = 'name';
		}

		$this->setState('list.ordering', $orderCol);

		$listOrder = JRequest::getCmd('filter_order_Dir', 'ASC');

		if (!in_array(strtoupper($listOrder), array('ASC', 'DESC', '')))
		{
			$listOrder = 'ASC';
		}

		$this->setState('list.direction', $listOrder);

		// Get the user object.
		$user = JFactory::getUser();

		if ((!$user->authorise('core.edit.state', 'com_birthdays')) && (!$user->authorise('core.edit', 'com_birthdays')))
		{
			// Limit to published for people who can't edit or edit.state.
			$this->setState('filter.published', 1);
		}

		// Load the parameters.
		$this->setState('params', $params);
	}
}
