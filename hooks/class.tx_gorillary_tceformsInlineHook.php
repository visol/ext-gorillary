<?php
//require_once($GLOBALS['_SERVER']['DOCUMENT_ROOT'].'/t3lib/interfaces/interface.t3lib_tceformsinlinehook.php');
require_once(PATH_t3lib.'/interfaces/interface.t3lib_tceformsinlinehook.php');

class tx_gorillary_tceformsInlineHook implements t3lib_tceformsInlineHook{
	
	public function init(&$parentObject){
	}
	
	/**
	 * Pre-processing to define which control items are enabled or disabled.
	 *
	 * @param	string		$parentUid: The uid of the parent (embedding) record (uid or NEW...)
	 * @param	string		$foreignTable: The table (foreign_table) we create control-icons for
	 * @param	array		$childRecord: The current record of that foreign_table
	 * @param	array		$childConfig: TCA configuration of the current field of the child record
	 * @param	boolean		$isVirtual: Defines whether the current records is only virtually shown and not physically part of the parent record
	 * @param	array		&$enabledControls: (reference) Associative array with the enabled control items
	 * @return	void
	 */
					
	public function renderForeignRecordHeaderControl_preProcess($parentUid, $foreign_table, array $rec, array $config, $isVirtual, array &$enabledControls){
		
	}

	/**
	 * Post-processing to define which control items to show. Possibly own icons can be added here.
	 *
	 * @param	string		$parentUid: The uid of the parent (embedding) record (uid or NEW...)
	 * @param	string		$foreignTable: The table (foreign_table) we create control-icons for
	 * @param	array		$childRecord: The current record of that foreign_table
	 * @param	array		$childConfig: TCA configuration of the current field of the child record
	 * @param	boolean		$isVirtual: Defines whether the current records is only virtually shown and not physically part of the parent record
	 * @param	array		&$controlItems: (reference) Associative array with the currently available control items
	 * @return	void
	 */
	public function renderForeignRecordHeaderControl_postProcess($parentUid, $foreignTable, array $childRecord, array $childConfig, $isVirtual, array &$controlItems){
	}

}
?>