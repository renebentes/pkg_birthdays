<?php
/**
 * @package     Birthdays
 * @subpackage	com_birthdays
 * @copyright   Copyright (C) Makesoft, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

// Access check.
if (!JFactory::getUser()->authorise('core.manage', 'com_birthdays'))
{
	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}

// Include dependencies
jimport('joomla.application.component.controller');

// Execute the task.
$controller = JController::getInstance('Birthdays');
$controller->execute(JRequest::getCmd('task'));
$controller->redirect();