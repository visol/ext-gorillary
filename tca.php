<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

t3lib_extMgm::allowTableOnStandardPages("tx_gorillary_content_mm");

require_once t3lib_extMgm::extPath('gorillary').'lib/class.user_imagefield.php';


$TCA['tx_gorillary_feedimports'] = array (
	'ctrl' => $TCA['tx_gorillary_feedimports']['ctrl'],
	'interface' => array (
		'showRecordFieldList' => 'sys_language_uid,l10n_parent,l10n_diffsource,hidden,title,images'
	),
	'feInterface' => $TCA['tx_gorillary_feedimports']['feInterface'],
	'columns' => array (
		'sys_language_uid' => array (
			'exclude' => 1,
			'label'  => 'LLL:EXT:lang/locallang_general.xml:LGL.language',
			'config' => array (
				'type'                => 'select',
				'foreign_table'       => 'sys_language',
				'foreign_table_where' => 'ORDER BY sys_language.title',
				'items' => array(
					array('LLL:EXT:lang/locallang_general.xml:LGL.allLanguages', -1),
					array('LLL:EXT:lang/locallang_general.xml:LGL.default_value', 0)
				)
			)
		),
		'l10n_parent' => array (
			'displayCond' => 'FIELD:sys_language_uid:>:0',
			'exclude'     => 1,
			'label'       => 'LLL:EXT:lang/locallang_general.xml:LGL.l18n_parent',
			'config'      => array (
				'type'  => 'select',
				'items' => array (
					array('', 0),
				),
				'foreign_table'       => 'tx_gorillary_collections',
				'foreign_table_where' => 'AND tx_gorillary_collections.pid=###CURRENT_PID### AND tx_gorillary_collections.sys_language_uid IN (-1,0)',
			)
		),
		'l10n_diffsource' => array (
			'config' => array (
				'type' => 'passthrough'
			)
		),
		'hidden' => array (
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
			'config'  => array (
				'type'    => 'check',
				'default' => '0'
			)
		),
		'title' => array (
			'exclude' => 0,
			'label' => 'LLL:EXT:gorillary/locallang_db.xml:tx_gorillary_feedimports.title',
			'config' => array (
				'type' => 'input',
				'size' => '30',
				'eval' => 'required',
			)
		),
		'image' => Array (
			'exclude' => 1,
			'l10n_mode' => $l10n_mode_image,
			'label' => 'LLL:EXT:gorillary/locallang_db.xml:tx_gorillary_feedimports.image',
			'config' => Array (
				'type' => 'group',
				'internal_type' => 'file',
				'allowed' => $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext'],
				'max_size' => '10000',
				'uploadfolder' => 'uploads/tx_gorillary',
				'show_thumbs' => '1',
				'size' => 1,
				'autoSizeMax' => 15,
				'maxitems' => '1',
				'minitems' => '0'
			)
		),

        'feed_url' => Array (
			'exclude' => 0,
			'label' => 'LLL:EXT:gorillary/locallang_db.xml:tx_gorillary_feedimports.feed_url',
			'config' => array (
				'type' => 'input',
				'size' => '30',
				'eval' => 'required',
			)
		),
        'image_records' => Array (
			'exclude' => 1,
			'label' => "LLL:EXT:gorillary/locallang_db.xml:tx_gorillary_feedimports.images",
			'config' => Array (
				'type' => "inline",
				'foreign_table' => 'tx_gorillary_images',
				'maxitems' => 500,
				'appearance' => array(
					'showSynchronizationLink' => 1,
					'showAllLocalizationLink' => 1,
					'showPossibleLocalizationRecords' => 1,
					'showRemovedLocalizationRecords' => 1,
					'enabledControls' => array(
						'new' => 0,
						'delete' => 1,
						'sort' => 1,
						'hide' => 1,
						'dragdrop' => 1,
					),
					'levelLinksPosition' => 'none',
				),
				'behaviour' => array(
					'localizationMode' => 'select',
				),
			)
		),
	),
	'types' => array (
		'0' => array('showitem' => 'title;;;;2-2-2, feed_url, image, images, image_records;;;;3-3-3')
	),
	'palettes' => array (
		'1' => array('showitem' => '')
	)
);


$TCA['tx_gorillary_collections'] = array (
	'ctrl' => $TCA['tx_gorillary_collections']['ctrl'],
	'interface' => array (
		'showRecordFieldList' => 'sys_language_uid,l10n_parent,l10n_diffsource,parentid,parenttable,title,images'
	),
	'feInterface' => $TCA['tx_gorillary_collections']['feInterface'],
	'columns' => array (
		'sys_language_uid' => array (		
			'exclude' => 1,
			'label'  => 'LLL:EXT:lang/locallang_general.xml:LGL.language',
			'config' => array (
				'type'                => 'select',
				'foreign_table'       => 'sys_language',
				'foreign_table_where' => 'ORDER BY sys_language.title',
				'items' => array(
					array('LLL:EXT:lang/locallang_general.xml:LGL.allLanguages', -1),
					array('LLL:EXT:lang/locallang_general.xml:LGL.default_value', 0)
				)
			)
		),
		'l10n_parent' => array (		
			'displayCond' => 'FIELD:sys_language_uid:>:0',
			'exclude'     => 1,
			'label'       => 'LLL:EXT:lang/locallang_general.xml:LGL.l18n_parent',
			'config'      => array (
				'type'  => 'select',
				'items' => array (
					array('', 0),
				),
				'foreign_table'       => 'tx_gorillary_collections',
				'foreign_table_where' => 'AND tx_gorillary_collections.pid=###CURRENT_PID### AND tx_gorillary_collections.sys_language_uid IN (-1,0)',
			)
		),
		'l10n_diffsource' => array (		
			'config' => array (
				'type' => 'passthrough'
			)
		),
        "parentid" => Array (
			"config" => Array (
				"type" => "passthrough",
			)
		),
		"parenttable" => Array (
			"config" => Array (
				"type" => "passthrough",
			)
		),
		'hidden' => array (		
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
			'config'  => array (
				'type'    => 'check',
				'default' => '0'
			)
		),
		'title' => array (		
			'exclude' => 0,		
			'label' => 'LLL:EXT:gorillary/locallang_db.xml:tx_gorillary_collections.title',
			'config' => array (
				'type' => 'input',	
				'size' => '30',	
				'eval' => 'required',
			)
		),
		'image' => Array (
			'exclude' => 1,
			'l10n_mode' => $l10n_mode_image,
			'label' => 'LLL:EXT:gorillary/locallang_db.xml:tx_gorillary_collections.image',
			'config' => Array (
				'type' => 'group',
				'internal_type' => 'file',
				'allowed' => $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext'],
				'max_size' => '10000',
				'uploadfolder' => 'uploads/tx_gorillary',
				'show_thumbs' => '1',
				'size' => 1,
				'autoSizeMax' => 15,
				'maxitems' => '1',
				'minitems' => '0'
			)
		),
        'images' => Array (
			'exclude' => 1,
			'l10n_mode' => $l10n_mode_image,
			'label' => 'LLL:EXT:gorillary/locallang_db.xml:tx_gorillary_collections.images',
			'config' => Array (
				'type' => 'group',
				'internal_type' => 'file',
				'allowed' => $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext'],
				'max_size' => '10000',
				'uploadfolder' => 'uploads/tx_gorillary',
				'show_thumbs' => '1',
				'size' => 10,
				'autoSizeMax' => 15,
				'maxitems' => '500',
				'minitems' => '0'
			)
		),
        'image_records' => Array (
			'exclude' => 1,
			'label' => "LLL:EXT:gorillary/locallang_db.xml:tx_gorillary_collections.images_meta",
			'config' => Array (
				'type' => "inline",
				'foreign_table' => 'tx_gorillary_images',
				'maxitems' => 500,
				'appearance' => array(
					'showSynchronizationLink' => 1,
					'showAllLocalizationLink' => 1,
					'showPossibleLocalizationRecords' => 1,
					'showRemovedLocalizationRecords' => 1,
					'enabledControls' => array(
						'new' => 0,
						'delete' => 0,
						'sort' => 0,
						'hide' => 0,
						'dragdrop' => 0,
					),
					'levelLinksPosition' => 'none',
				),
				'behaviour' => array(
					'localizationMode' => 'select',
				),
			)
		),
	),
	'types' => array (
		'0' => array('showitem' => 'title;;;;2-2-2, parentid, parenttable, image, images, image_records;;;;3-3-3')
	),
	'palettes' => array (
		'1' => array('showitem' => '')
	)
);



$TCA['tx_gorillary_images'] = array (
	'ctrl' => $TCA['tx_gorillary_images']['ctrl'],
	'interface' => array (
		'showRecordFieldList' => 'hidden,title,description,image'
	),
	'feInterface' => $TCA['tx_gorillary_images']['feInterface'],
	'columns' => array (
        'sys_language_uid' => array (
			'exclude' => 1,
			'label'  => 'LLL:EXT:lang/locallang_general.xml:LGL.language',
			'config' => array (
				'type'                => 'select',
				'foreign_table'       => 'sys_language',
				'foreign_table_where' => 'ORDER BY sys_language.title',
				'items' => array(
					array('LLL:EXT:lang/locallang_general.xml:LGL.allLanguages', -1),
					array('LLL:EXT:lang/locallang_general.xml:LGL.default_value', 0)
				)
			)
		),
		'l18n_parent' => array (
			'displayCond' => 'FIELD:sys_language_uid:>:0',
			'exclude'     => 1,
			'label'       => 'LLL:EXT:lang/locallang_general.xml:LGL.l18n_parent',
			'config'      => array (
				'type'  => 'select',
				'items' => array (
					array('', 0),
				),
				'foreign_table'       => 'tx_gorillary_images',
				'foreign_table_where' => 'AND tx_gorillary_images.pid=###CURRENT_PID### AND tx_gorillary_images.sys_language_uid IN (-1,0)',
			)
		),
		'l18n_diffsource' => array (
			'config' => array (
				'type' => 'passthrough'
			)
		),
		'hidden' => array (		
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
			'config'  => array (
				'type'    => 'check',
				'default' => '0'
			)
		),
        'image' => array (
			'exclude' => 0,
			'label' => 'LLL:EXT:gorillary/locallang_db.xml:tx_gorillary_images.image',
			'config' => array (
				'type' => 'input',
				'size' => '30',
				'eval' => 'required',
			)
		),
		'title' => array (		
			'exclude' => 0,		
			'label' => 'LLL:EXT:gorillary/locallang_db.xml:tx_gorillary_images.title',		
			'config' => array (
				/*'type' => 'input',
				'size' => '30',	
				'eval' => 'required',*/
				'type' => 'user',
				'userFunc' => 'user_imagefield->getHtml'
			)
		),
		'description' => array (		
			'exclude' => 0,		
			'label' => 'LLL:EXT:gorillary/locallang_db.xml:tx_gorillary_images.description',		
			'config' => array (
				'type' => 'text',
				'cols' => '30',	
				'rows' => '5',
			)
		),
        'link' => array (
            'exclude' => 0,
            'label' => 'LLL:EXT:gorillary/locallang_db.xml:tx_gorillary_images.link',
            'config' => array (
                'type'     => 'input',
                'size'     => '15',
                'max'      => '255',
                'checkbox' => '',
                'eval'     => 'trim',
                'wizards'  => array(
                    '_PADDING' => 2,
                    'link'     => array(
                        'type'         => 'popup',
                        'title'        => 'Link',
                        'icon'         => 'link_popup.gif',
                        'script'       => 'browse_links.php?mode=wizard',
                        'JSopenParams' => 'height=300,width=500,status=0,menubar=0,scrollbars=1'
                    )
                )
            )
        ),
		'additional_files' => array(
			'exclude' => 1,
			'l10n_mode' => $l10n_mode_image,
			'label' => 'LLL:EXT:gorillary/locallang_db.xml:tx_gorillary_images.additional_files',
			'config' => Array (
				'type' => 'group',
				'internal_type' => 'file',
				'allowed' => '',
				'disallowed' => 'php,php3',
				'max_size' => '100000',
				'uploadfolder' => 'uploads/tx_gorillary',
				'show_thumbs' => '1',
				'size' => 5,
				'autoSizeMax' => 15,
				'maxitems' => '10',
				'minitems' => '0'
			)
		),
		
	),
	'types' => array (
		'0' => array('showitem' => 'title;;;;2-2-2, link, description;;;;3-3-3, additional_files')
	),
	'palettes' => array (
		'1' => array('showitem' => '')
	)
);
?>
