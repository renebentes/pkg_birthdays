<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_birthdays
 * @since       2.0.0
 *
 * @author      Rene Bentes Pinto <renebentes@yahoo.com.br>
 * @link        http://renebentes.github.io
 * @copyright   Copyright (C) 2012 - 2015 Rene Bentes Pinto, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

// No direct access.
defined('_JEXEC') or die('Restricted access!');

// JLayout for exclusive fields on com_birthdays
$form   = $displayData->getForm();
$fields = $displayData->get('fields') ?: array(
  'grade',
  'nickname',
  'picture',
  'birthdate'
);

$hiddenFields = $displayData->get('hidden_fields') ?: array();

$html   = array();
$html[] = '<fieldset class="form-vertical">';

foreach ($fields as $field)
{
  $field = is_array($field) ? $field : array($field);

  foreach ($field as $f)
  {
    if ($form->getField($f))
    {
      if (in_array($f, $hiddenFields))
      {
        $form->setFieldAttribute($f, 'type', 'hidden');
      }

      $html[] = $form->renderField($f);
      break;
    }
  }
}

$html[] = '</fieldset>';

echo implode('', $html);