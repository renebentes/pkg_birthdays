<?php
/**
 * @package     Birthdays
 * @subpackage	com_birthdays
 * @copyright   Copyright (C) MakeSoft, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

/**
 * Birthdays component helper.
 *
 * @package		Birthdays
 * @subpackage	com_birthdays
 * @since		2.5
 */
class BirthdaysHelper
{
	public static $extension = 'com_birthdays';

	/**
	 * Configure the Linkbar.
	 *
	 * @param	string	$vName	The name of the active view.
	 *
	 * @return	void
	 * @since	2.5
	 */
	public static function addSubmenu($vName)
	{
		JSubMenuHelper::addEntry(
			JText::_('COM_BIRTHDAYS_SUBMENU_BIRTHDAYS'),
			'index.php?option=com_birthdays&view=birthdays',
			$vName == 'birthdays'
		);
	}

	/**
	 * Gets a list of the actions that can be performed.
	 *
	 * @return	JObject
	 */
	public static function getActions()
	{
		$user		= JFactory::getUser();
		$result		= new JObject;
		$assetName	= 'com_birthdays';

		$actions = array(
			'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.edit.state', 'core.delete'
		);

		foreach ($actions as $action) {
			$result->set($action, $user->authorise($action, $assetName));
		}

		return $result;
	}

	/**
	 * Get a list of the month for filtering.
	 *
	 * @return  array  An array of JHtmlOption elements.
	 *
	 * @since   2.5
	 */
	public static function getMonthOptions()
	{
		// Build the filter options.
		$options = array();
		jimport('joomla.utilities.date');

		$options[] = JHtml::_('select.option', '', JText::_('COM_BIRTHDAYS_FILTER_BIRTHDATE'));
		for($i = 1; $i <=12; $i++)
		{
			$options[] = JHtml::_('select.option', $i, JDate::monthToString($i));
		}

		return $options;
	}

	/**
	 * Method for resizing images to display in list.
	 *
	 * @param   string   $image   The path to full image
	 * @param   string   $size    The new size. Example: array('50x50','120x250');
	 * @param   string   $folder  The thumbs destination folder
	 * @param   integer  $method  The thumbnail creation method.
	 * @return  string   Path of the new image file.
	 *
	 * @since   2.5
	 */
	public static function getImage($image, $size, $folder, $method = 2)
	{
		jimport('joomla.filesystem.folder');
		jimport('joomla.filesystem.file');
		jimport('joomla.image.image');

		if(!empty($size) && JFile::exists($image))
		{
			// Check or try to create folder
			if (JFolder::exists($folder) || JFolder::create($folder))
			{
				// Create file to previne direct access
				$data = "<html>\n<body bgcolor=\"#FFFFFF\">\n</body>\n</html>";
				JFile::write($folder . DS . "index.html", $data);
				// Get thumb size
				$size = explode('x', strtolower($size));
				if (count($size) != 2)
				{
					return false;
				}

				$width = $size[0];
				$height = $size[1];

				// Source object
				$sourceImage = new JImage($image);
				$srcHeight = $sourceImage->getHeight();
				$srcWidth = $sourceImage->getWidth();
				$properties = JImage::getImageFileProperties($image);

				// Generate thumb name
				$filename = JFile::getName($image);
				$extension = JFile::getExt($filename);
				$thumbname = str_replace('.' . $extension, '_' . $width . 'x' . $height . '.' . $extension, $filename);

				// Try to generate the thumb
				if ($method == 4)
				{
					// Auto crop centered coordinates
					$left = round(($srcWidth - $width) / 2);
					$top = round(($srcHeight - $height) / 2);

					// Crop image
					$thumb = $sourceImage->crop($width, $height, $left, $top, true);
				}
				else
				{
					// Resize image
					$thumb = $sourceImage->resize($width, $height, true, $method);
				}

				if ($properties->type == 3)
				{
					$quality = 10;
				}
				elseif ($properties->type == 2)
				{
					$quality = 90;
				}

				$thumbname = $folder . '/' . $thumbname;

				if (!JFile::exists($thumbname))
				{
					$thumb->toFile($thumbname, $properties->type, array('quality' => $quality));
				}
				return $thumbname;
			}
		}

		return false;
	}
}