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
/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 * Hint: use extdeveval to insert/update function index above.
 */
require_once(PATH_tslib . 'class.tslib_pibase.php');

/**
 * Plugin 'Gorillary Gallary' for the 'gorillary' extension.
 *
 * @author	Stephan Petzl <stephan.petzl@ajado.com>
 * @package	TYPO3
 * @subpackage	tx_gorillary
 */
class tx_gorillary_pi1 extends tslib_pibase {

	// Same as class name
	var $prefixId = 'tx_gorillary_pi1';
	// Path to this script relative to the extension dir.
	var $scriptRelPath = 'pi1/class.tx_gorillary_pi1.php';
	// The extension key.
	var $extKey = 'gorillary';
	
	var $pi_checkCHash = true;
	/**
	 *
	 * @var tslib_fe
	 */
	protected $tsfe;
	/**
	 *
	 * @var t3lib_DB
	 */
	protected $db;
	/**
	 *
	 * @var tslib_cObj
	 */
	public $cObj;

	/**
	 * hooks for any additional content manipulation
	 * @var array
	 */
	protected $hookObjects;
	/**
	 * Main method of your PlugIn
	 *
	 * @param	string		$content: The content of the PlugIn
	 * @param	array		$conf: The PlugIn Configuration
	 * @return	The content that should be displayed on the website
	 */
	function main($content, $conf) {

		$this->db = $GLOBALS['TYPO3_DB'];
		$this->tsfe = $GLOBALS['TSFE'];
		$this->pi_setPiVarDefaults();
		$this->pi_initPIflexForm();
		$this->conf = $conf;

		$content = "";

		$this->hookObjects = array();

		// initialise hooks for any additional content manipulation
		// TODO: make use of hook objects
		if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['gorillary']['contentManipulation'])) {
			foreach ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['gorillary']['contentManipulation'] as $_classRef) {
				$this->hookObjects[] = & t3lib_div::getUserObj($_classRef);
			}
		}

		// we have to retrieve the original uid, in case this is a localized tt_content record
		if(isset($this->cObj->data['_LOCALIZED_UID'])){
			$contentId = $this->cObj->data['_LOCALIZED_UID'];
		}else{
			$contentId = $this->cObj->data['uid'];
		}

		// check whether the default typoscript template was included
		if (!$this->conf['singleView.']){
		    return $this->pi_wrapInBaseClass("please include the template \"Gorillary gallery default configuration\" in your typoscript root template!");
		}

		// include the additional files into the header (e.g. some js files)
		$this->tsfe->additionalHeaderData[$this->prefixId] = '';
		foreach ($this->conf['additionalHeaderData.'] as $value) {
		    $this->tsfe->additionalHeaderData[$this->prefixId] .= $value."\n";
		}

		// check whether we have GET params here
		$imageId = intval($this->piVars['image']);
		$collectionId = intval($this->piVars['collection']);
		if($imageId){	// load image single view
			$content = $this->getSingleView($imageId);
		}else if($collectionId){ // load collection single view
			$content = $this->getCollectionView($collectionId);
		}else{ // load collection overview
			$content = $this->getGalleryView($contentId);
		}


		return $this->pi_wrapInBaseClass($content);
	}

	/**
	 * a gallery consists out of several collections
	 * @param int $contentId
	 * @return string
	 */
	protected function getGalleryView($contentId) {
		$content = "";
		$collections = $this->db->exec_SELECTgetRows('*', 'tx_gorillary_collections', "parentid=" . $contentId . " AND deleted=0 AND hidden=0", '', 'sorting');

		if (count($collections) > 1) {

			// include the additional files into the header (e.g. some js files)
			foreach ($this->conf['galleryView.']['additionalHeaderData.'] as $value) {
			    $this->tsfe->additionalHeaderData[$this->prefixId] .= $value."\n";
			}
			$cObj = t3lib_div::makeInstance('tslib_cObj');

			foreach ($collections as $collection) {
				// if there is no collection overview image set
				if(!$collection['image']){ // use the first image of the collection
					$images = explode(",",$collection['images']);
					$collection['image'] = $images[0];
				}
				$cObj->start($collection);
				$html = $cObj->cObjGetSingle($this->conf['galleryView.']['thumbnail'], $this->conf['galleryView.']['thumbnail.']);
				$content .= $html;
				
			}
					
			$content = $cObj->stdWrap($content, $this->conf['galleryView.']);
		} else if(count($collections) == 1){
			$content = $this->getCollectionView($collections[0]['uid']);
		} else{
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
		$collections = $this->db->exec_SELECTgetRows('*', 'tx_gorillary_collections', "uid=" . $collectionId . " AND deleted=0 AND hidden=0");

		// include the additional files into the header (e.g. some js files)
		foreach ($this->conf['collectionView.']['additionalHeaderData.'] as $value) {
		    $this->tsfe->additionalHeaderData[$this->prefixId] .= $value."\n";
		}
		if (count($collections)) {
			$collection = $collections[0];
			$cObjStdWrap = t3lib_div::makeInstance('tslib_cObj');
			$cObjStdWrap->start($collection);

			$cObj = t3lib_div::makeInstance('tslib_cObj');

			$images = $this->getImagesByCollectionId($collectionId);
			$filenames = explode(',', $collection['images']);
			$imageIndex = array();
			foreach($images as $image){
				$filename = $image['image'];
				$imageIndex[$filename] = $image;
			}
			// we have to order the images before output
			$imagesOrdered = array();
			foreach($filenames as $filename){
				$image = $imageIndex[$filename];
				$cObj->start($image);
				$content .= $cObj->cObjGetSingle($this->conf['collectionView.']['thumbnail'], $this->conf['collectionView.']['thumbnail.']);
			}
			$content = $cObjStdWrap->stdWrap($content, $this->conf['collectionView.']);

		} else {
			$content = "no collections found!";
		}
		return $content;
	}

	/**
	 * render a single image.
	 * load the given image, if not found take the first image of the given media collection
	 * and render it
	 * @param int $imageid
	 * @param int $collectionId
	 * @return string the content
	 */
	protected function getSingleView($imageId = null) {

		$content = "";
		$cObj = t3lib_div::makeInstance('tslib_cObj');
		
		// include the additional files into the header (e.g. some js files)
		foreach ($this->conf['singleView.']['additionalHeaderData.'] as $value) {
		    $this->tsfe->additionalHeaderData[$this->prefixId] .= $value."\n";
		}
		if ($imageId) {
			$images = $this->db->exec_SELECTgetRows('*', 'tx_gorillary_images', 'deleted=0 AND hidden=0 AND uid=' . $imageId);
		}

		if (count($images) > 0) {
			$image = $images[0];
			$cObj->start($image);
			$content .= $cObj->cObjGetSingle($this->conf['singleView.']['thumbnail'], $this->conf['singleView.']['thumbnail.']);
		} else {
			$content .= "no such image found!";
		}


		$content = $cObj->stdWrap($content, $this->conf['singleView.']);
		return $content;
	}
	
	private function getImagesByCollectionId($collectionId){
		$images = $this->db->exec_SELECTgetRows('*', 'tx_gorillary_images', 'deleted=0 AND hidden=0 AND collection=' . $collectionId);
		return $images;
	}


}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/gorillary/pi1/class.tx_gorillary_pi1.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/gorillary/pi1/class.tx_gorillary_pi1.php']);
}
?>
