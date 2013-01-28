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
 * View to edit a birthdays.
 *
 * @package		Birthdays
 * @subpackage	com_birthdays
 * @since		2.5
 */
class BirthdaysViewBirthday extends JView
{
	protected $form;
	protected $item;
	protected $state;

	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
		// Initialiase variables.
		$this->form  = $this->get('Form');
		$this->item  = $this->get('Item');
		$this->state = $this->get('State');

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		// Get document
		$doc = JFactory::getDocument();
		$doc->setTitle(JText::_('COM_BIRTHDAYS_BIRTHDAY_TITLE'));
		$doc->addStyleSheet(JURI::root() . 'media/com_birthdays/css/backend.css');

		$this->addToolbar();

		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @since	2.5
	 */
	protected function addToolbar()
	{
		JRequest::setVar('hidemainmenu', true);

		$user       = JFactory::getUser();
		$userId     = $user->get('id');
		$isNew      = ($this->item->id == 0);
		$checkedOut = !($this->item->checked_out == 0 || $this->item->checked_out == $userId);
		$canDo		= BirthdaysHelper::getActions();

		JToolBarHelper::title($isNew ? JText::_('COM_BIRTHDAYS_BIRTHDAY_ADD') : JText::_('COM_BIRTHDAYS_BIRTHDAY_EDIT'), 'birthday.png');

		// If not checked out, can save the item.
		if (!$checkedOut && $canDo->get('core.edit')) {
			JToolBarHelper::apply('birthday.apply');
			JToolBarHelper::save('birthday.save');

			if ($canDo->get('core.create')) {
				JToolBarHelper::save2new('birthday.save2new');
			}
		}

		// If an existing item, can save to a copy.
		if (!$isNew && $canDo->get('core.create')) {
			JToolBarHelper::save2copy('birthday.save2copy');
		}

		if (empty($this->item->id))  {
			JToolBarHelper::cancel('birthday.cancel');
		}
		else {
			JToolBarHelper::cancel('birthday.cancel', 'JTOOLBAR_CLOSE');
		}

		JToolBarHelper::divider();
		JToolBarHelper::help('birthday', $com = true);
	}
}