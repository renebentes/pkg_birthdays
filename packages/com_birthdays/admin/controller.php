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
 * Birthdays Component Controller
 *
 * @package     Jooomla.Administrator
 * @subpackage  com_birthdays
 * @since       0.0.1
 */
class BirthdaysController extends JControllerLegacy
{
	/**
	 * @var		string	The default view.
	 * @since	0.0.1
	 */
	protected $default_view = 'birthdays';

	/**
	 * Method to display a view.
	 *
	 * @param   boolean  $cachable   If true, the view output will be cached.
	 * @param   array    $urlparams  An array of safe url parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
	 *
	 * @return  JController  This object to support chaining.
   *
	 * @since   0.0.1
	 */
	public function display($cachable = false, $urlparams = array())
	{
		$view   = $this->input->get('view', $this->default_view);
		$layout = $this->input->get('layout', 'default', 'string');
		$id     = $this->input->getInt('id');

		// Check for edit form.
		if ($view == 'birthday' && $layout == 'edit' && !$this->checkEditId('com_birthdays.edit.birthday', $id))
		{
			// Somehow the person just went to the form - we do not allow that.
			$this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_UNHELD_ID', $id));
			$this->setMessage($this->getError(), 'error');
			$this->setRedirect(JRoute::_('index.php?option=com_birthdays&view=birthdays', false));
			return false;
		}

		parent::display();

		return $this;
	}
}