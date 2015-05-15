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
defined('_JEXEC') or die;

/**
 * Script file of Birthdays Component
 *
 * @package     Joomla.Administrator
 * @subpackage  com_birthdays
 *
 * @since       0.0.1
 */
class Com_BirthdaysInstallerScript
{
  /**
   * Extension name
   *
   * @var   string
   * @since 0.0.1
   */
  private $_extension = 'com_birthdays';

  /**
   * Version release
   *
   * @var   string
   * @since 0.0.1
   */
  private $_release = '';

  /**
   * Array of sub extensions package
   *
   * @var   array
   * @since 0.0.1
   */
  private $_subextensions = array(
    'modules' => array(
    ),
    'plugins' => array(
    )
  );

  /**
   * Array of obsolete files and folders.
   * Examples:
   *    /path/to/file.ext
   *    /path/to/folder
   *
   * @var   array
   * @since 0.0.1
   */
  private $_obsoletes = array(
    'files' => array(
      '/administrator/components/com_birthdays/views/birthday/tmpl/edit_publish.php'
    ),
    'folders' => array(
    )
  );

  /**
   * Method to install the component
   *
   * @param  JAdapterInstance  $adapter  The object responsible for running this script.
   *
   * @return boolean           True on success.
   *
   * @since  0.0.1
   */
  function install($adapter)
  {
    echo '<p>' . JText::sprintf('COM_BIRTHDAYS_INSTALL_TEXT', $this->_release) . '</p>';
  }

  /**
   * Method to uninstall the component
   *
   * @param  JAdapterInstance  $adapter  The object responsible for running this script.
   *
   * @return boolean           True on success.
   *
   * @since  0.0.1
   */
  function uninstall(JAdapterInstance $adapter)
  {
    echo '<p>' . JText::sprintf('COM_BIRTHDAYS_UNINSTALL_TEXT', $this->_release) . '</p>';
  }

  /**
   * Method to update the component
   *
   * @param  JAdapterInstaller $adapter
   *
   * @return boolean           True on success.
   *
   * @since  0.0.1
   */
  function update($adapter)
  {
    echo '<p>' . JText::sprintf('COM_BIRTHDAYS_UPDATE_TEXT', $this->_release) . '</p>';
  }

  /**
   * Method to run before an install/update/uninstall method
   *
   * @param  string            $route    Which action is happening (install|uninstall|discover_install).
   * @param  JAdapterInstance  $adapter  The object responsible for running this script.
   *
   * @return boolean           True on success.
   *
   * @since  0.0.1
   */
  function preflight($route, $adapter)
  {
    $this->_checkCompatible($route, $adapter);
  }

  /**
   * Method to run after an install/update/uninstall method
   *
   * @param  string            $route    Which action is happening (install|uninstall|discover_install).
   * @param  JAdapterInstance  $adapter  The object responsible for running this script.
   *
   * @return boolean  True on success.
   *
   * @since  0.0.1
   */
  function postflight($route, $adapter)
  {
    if ($route == 'install')
    {
      $folder = JPATH_ROOT . '/images/birthdays';
      $src    = $parent->getParent()->getPath('source');

      if(JFolder::exists($folder) || JFolder::create($folder))
      {
          if(!JFile::exists($folder . '/index.html'))
          {
              JFile::copy($src . '/images/index.html', $folder . '/index.html');
          }

          if(!JFile::exists($folder . '/noimage.jpg'))
          {
              JFile::copy($src . '/images/noimage.jpg', $folder . '/noimage.jpg');
          }
      }
    }
    elseif ($route == 'uninstall')
    {
      $this->_obsoletes['folders'] = '/images/birthdays';
    }

    $this->_removeObsoletes();
  }

  /**
   * Method for checking compatibility installation environment
   *
   * @param  JAdapterInstance  $adapter  The object responsible for running this script.
   *
   * @return boolean           True if the installation environment is compatible
   *
   * @since  0.0.1
   */
  private function _checkCompatible($route, $adapter)
  {
    // Get the application.
    $this->_release = (string) $adapter->get('manifest')->version;
    $min_version    = (string) $adapter->get('manifest')->attributes()->version;
    $jversion       = new JVersion;

    if (version_compare($jversion->getShortVersion(), $min_version, 'lt' ))
    {
      echo JText::sprintf('COM_BIRTHDAYS_VERSION_UNSUPPORTED', $this->_release, $min_version);
      return false;
    }

    // Storing old release number for process in postflight.
    if ($route == 'update')
    {
      $oldRelease = $this->_getParam('version');

      if (version_compare($this->_release, $oldRelease, 'le'))
      {
        echo JText::sprintf('COM_BIRTHDAYS_UPDATE_UNSUPPORTED', $oldRelease, $this->_release);
        return false;
      }
    }
  }

  /**
   * Removes obsoletes files and folders
   *
   * @param  array $obsoletes Array with obsolete files and folders
   *
   * @return void
   *
   * @since  0.0.1
   */
  private function _removeObsoletes($obsoletes = array())
  {
    jimport('joomla.filesystem.file');
    foreach($obsoletes['files'] as $file)
    {
      if(JFile::exists(JPATH_ROOT . $file) && !JFile::delete(JPATH_ROOT . $file))
      {
        echo JText::sprintf('FILES_JOOMLA_ERROR_FILE_FOLDER', $file) . '<br />';
      }
    }

    jimport('joomla.filesystem.folder');
    foreach($obsoletes['folders'] as $folder)
    {
      if(!JFolder::exists(JPATH_ROOT . $folder) && !JFolder::delete(JPATH_ROOT . $folder))
      {
        echo JText::sprintf('FILES_JOOMLA_ERROR_FILE_FOLDER', $folder) . '<br />';
      }
    }
  }

  /**
   * Get a variable from the manifest cache.
   *
   * @param  string $name Column name
   *
   * @return string Value of column name
   *
   * @since  0.0.1
   */
  private function _getParam($name)
  {
    $db    = JFactory::getDbo();
    $query = $db->getQuery(true);

    $query->select($db->quoteName('manifest_cache'));
    $query->from($db->quoteName('#__extensions'));
    $query->where($db->quoteName('name') . ' = ' . $db->quote($this->_extension));
    $db->setQuery($query);

    $manifest = json_decode($db->loadResult(), true);

    return $manifest[$name];
  }
}