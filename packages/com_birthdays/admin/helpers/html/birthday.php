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
 * Utility class working with Birthdays.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_birthdays
 * @since       0.0.1
 */
abstract class JHtmlBirthday
{
  /**
   * Displays a batch widget for moving or copying items.
   *
   * @param   string  $extension  The extension.
   *
   * @return  string  The necessary HTML for the widget.
   *
   * @since   2.0.0
   */
  public static function item($extension)
  {
    // Create the copy/move options.
    $options = array(
      JHtml::_('select.option', 'c', JText::_('JLIB_HTML_BATCH_COPY')),
      JHtml::_('select.option', 'm', JText::_('JLIB_HTML_BATCH_MOVE'))
    );
    // Create the batch selector to move or copy.
    $lines = array('<div id="batch-move-copy" class="control-group radio">',
      JHtml::_('select.radiolist', $options, 'batch[move_copy]', '', 'value', 'text', 'm'), '</div><hr />');
    return implode("\n", $lines);
  }
}