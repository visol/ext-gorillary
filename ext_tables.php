<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

t3lib_extMgm::addPlugin(array(
    'Gorillary Gallery',
    $_EXTKEY . '_pi1',
    t3lib_extMgm::extRelPath($_EXTKEY) . 'ext_icon.gif'
),'list_type');

t3lib_extMgm::addPlugin(array(
    'Gorillary Media Feed Gallery',
    $_EXTKEY . '_pi2',
    t3lib_extMgm::extRelPath($_EXTKEY) . 'ext_icon.gif'
),'list_type');

t3lib_extMgm::allowTableOnStandardPages("tx_gorillary_feedimports");

$TCA['tx_gorillary_feedimports'] = array (
	'ctrl' => array (
		'title'     => 'LLL:EXT:gorillary/locallang_db.xml:tx_gorillary_feedimports',
		'label'     => 'title',
		'tstamp'    => 'tstamp',
		'crdate'    => 'crdate',
		'cruser_id' => 'cruser_id',
		'languageField'            => 'sys_language_uid',
		'transOrigPointerField'    => 'l10n_parent',
		'transOrigDiffSourceField' => 'l10n_diffsource',
		'default_sortby' => 'ORDER BY crdate',
		'delete' => 'deleted',
		'enablecolumns' => array (
			'disabled' => 'hidden',
		),
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY).'tca.php',
		'iconfile'          => t3lib_extMgm::extRelPath($_EXTKEY).'images/media-import.png',
	),
);

t3lib_extMgm::allowTableOnStandardPages("tx_gorillary_collections");

$TCA['tx_gorillary_collections'] = array (
	'ctrl' => array (
		'title'     => 'LLL:EXT:gorillary/locallang_db.xml:tx_gorillary_collections',		
		'label'     => 'title',	
		'tstamp'    => 'tstamp',
		'crdate'    => 'crdate',
		'cruser_id' => 'cruser_id',
		'languageField'            => 'sys_language_uid',	
		'transOrigPointerField'    => 'l10n_parent',	
		'transOrigDiffSourceField' => 'l10n_diffsource',	
		'default_sortby' => 'ORDER BY crdate',
		'sortby' => 'sorting',
		'delete' => 'deleted',	
		'enablecolumns' => array (
			'disabled' => 'hidden',
		),
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY).'tca.php',
		'iconfile'          => t3lib_extMgm::extRelPath($_EXTKEY).'images/image-stack.png',
	),
);

t3lib_extMgm::allowTableOnStandardPages("tx_gorillary_images");
$TCA['tx_gorillary_images'] = array (
	'ctrl' => array (
		'title'     => 'LLL:EXT:gorillary/locallang_db.xml:tx_gorillary_images',		
		'label'     => 'title',
		'tstamp'    => 'tstamp',
		'crdate'    => 'crdate',
		'cruser_id' => 'cruser_id',
		'sortby' => 'sorting',	
		'delete' => 'deleted',	
		'enablecolumns' => array (		
			'disabled' => 'hidden',
		),
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY).'tca.php',
		'iconfile'          => t3lib_extMgm::extRelPath($_EXTKEY).'images/image.png',
	),
);


t3lib_div::loadTCA('tt_content');

$tempColumns = array (
    "tx_gorillary_imagesource" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:gorillary/locallang_db.xml:tx_gorillary_collections",
			"config" => Array (
				"type" => "inline",
				"foreign_table" => "tx_gorillary_collections",
				"foreign_field" => "parentid",
				"foreign_table_field" => "parenttable",
				"maxitems" => 10,
				'appearance' => array(
					'showSynchronizationLink' => 1,
					'showAllLocalizationLink' => 1,
					'showPossibleLocalizationRecords' => 1,
					'showRemovedLocalizationRecords' => 1,
					'enabledControls' => array(
						'new' => 1,
						'delete' => 1,
						'sort' => 1,
						'hide' => 1,
						'dragdrop' => 1,
					),
				),
				'behaviour' => array(
					'localizationMode' => 'select',
				),
			)
		),
	"tx_gorillary_feedimports" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:gorillary/locallang_db.xml:tx_gorillary_feedimports",
			"config" => Array (
				"type" => "inline",
				"foreign_table" => "tx_gorillary_feedimports",
				"foreign_field" => "parentid",
				"foreign_table_field" => "parenttable",
				"maxitems" => 10,
				'appearance' => array(
					'showSynchronizationLink' => 1,
					'showAllLocalizationLink' => 1,
					'showPossibleLocalizationRecords' => 1,
					'showRemovedLocalizationRecords' => 1,
					'enabledControls' => array(
						'new' => 1,
						'delete' => 1,
						'sort' => 1,
						'hide' => 1,
						'dragdrop' => 1,
					),
				),
				'behaviour' => array(
					'localizationMode' => 'select',
				),
			)
		),
);


t3lib_div::loadTCA('tt_content');
t3lib_extMgm::addTCAcolumns('tt_content', $tempColumns, 1);

t3lib_div::loadTCA('tt_content');
//t3lib_extMgm::addPiFlexFormValue($_EXTKEY.'_pi1', 'FILE:EXT:'.$_EXTKEY.'/flexform_ds.xml');


$TCA['tt_content']['types']['list']['subtypes_excludelist'][$_EXTKEY.'_pi1']='layout,select_key,pages,recursive';
$TCA['tt_content']['types']['list']['subtypes_addlist'][$_EXTKEY.'_pi1']='tx_gorillary_imagesource';

$TCA['tt_content']['types']['list']['subtypes_excludelist'][$_EXTKEY.'_pi2']='layout,select_key,pages,recursive';
$TCA['tt_content']['types']['list']['subtypes_addlist'][$_EXTKEY.'_pi2']='tx_gorillary_feedimports';


t3lib_extMgm::addStaticFile($_EXTKEY,'static/gorillary_gallery_default_configuration/', 'Gorillary base configuration');
t3lib_extMgm::addStaticFile($_EXTKEY,'static/gorillary_jquery/', 'Gorillary jQuery');
t3lib_extMgm::addStaticFile($_EXTKEY,'static/gorillary_jquery_cycle/', 'Gorillary jQuery Cycle');
t3lib_extMgm::addStaticFile($_EXTKEY,'static/gorillary_jquery_galleriffic/', 'Gorillary jQuery Galleriffic');
t3lib_extMgm::addStaticFile($_EXTKEY,'static/gorillary_jquery_colorbox/', 'Gorillary jQuery Collorbox');
t3lib_extMgm::addStaticFile($_EXTKEY,'static/gorillary_direct_link_to_image/', 'Gorillary direct image link');

if (TYPO3_MODE == 'BE') {
    $TBE_MODULES_EXT['xMOD_db_new_content_el']['addElClasses']['tx_gorillary_pi1_wizicon'] = t3lib_extMgm::extPath($_EXTKEY).'pi1/class.tx_gorillary_pi1_wizicon.php';
	$TBE_MODULES_EXT['xMOD_db_new_content_el']['addElClasses']['tx_gorillary_pi2_wizicon'] = t3lib_extMgm::extPath($_EXTKEY).'pi2/class.tx_gorillary_pi2_wizicon.php';
}

?>
