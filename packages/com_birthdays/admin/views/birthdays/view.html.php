<?php
/**
 * @package     Birthdays
 * @subpackage	com_birthdays
 * @copyright   Copyright (C) MakeSoft, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * View class for a list of birthdays.
 *
 * @package     Birthdays
 * @subpackage  com_birthdays
 * @since       2.5
 */
class BirthdaysViewBirthdays extends JView
{
	protected $items;
	protected $pagination;
	protected $state;

	/**
	 * Method to display the view.
	 *
	 * @param   string  $tpl  A template file to load. [optional]
	 *
	 * @return  mixed  A string if successful, otherwise a JError object.
	 *
	 * @since   1.6
	 */
	public function display($tpl = null)
	{
		// Initialise variables.
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');
		$this->state		= $this->get('State');

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		// Get document
		$doc = JFactory::getDocument();
		$doc->setTitle(JText::_('COM_BIRTHDAYS_BIRTHDAYS_TITLE'));
		$doc->addStyleSheet(JURI::root() . 'media/com_birthdays/css/backend.css');

		$this->addToolbar();
		// Include the component HTML helpers.
		JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');

		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @return  void
	 *
	 * @since   1.6
	 */
	protected function addToolbar()
	{
		require_once JPATH_COMPONENT . '/helpers/birthdays.php';
		$user = JFactory::getUser();
		$canDo = BirthdaysHelper::getActions();

		JToolBarHelper::title(JText::_('COM_BIRTHDAYS_MANAGER_BIRTHDAYS'), 'birthday.png');

		if ($user->authorise('core.create', 'com_birthdays') && $canDo->get('core.create'))
		{
			JToolBarHelper::addNew('birthday.add');
		}

		if (($canDo->get('core.edit')))
		{
			JToolBarHelper::editList('birthday.edit');
		}

		if ($canDo->get('core.edit.state'))
		{
			if ($this->state->get('filter.published') != 2)
			{
				JToolBarHelper::divider();
				JToolBarHelper::publish('birthdays.publish', 'JTOOLBAR_PUBLISH', true);
				JToolBarHelper::unpublish('birthdays.unpublish', 'JTOOLBAR_UNPUBLISH', true);
			}

			if ($this->state->get('filter.published') != -1)
			{
				JToolBarHelper::divider();
				if ($this->state->get('filter.published') != 2)
				{
					JToolBarHelper::archiveList('birthdays.archive');
				}
				elseif ($this->state->get('filter.published') == 2)
				{
					JToolBarHelper::unarchiveList('birthdays.publish');
				}
			}
		}

		if ($canDo->get('core.edit.state'))
		{
			JToolBarHelper::checkin('birthdays.checkin');
		}

		if ($this->state->get('filter.published') == -2 && $canDo->get('core.delete'))
		{
			JToolBarHelper::deleteList('', 'birthdays.delete', 'JTOOLBAR_EMPTY_TRASH');
			JToolBarHelper::divider();
		}
		elseif ($canDo->get('core.edit.state'))
		{
			JToolBarHelper::trash('birthdays.trash');
			JToolBarHelper::divider();
		}

		if ($canDo->get('core.admin'))
		{
			JToolBarHelper::preferences('com_birthdays');
			JToolBarHelper::divider();
		}
		JToolBarHelper::help('birthdays', $com = true);
	}
}
