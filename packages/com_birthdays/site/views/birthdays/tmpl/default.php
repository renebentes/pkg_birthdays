<?php
/**
 * @package     Birthdays
 * @subpackage  com_birthdays
 * @copyright   Copyright (C) 2013 Makesoft, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/birthdays.php';
?>

<div class="birthdays-list<?php echo $this->pageclass_sfx; ?>">
	<?php if ($this->params->get('show_page_heading', 1)): ?>
	<h1>
		<?php echo $this->escape($this->params->get('page_heading')); ?>
	</h1>
	<?php endif; ?>

	<?php if (empty($this->items)) : ?>
	<p><?php echo JText::_('COM_BIRTHDAYS_NO_BIRTHDAYS'); ?></p>
	<?php else : ?>
	<form action="<?php echo htmlspecialchars(JFactory::getURI()->toString()); ?>" method="post" name="adminForm" id="adminForm">
		<?php if ($this->params->get('show_pagination_limit') || $this->params->get('filter_field') != 0) : ?>
		<fieldset class="alert alert-info">
			<?php if($this->params->get('filter_field') == 1) : ?>
			<legend><?php echo JText::_('JGLOBAL_FILTER_LABEL'); ?></legend>
			<?php endif; ?>

			<?php if ($this->params->get('show_pagination_limit')) : ?>
			<div class="pull-right">
				<label><?php echo JText::_('JGLOBAL_DISPLAY_NUM'); ?></label>
				<?php echo $this->pagination->getLimitBox(); ?>
			</div>
			<?php endif; ?>
			<input type="hidden" name="filter_order" value="" />
			<input type="hidden" name="filter_order_Dir" value="" />
			<input type="hidden" name="limitstart" value="" />
		</fieldset>
		<?php endif; ?>
	</form>

		<table class="table table-bordered table-striped table-condensed">
			<?php if ($this->params->get('show_headings')==1) : ?>
			<thead>
				<tr>
					<th><?php echo JText::_('COM_BIRTHDAYS_HEADING_PICTURE'); ?></th>
					<th><?php echo JText::_('COM_BIRTHDAYS_HEADING_NAME'); ?></th>
					<th><?php echo JText::_('COM_BIRTHDAYS_HEADING_BIRTHDATE'); ?></th>
					<?php if ($this->params->get('show_link_hits')) : ?>
					<th><?php echo JText::_('JGLOBAL_HITS'); ?></th>
					<?php endif; ?>
				</tr>
			</thead>
			<?php endif; ?>
			<tbody>
			<?php foreach ($this->items as $item): ?>
				<tr>
					<td>
						<?php if (empty($item->picture))
						{
							$item->picture = 'images/birthdays/noimage.jpg';
						}
						$file = BirthdaysHelper::getImage(JPATH_SITE . DS . $item->picture, '86x126', JPATH_SITE . '/cache/com_birthdays');
						?>
						<img src="<?php echo str_replace(JPATH_SITE . DS, JURI::root(), $file); ?>" alt="<?php echo JText::_('COM_BIRTHDAYS_GRADE_' . $item->grade) . ' ' . $this->escape($item->nickname);?>" title="<?php echo JText::_('COM_BIRTHDAYS_GRADE_' . $item->grade) . ' ' . $this->escape($item->nickname);?>" />
					</td>
					<td><?php echo $this->escape($item->name); ?></td>
					<td><?php echo JHtml::_('date', $item->birthdate, JText::_('COM_BIRTHDAYS_DATE_FORMAT')); ?></td>
					<?php if ($this->params->get('show_link_hits')) : ?>
					<td><?php echo $item->hits; ?></td>
					<?php endif; ?>
				</tr>
			<?php endforeach ?>
			</tbody>
		</table>
		<?php endif; ?>

		<?php if ($this->params->get('show_pagination')): ?>
		<nav class="pagination pagination-centered">
			<?php echo $this->pagination->getPagesLinks(); ?>
		<?php if ($this->params->def('show_pagination_results', 1)): ?>
			<p class="counter muted"><?php echo $this->pagination->getPagesCounter(); ?></p>
		<?php endif; ?>
		</nav>
	<?php endif; ?>
</div>
