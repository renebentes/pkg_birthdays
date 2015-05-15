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

JHtml::_('behavior.tabstate');

// Defines variables
$app = JFactory::getApplication();

// Access check.
if (!JFactory::getUser()->authorise('core.manage', 'com_birthdays'))
{
  $app->enqueueMessage(JText::_('JERROR_ALERTNOAUTHOR'), 'error');
  return false;
}

// Load helper
JLoader::register('BirthdaysHelper', __DIR__ . '/helpers/birthdays.php');

// Get an instance of the controller prefixed by Birthdays
$controller = JControllerLegacy::getInstance('Birthdays');

// Perform the Request task
$controller->execute($app->input->get('task'));

// Redirect if set by the controller
$controller->redirect();