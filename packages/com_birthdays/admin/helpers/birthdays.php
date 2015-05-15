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
defined('_JEXEC') or die('Restricted access!');


/**
 * Birthdays component helper.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_birthdays
 * @since       0.0.1
 */
class BirthdaysHelper extends JHelperContent
{
	public static $extension = 'com_birthdays';

	/**
	 * Configure the Linkbar.
	 *
	 * @param  string  $vName  The name of the active view.
	 *
	 * @return void
   *
	 * @since  0.0.1
	 */
	public static function addSubmenu($vName)
	{
		JHtmlSidebar::addEntry(
			JText::_('COM_BIRTHDAYS_SUBMENU_BIRTHDAYS'),
			'index.php?option=com_birthdays&view=birthdays',
			$vName == 'birthdays'
		);
	}

	/**
	 * Method for resizing images to display in list.
	 *
	 * @param   string   $image   The path to full image
	 * @param   string   $size    The new size. Example: array('50x50','120x250');
	 * @param   string   $folder  The thumbs destination folder
	 * @param   integer  $method  The thumbnail creation method.
	 *
	 * @return  string   Path of the new image file.
	 *
	 * @since   0.0.1
	 */
	public static function getImage($image, $size, $folder, $method = 2)
	{
		jimport('joomla.filesystem.folder');

		if(!empty($size) && JFile::exists($image))
		{
			// Check or try to create folder
			if (JFolder::exists($folder) || JFolder::create($folder))
			{
				// Create file to previne direct access
				$data = '<!DOCTYPE html><html><head><title>&nbsp;</title></head><body></body></html>';
				JFile::write($folder . "/index.html", $data);
				// Get thumb size
				$size = explode('x', strtolower($size));
				if (count($size) != 2)
				{
					return false;
				}

				$width  = $size[0];
				$height = $size[1];

				// Source object
				$sourceImage = new JImage($image);
				$srcHeight   = $sourceImage->getHeight();
				$srcWidth    = $sourceImage->getWidth();
				$properties  = JImage::getImageFileProperties($image);

				// Generate thumb name
				$filename  = JFile::getName($image);
				$extension = JFile::getExt($filename);
				$thumbname = str_replace('.' . $extension, '_' . $width . 'x' . $height . '.' . $extension, $filename);

				// Try to generate the thumb
				if ($method == 4)
				{
					// Auto crop centered coordinates
					$left = round(($srcWidth - $width) / 2);
					$top  = round(($srcHeight - $height) / 2);

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