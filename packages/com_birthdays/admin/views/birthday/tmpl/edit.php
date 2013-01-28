<?php
/**
 * @package     Birthdays
 * @subpackage	com_birthdays
 * @copyright   Copyright (C) MakeSoft, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
$canDo = BirthdaysHelper::getActions();
?>
<form action="<?php echo JRoute::_('index.php?option=com_birthdays&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="birthday-form" class="form-validate">
	<div class="width-60 fltlft">
		<fieldset class="adminform">
			<legend><?php echo empty($this->item->id) ? JText::_('COM_BIRTHDAYS_BIRTHDAY_ADD') : JText::sprintf('COM_BIRTHDAYS_BIRTHDAY_EDIT', $this->item->id); ?></legend>
			<ul class="adminformlist">
				<?php if ($this->item->id): ?>
					<li><?php echo $this->form->getLabel('id'); ?>
					<?php echo $this->form->getInput('id'); ?></li>
				<?php endif ?>

				<li><?php echo $this->form->getLabel('name'); ?>
				<?php echo $this->form->getInput('name'); ?></li>
				<li><?php echo $this->form->getLabel('grade'); ?>
				<?php echo $this->form->getInput('grade'); ?></li>
				<li><?php echo $this->form->getLabel('nickname'); ?>
				<?php echo $this->form->getInput('nickname'); ?></li>
				<li><?php echo $this->form->getLabel('picture'); ?>
				<?php echo $this->form->getInput('picture'); ?></li>
				<li><?php echo $this->form->getLabel('birthdate'); ?>
				<?php echo $this->form->getInput('birthdate'); ?></li>
				<li><?php echo $this->form->getLabel('alias'); ?>
				<?php echo $this->form->getInput('alias'); ?></li>

				<?php if ($canDo->get('core.edit.state')) : ?>
					<li><?php echo $this->form->getLabel('published'); ?>
					<?php echo $this->form->getInput('published'); ?></li>
				<?php endif; ?>

				<li><?php echo $this->form->getLabel('language'); ?>
				<?php echo $this->form->getInput('language'); ?></li>
				<li><?php echo $this->form->getLabel('access'); ?>
				<?php echo $this->form->getInput('access'); ?></li>
				<li><?php echo $this->form->getLabel('ordering'); ?>
				<?php echo $this->form->getInput('ordering'); ?></li>
			</ul>
		</fieldset>
	</div>
	<div class="width-40 fltrt">
		<?php echo $this->loadTemplate('publish'); ?>
	</div>
	<div>
		<input type="hidden" name="task" value="" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
	<div class="clr"></div>
</form>
