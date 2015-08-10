<?php
if (!defined ('TYPO3_MODE')) {
 	die ('Access denied.');
}
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass'][] = "EXT:".$_EXTKEY."/hooks/class.tx_gorillary_collection_save_hook.php:&tx_gorillary_collection_save_hook";
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass'][] = "EXT:".$_EXTKEY."/hooks/class.tx_gorillary_feedimport_save_hook.php:&tx_gorillary_feedimport_save_hook";
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tceforms_inline.php']['tceformsInlineHook'][] = "EXT:".$_EXTKEY."/hooks/class.tx_gorillary_tceformsInlineHook.php:&tx_gorillary_tceformsInlineHook";
t3lib_extMgm::addPItoST43($_EXTKEY, 'pi1/class.tx_gorillary_pi1.php', '_pi1', 'list_type', 1);
t3lib_extMgm::addPItoST43($_EXTKEY, 'pi2/class.tx_gorillary_pi2.php', '_pi2', 'list_type', 1);




t3lib_extMgm::addTypoScript($_EXTKEY,'setup','
	tt_content.shortcut.20.0.conf.tx_gorillary_images = < plugin.'.t3lib_extMgm::getCN($_EXTKEY).'_pi1
	tt_content.shortcut.20.0.conf.tx_gorillary_images.CMD = singleView
',43);
?>
