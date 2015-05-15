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

// no direct access
defined('_JEXEC') or die;

// Include the component HTML helpers.
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');

// Load the behavior script.
JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');

// Initialise variables.
$user      = JFactory::getUser();
$userId    = $user->get('id');
$listDirn  = $this->escape($this->state->get('list.direction'));
$listOrder = $this->escape($this->state->get('list.ordering'));
$archived  = $this->state->get('filter.state') == 2 ? true : false;
$trashed   = $this->state->get('filter.state') == -2 ? true : false;
$saveOrder = $listOrder == 'a.ordering';

if ($saveOrder)
{
	$saveOrderingUrl = 'index.php?option=com_birthdays&amp;task=birthdays.saveOrderAjax&tmpl=component';
	JHtml::_('sortablelist.sortable', 'birthdayList', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
}

$assoc = JLanguageAssociations::isEnabled();
?>
<script type="text/javascript">
	Joomla.orderTable = function() {
		table = document.getElementById("sortTable");
		direction = document.getElementById("directionTable");
		order = table.options[table.selectedIndex].value;
		if (order != '<?php echo $listOrder; ?>') {
			dirn = 'asc';
		} else {
			dirn = direction.options[direction.selectedIndex].value;
		}
		Joomla.tableOrdering(order, dirn, '');
	};
</script>
<form action="<?php echo JRoute::_('index.php?option=com_birthdays&amp;view=birthdays'); ?>" method="post" name="adminForm" id="adminForm">
<?php if (!empty($this->sidebar)): ?>
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
<?php else: ?>
	<div id="j-main-container">
<?php endif;
		// Search tools bar.
		echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this));
	if (empty($this->items)) : ?>
		<div class="alert alert-no-items">
			<?php echo JText::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
		</div>
	<?php else: ?>
		<table class="table table-striped" id="birthdayList">
			<thead>
				<tr>
					<th width="1%" class="nowrap center hidden-phone">
						<?php echo JHtml::_('searchtools.sort', '', 'a.ordering', $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING', 'icon-menu-2'); ?>
					</th>
					<th width="1%" class="hidden-phone">
						<?php echo JHtml::_('grid.checkall'); ?>
					</th>
					<th width="1%" style="min-width: 55px;" class="nowrap center">
						<?php echo JHtml::_('searchtools.sort', 'JSTATUS', 'a.state', $listDirn, $listOrder); ?>
					</th>
					<th>
						<?php echo JHtml::_('searchtools.sort', 'COM_BIRTHDAYS_HEADING_NAME', 'a.name', $listDirn, $listOrder); ?>
					</th>
					<th width="10%" class="nowrap hidden-phone">
						<?php echo JHtml::_('searchtools.sort', 'COM_BIRTHDAYS_HEADING_BIRTHDATE', 'a.birthdate', $listDirn, $listOrder); ?>
					</th>
					<th width="10%" class="nowrap hidden-phone">
						<?php echo JHtml::_('searchtools.sort', 'JAUTHOR', 'a.created_by', $listDirn, $listOrder); ?>
					</th>
					<th width="1%" class="nowrap hidden-phone">
						<?php echo JHtml::_('searchtools.sort', 'JGRID_HEADING_ID', 'a.id', $listDirn, $listOrder); ?>
					</th>
				</tr>
			</thead>
			<tbody>
			<?php foreach ($this->items as $i => $item) :
				$ordering   = ($listOrder == 'a.ordering');
				$canCreate  = $user->authorise('core.create', 'com_birthdays');
				$canEdit    = $user->authorise('core.edit', 'com_birthdays.birthday.' . $item->id);
				$canCheckin = $user->authorise('core.manage', 'com_checkin') || $item->checked_out == $userId || $item->checked_out == 0;
				$canEditOwn = $user->authorise('core.edit.own', 'com_birthdays.birthday.' . $item->id) && $item->created_by == $userId;
				$canChange  = $user->authorise('core.edit.state', 'com_birthdays.birthday.' . $item->id) && $canCheckin;
			?>
				<tr class="row<?php echo $i % 2; ?>">
					<td class="order nowrap center hidden-phone">
					<?php
						$iconClass = '';
						if (!$canChange)
						{
							$iconClass = ' inactive';
						}
						elseif (!$saveOrder)
						{
							$iconClass = ' inactive tip-top hasTooltip" title="' . JHtml::tooltipText('JORDERINGDISABLED');
						}
					?>
						<span class="sortable-handler<?php echo $iconClass; ?>"><i class="icon-menu"></i></span>
					<?php if ($canChange && $saveOrder) : ?>
						<input type="text" style="display: none;" name="order[]" size="5" value="<?php echo $item->ordering; ?>" class="width-20 text-area-order" />
					<?php endif; ?>
					</td>
					<td class="center hidden-phone">
						<?php echo JHtml::_('grid.id', $i, $item->id); ?>
					</td>
					<td class="center">
						<div class="btn-group">
						<?php
							echo JHtml::_('jgrid.published', $item->state, $i, 'birthdays.', $canChange, 'cb', $item->publish_up, $item->publish_down);

							// Create Checked Item
							if ($item->checked_out)
							{
								echo JHtml::_('jgrid.checkedout', $i, $item->editor, $item->checked_out_time, 'birthdays.', $canCheckin);
							}

							// Create dropdown items.
							$action = $archived ? 'unarchive' : 'archive';
							JHtml::_('actionsdropdown.' . $action, 'cb' . $i, 'birthdays');
							$action = $trashed ? 'untrash' : 'trash';
							JHtml::_('actionsdropdown.' . $action, 'cb' . $i, 'birthdays');
							// Render dropdown list.
							echo JHtml::_('actionsdropdown.render', $this->escape($item->name));
						?>
						</div>
					</td>
					<td class="nowrap has-context">
						<div class="span2 hidden-phone">
						<?php if (empty($item->picture))
						{
							$item->picture = 'images/birthdays/noimage.jpg';
						}
						$file = BirthdaysHelper::getImage(JPATH_SITE . '/' . $item->picture, '86x126', JPATH_SITE . '/cache/com_birthdays');

						if ($canEdit || $canEditOwn)
						{
							$link = JRoute::_('index.php?option=com_birthdays&task=birthday.edit&id=' . (int) $item->id);
						} ?>

						<?php if (isset($link)) : ?>
							<a class="hasTooltip thumbnail" href="<?php echo $link; ?>" title="<?php echo JText::_('JACTION_EDIT'); ?>">
								<img src="<?php echo str_replace(JPATH_SITE . '/', JUri::root(), $file); ?>" alt="<?php echo JText::_('COM_BIRTHDAYS_GRADE_' . $item->grade) . ' ' . $this->escape($item->nickname);?>" title="<?php echo JText::_('COM_BIRTHDAYS_GRADE_' . $item->grade) . ' ' . $this->escape($item->nickname);?>" />
							</a>
						<?php else : ?>
							<img class="thumbnail" src="<?php echo str_replace(JPATH_SITE . '/', JUri::root(), $file); ?>" alt="<?php echo JText::_('COM_BIRTHDAYS_GRADE_' . $item->grade) . ' ' . $this->escape($item->nickname);?>" title="<?php echo JText::_('COM_BIRTHDAYS_GRADE_' . $item->grade) . ' ' . $this->escape($item->nickname);?>" />
						<?php endif ?>
						</div>
						<div class="span10 break-word">
						<?php if (isset($link)) : ?>
							<a class="hasTooltip" href="<?php echo $link; ?>" title="<?php echo JText::_('JACTION_EDIT'); ?>">
								<?php echo $this->escape($item->name); ?>
							</a>
						<?php else : ?>
							<span class="hasTooltip" title="<?php echo JText::sprintf('JFIELD_ALIAS_LABEL', $this->escape($item->alias)); ?>">
								<?php echo $this->escape($item->name); ?>
							</span>
						<?php endif; ?>
							<div class="small">
								<?php echo JText::_('COM_BIRTHDAYS_HEADING_NICKNAME') . ': ' . JText::_('COM_BIRTHDAYS_GRADE_' . $item->grade) . ' ' . $this->escape($item->nickname);?>
							</div>
						</div>
					</td>
					<td class="nowrap small">
						<?php echo JHtml::_('date', $item->birthdate, JText::_('COM_BIRTHDAYS_DATE_FORMAT')); ?>
					</td>
					<td class="small hidden-phone">
					<?php if ($item->created_by_alias) : ?>
						<a class="hasTooltip" href="<?php echo JRoute::_('index.php?option=com_users&task=user.edit&id=' . (int) $item->created_by); ?>" title="<?php echo JText::_('JAUTHOR'); ?>">
						<?php echo $this->escape($item->author_name); ?></a>
						<p class="smallsub"> <?php echo JText::sprintf('JGLOBAL_LIST_ALIAS', $this->escape($item->created_by_alias)); ?></p>
					<?php else : ?>
						<a class="hasTooltip" href="<?php echo JRoute::_('index.php?option=com_users&task=user.edit&id=' . (int) $item->created_by); ?>" title="<?php echo JText::_('JAUTHOR'); ?>">
						<?php echo $this->escape($item->author_name); ?></a>
					<?php endif; ?>
					</td>
					<td class="center hidden-phone">
						<?php echo (int) $item->id; ?>
					</td>
				</tr>
			<?php endforeach; ?>
			</tbody>
			<tfoot>
				<tr>
					<td colspan="10">
						<?php echo $this->pagination->getListFooter(); ?>
					</td>
				</tr>
			</tfoot>
		</table>
	<?php endif;

		// Load the batch processing form.
		echo $this->loadTemplate('batch'); ?>

		<div>
			<input type="hidden" name="task" value="" />
			<input type="hidden" name="boxchecked" value="0" />
			<?php echo JHtml::_('form.token'); ?>
		</div>
	</div>
</form>