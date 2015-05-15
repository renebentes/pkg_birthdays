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
 * View class for a list of Birthdays.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_birthdays
 * @since       0.0.1
 */
class BirthdaysViewBirthdays extends JViewLegacy
{
	/**
	 * Method to display the view.
	 *
	 * @param   string  $tpl  A template file to load. [optional]
	 *
	 * @return  mixed  A string if successful, otherwise a JError object.
	 *
	 * @since   0.0.1
	 */
	public function display($tpl = null)
	{
		// Initialise variables.
		$this->items         = $this->get('Items');
		$this->pagination    = $this->get('Pagination');
		$this->state         = $this->get('State');
		$this->filterForm    = $this->get('FilterForm');
		$this->activeFilters = $this->get('ActiveFilters');

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JFactory::getApplication()->enqueueMessage(implode("\n", $errors), 'error');
			return false;
		}

		// We do not need toolbar in the modal window.
		if ($this->getLayout() !== 'modal')
		{
			// Load the submenu.
			BirthdaysHelper::addSubmenu('administrator');
			$this->addToolbar();
			$this->sidebar = JHtmlSidebar::render();
		}

		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @return  void
	 *
	 * @since   0.0.1
	 */
	protected function addToolbar()
	{
		// Initialise Variables
		$state = $this->get('State');
		$canDo = BirthdaysHelper::getActions('com_birthdays');
		$user  = JFactory::getUser();

		// Get the toolbar object instance.
		$bar = JToolBar::getInstance('toolbar');

		JToolBarHelper::title(JText::_('COM_BIRTHDAYS_MANAGER_BIRTHDAYS_TITLE'), 'calendar birthdays');

		if ($canDo->get('core.create'))
		{
			JToolbarHelper::addNew('birthday.add');
		}

		if (($canDo->get('core.edit')) || ($canDo->get('core.edit.own')))
		{
			JToolbarHelper::editList('birthday.edit');
		}

		if ($canDo->get('core.edit.state'))
		{
			JToolbarHelper::publish('birthdays.publish', 'JTOOLBAR_PUBLISH', true);
			JToolbarHelper::unpublish('birthdays.unpublish', 'JTOOLBAR_UNPUBLISH', true);
			JToolbarHelper::archiveList('birthdays.archive');
			JToolbarHelper::checkin('birthdays.checkin');
		}

		// Add a batch button.
		if ($user->authorise('core.create', 'com_birthdays') && $user->authorise('core.edit', 'com_birthdays') && $user->authorise('core.edit.state', 'com_birthdays'))
		{
			// Load the modal bootstrap script.
			JHtml::_('bootstrap.modal', 'collapseModal');

			// Instantiate a new JLayoutFile instance and render the batch button.
			$layout = new JLayoutFile('joomla.toolbar.batch');
			$title  = JText::_('JTOOLBAR_BATCH');
			$dhtml  = $layout->render(array('title' => $title));
			$bar->appendButton('Custom', $dhtml, 'batch');
		}

		if ($state->get('filter.state') == -2 && $canDo->get('core.delete'))
		{
			JToolbarHelper::deleteList('', 'birthdays.delete', 'JTOOLBAR_EMPTY_TRASH');
		}
		elseif ($canDo->get('core.edit.state'))
		{
			JToolbarHelper::trash('birthdays.trash');
		}

		if ($canDo->get('core.admin'))
		{
			JToolBarHelper::preferences('com_birthdays');
		}

		JToolBarHelper::help('birthdays', $com = true);
	}
}