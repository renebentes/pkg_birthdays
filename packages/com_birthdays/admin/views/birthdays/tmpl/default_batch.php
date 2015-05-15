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

$published = $this->state->get('filter.state');
?>
<div class="modal hide fade" id="collapseModal">
  <div class="modal-header">
    <button type="button" role="presentation" class="close" data-dismiss="modal">&#215;</button>
    <h3><?php echo JText::_('COM_BIRTHDAYS_BATCH_OPTIONS'); ?></h3>
  </div>
  <div class="modal-body modal-batch">
    <p><?php echo JText::_('COM_BIRTHDAYS_BATCH_TIP'); ?></p>
    <div class="row-fluid">
      <div class="control-group span4">
        <div class="controls">
          <?php echo JHtml::_('batch.access'); ?>
        </div>
      </div>
      <div class="control-group span4">
        <div class="controls">
          <?php echo JHtml::_('batch.language'); ?>
        </div>
      </div>
    </div>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn" onclick="document.getElementById('batch-access').value='';document.getElementById('batch-tag-id)').value='';document.getElementById('batch-language-id').value='';" data-dismiss="modal">
      <?php echo JText::_('JCANCEL'); ?>
    </button>
    <button type="submit" class="btn btn-primary" onclick="Joomla.submitbutton('birthday.batch');">
      <?php echo JText::_('JGLOBAL_BATCH_PROCESS'); ?>
    </button>
  </div>
</div>