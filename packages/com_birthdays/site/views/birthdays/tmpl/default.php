<?php
/**
 * @package     Birthdays
 * @subpackage  com_birthdays
 * @copyright   Copyright (C) 2013 Makesoft, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

<div class="birthdays-list<?php echo $this->pageclass_sfx; ?>">
	<?php if ($this->params->get('show_page_heading', 1)): ?>
	<h1>
		<?php echo $this->escape($this->params->get('page_heading')); ?>
	</h1>
	<?php endif; ?>

	<table class="table table-bordered table-striped">
		<thead>
			<tr>
				<th class="title"><?php echo JText::_('COM_BIRTHDAYS_NAME_LABEL'); ?></th>
				<th><?php echo JText::_('COM_BIRTHDAYS_NAME_DESCRIPTION'); ?></th>
			</tr>
		</thead>
		<tbody>
	<?php foreach ($this->items as $item): ?>
			<tr>
				<td><?php echo $item->name ?></td>
			</tr>
	<?php endforeach ?>
		</tbody>
	</table>

	<?php if ($this->params->get('show_pagination')): ?>
	<div class="pagination">
	<?php if ($this->params->def('show_pagination_results', 1)): ?>
		<p class="counter">
			<?php echo $this->pagination->getPagesCounter(); ?>
		</p>
	<?php endif; ?>
	<?php echo $this->pagination->getPagesLinks(); ?>
	</div>
	<?php endif; ?>
</div>
