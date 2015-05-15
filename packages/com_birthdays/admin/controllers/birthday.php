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
 * Birthday controller class.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_birthdays
 * @since       0.0.1
 */
class BirthdaysControllerBirthday extends JControllerForm
{
  /**
   * Method to run batch operations.
   *
   * @param  object  $model The model.
   *
   * @return boolean True if successful, false otherwise and internal error is set.
   *
   * @since  2.0.0
   */
  public function batch($model = null)
  {
    // Check for request forgeries.
    JSession::checkToken() or die(JText::_('JINVALID_TOKEN'));

    // Set the model.
    $model = $this->getModel('Birthday', '', array());

    // Preset the redirect.
    $this->setRedirect(JRoute::_('index.php?option=com_birthdays&view=birthdays' . $this->getRedirectToListAppend(), false));

    return parent::batch($model);
  }
}