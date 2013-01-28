<?php
/**
 * @package     Birthdays Month
 * @subpackage  mod_birthdays_month
 * @copyright   Copyright (C) 2013 Makesoft, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

// Load the products component language file.
$lang = JFactory::getLanguage();
$lang->load('com_birthdays', JPATH_ADMINISTRATOR . '/components/com_birthdays');
?>
<marquee width="250" height="100" onmouseout="start()" onmouseover="stop()" direction="up" truespeed="1" scrolldelay="200" behavior="scrool"<?php echo $moduleclass_sfx ? 'class="' . $moduleclass_sfx .'"' : ''; ?>>
<p>
<?php foreach ($list as $i => $item) :
	echo JHtml::_('date', $item->birthdate, JText::_('MOD_BIRTHDAYS_MONTH_DATE_FORMAT')) . ' - ' . JText::_('COM_BIRTHDAYS_GRADE_' . $item->grade) . ' ' . $item->nickname . '<br />';
endforeach;?>
</p>
</marquee>