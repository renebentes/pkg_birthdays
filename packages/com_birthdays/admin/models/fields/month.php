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

JFormHelper::loadFieldClass('predefinedList');

/**
 * Month Field class for the Birthdays.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_birthdays
 * @since       2.0.0
 */
class JFormFieldMonth extends JFormFieldPredefinedList
{
  /**
   * The form field type.
   *
   * @var    string
   * @since  2.0.0
   */
  public $type = 'Month';

  /**
   * Available predefined options
   *
   * @var  array
   * @since  2.0.0
   */
  protected $predefinedOptions = array(
    '1'  => 'JANUARY',
    '2'  => 'FEBRUARY',
    '3'  => 'MARCH',
    '4'  => 'APRIL',
    '5'  => 'MAY',
    '6'  => 'JUNE',
    '7'  => 'JULY',
    '8'  => 'AUGUST',
    '9'  => 'SEPTEMBER',
    '10' => 'OCTOBER',
    '11' => 'NOVEMBER',
    '12' => 'DECEMBER'
  );
}