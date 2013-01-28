<?php
/**
 * @package     Birthdays
 * @subpackage	com_birthdays
 * @copyright   Copyright (C) MakeSoft, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

?>

<fieldset class="panelform">
	<legend><?php echo JText::_('COM_BIRTHDAYS_FIELDSET_PUBLISH'); ?></legend>
	<ul class="adminformlist">
		<?php foreach($this->form->getFieldset('publish') as $field): ?>
			<li><?php echo $field->label; ?>
				<?php echo $field->input; ?></li>
		<?php endforeach; ?>
	</ul>
</fieldset>