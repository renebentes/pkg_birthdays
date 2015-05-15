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

JFormHelper::loadFieldClass('ordering');

/**
 * Ordering Field class for the Birthdays.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_birthdays
 * @since       0.0.1
 */
class JFormFieldOrdering extends JFormFieldOrdering
{
  /**
   * The form field type.
   *
   * @var    string
   * @since  0.0.1
   */
  public $type = 'Ordering';

  /**
   * Builds the query for the ordering list.
   *
   * @return JDatabaseQuery The query for the ordering form field.
   *
   * @since  0.0.1
   */
  protected function getQuery()
  {
    // Initialiase variables.
    $db    = JFactory::getDbo();
    $query = $db->getQuery(true);

    // Create the base select statement.
    $query->select(array($db->quoteName('ordering', 'value'), $db->quoteName('name', 'text')))
      ->from($db->quoteName('#__birthdays'))
      ->where($db->quoteName('state') . ' >= 0')
      ->order($db->quoteName('ordering'));
    return $query;
  }
}