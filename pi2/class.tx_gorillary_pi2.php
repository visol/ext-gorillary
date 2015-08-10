<?php

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2010 Stephan Petzl <stephan.petzl@ajado.com>
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
************************************************************** */
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Plugin 'Gorillary Gallary' for the 'gorillary' extension.
 *
 * @author	Stephan Petzl <stephan.petzl@ajado.com>
 * @package	TYPO3
 * @subpackage	tx_gorillary
 */
class tx_gorillary_pi2 extends tx_gorillary_pi1 {

	var $prefixId = 'tx_gorillary_pi1';
// Same as class name
	var $scriptRelPath = 'pi2/class.tx_gorillary_pi2.php';
// Path to this script relative to the extension dir.
	var $extKey = 'gorillary';
// The extension key.
	var $pi_checkCHash = true;

	function main($content, $conf) {
		return parent::main($content, $conf);
	}
	/**
	 * a gallery consists out of several collections
	 * @param int $contentId
	 * @return string
	 */
	protected function getGalleryView($contentId) {
		$content = "";
		$collections = $this->db->exec_SELECTgetRows('*', 'tx_gorillary_feedimports', "parentid=" . $contentId . " AND parenttable='tt_content' AND deleted=0 AND hidden=0");

		if (count($collections) > 1) {
			$cObj = GeneralUtility::makeInstance('TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer');

			foreach ($collections as $collection) {
				$cObj->start($collection);
				$html = $cObj->cObjGetSingle($this->conf['galleryView.']['thumbnail'], $this->conf['galleryView.']['thumbnail.']);

				$content .= $html;
			}
			$content = str_replace('|', $content, $this->conf['galleryView.']['wrap']);

		} else if(count($collections) == 1){
			$content = $this->getCollectionView($collections[0]['uid']);
		} else {
			$content = "no collections found!";
		}
		return $content;
	}

	/**
	 * a collection consists out of several images
	 * @param int $collectionId
	 * @return string
	 */
	protected function getCollectionView($collectionId) {
		$content = "";
		$collections = $this->db->exec_SELECTgetRows('*', 'tx_gorillary_feedimports', "uid=" . $collectionId . " AND parenttable='tt_content' AND deleted=0 AND hidden=0");

		if (count($collections)) {
			$cObjCollection = GeneralUtility::makeInstance('TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer');
			$cObjCollection->start($collections[0]);

			$cObj = GeneralUtility::makeInstance('TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer');
			$images = $this->db->exec_SELECTgetRows('*', 'tx_gorillary_images', 'deleted=0 AND hidden=0 AND feedimport=' . $collectionId);

			foreach ($images as $image) {
				$cObj->start($image);
				$content .= $cObj->cObjGetSingle($this->conf['collectionView.']['thumbnail'], $this->conf['collectionView.']['thumbnail.']);
			}
			$content = $cObjCollection->stdWrap($content, $this->conf['collectionView.']);

		} else {
			$content = "no collections found!";
		}
		return $content;
	}
	
}
