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

/**
 * View class for edit a Birthday.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_birthdays
 * @since       0.0.1
 */
class BirthdaysViewBirthday extends JViewLegacy
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
		$this->form  = $this->get('Form');
		$this->item  = $this->get('Item');
		$this->state = $this->get('State');
		$this->canDo = BirthdaysHelper::getActions('com_birthdays');

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JFactory::getApplication()->enqueueMessage(implode("\n", $errors), 'error');
			return false;
		}

		// We do not need toolbar in the modal window.
		if ($this->getLayout() !== 'modal')
		{
			$this->form->setFieldAttribute('language', 'readonly', 'true');
		}

		$this->addToolbar();

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
		JFactory::getApplication()->input->set('hidemainmenu', true);

		// Initialise Variables
		$user       = JFactory::getUser();
		$userId     = $user->get('id');
		$isNew      = ($this->item->id == 0);
		$checkedOut = !($this->item->checked_out == 0 || $this->item->checked_out == $userId);

		// Since we do not track these assets at the item level.
		$canDo = $this->canDo;

		JToolbarHelper::title(JText::_('COM_BIRTHDAYS_MANAGER_' . ($checkedOut ? 'VIEW_BIRTHDAY' : ($isNew ? 'ADD_BIRTHDAY' : 'EDIT_BIRTHDAY'))), 'pencil-2 birthday-add');

		// Built the actions for new and existing records.
		// For new records, check the create permission.
		if ($isNew)
		{
			JToolbarHelper::apply('birthday.apply');
			JToolbarHelper::save('birthday.save');
			JToolbarHelper::save2new('birthday.save2new');
			JToolbarHelper::cancel('birthday.cancel');
		}
		else
		{
			// Can not save the record if it's checked out.
			if (!$checkedOut)
			{
				// Since it's an existing record, check the edit permission, or fall back to edit own if the owner.
				if ($canDo->get('core.edit') || ($canDo->get('core.edit.own') && $this->item->created_by == $userId))
				{
					JToolbarHelper::apply('birthday.apply');
					JToolbarHelper::save('birthday.save');

					// We can save this record, but check the create permission to see if we can return to make a new one.
					if ($canDo->get('core.create'))
					{
						JToolbarHelper::save2new('birthday.save2new');
					}
				}
			}

			JToolbarHelper::cancel('birthday.cancel', 'JTOOLBAR_CLOSE');
		}

		JToolBarHelper::help('birthday', $com = true);
	}
}