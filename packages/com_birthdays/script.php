<?php
/**
 * @package     Birthdays
 * @subpackage  com_birthdays
 * @copyright   Copyright (C) 2013 Makesoft, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

// Include dependencies
jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');
jimport('joomla.installer.installer');

/**
 * Script file of Birthdays component
 */
class Com_BirthdaysInstallerScript
{
    /**
     * Method to install the component
     *
     * @param JInstaller $parent
     */
    function install($parent)
    {
        $folder = JPATH_ROOT . DS . 'images/birthdays';
        $src    = $parent->getParent()->getPath('source');

        if(JFolder::exists($folder) || JFolder::create($folder))
        {
            if(!JFile::exists($folder . '/index.html'))
            {
                $data = '<html><body bgcolor="#FFFFFF"></body></html>';
                JFile::write($folder . '/index.html', $data);
            }

            if(!JFile::exists($folder . '/noimage.jpg'))
            {
                JFile::copy($src . '/images/noimage.jpg', $folder . '/noimage.jpg');
            }
        }
    }

    /**
     * Method to uninstall the component
     *
     * @param JInstaller $parent
     */
    function uninstall($parent)
    {
        $folder = JPATH_ROOT . DS . 'images/birthdays';
        $db =& JFactory::getDbo();

        if(JFolder::exists($folder))
        {
            JFolder::delete($folder);
        }
    }

    /**
     * Method to update the component
     *
     * @param JInstaller $parent
     */
    function update($parent)
    {
        $this->install($parent);
    }
}