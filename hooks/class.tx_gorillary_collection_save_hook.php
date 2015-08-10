<?php
/**
 * creates (or deletes) a record for each image which is selected in the select box of the collection record
 * gets triggered when hitting the save button in the backend 
 */
class tx_gorillary_collection_save_hook{

	/**
	 * @var t3lib_DB
	 */
	private $db;
	private $collection;
	private $images;


    function tx_gorillary_collection_save_hook(){
    
    }
    
	function processDatamap_afterDatabaseOperations($status, $table, $id, $fieldArray, $ref){
		$this->db = $GLOBALS['TYPO3_DB'];
		//debug($status);
		
		if($table == "tx_gorillary_collections"){
			
			switch($status){
				case "new":
					// we have to retrieve the real uid from the database
					$collectionUid = $ref->substNEWwithIDs[$id];
					break;
				case "update":
					$collectionUid = $id;
					break;
			}

			$rows = $this->db->exec_SELECTgetRows('*', 'tx_gorillary_collections', "uid='$collectionUid' AND deleted=0");
			$this->collection = $rows[0];
			$this->images = $this->getImageRecordsOfCollection($this->collection);
			$this->createRecords();
			$this->updateCollectionRecord();
			$this->deleteCheck = true;
		}
	}
	
	function processDatamap_afterAllOperations(&$ref){
		if($this->deleteCheck){
			$this->deleteRecords();
		}
	}
	
    function processDatamap_postProcessFieldArray($status, $table, $id, $fieldArray, $ref){
		if($table == "tx_gorillary_collections"){
			
		}
    }
    function processDatamap_preProcessFieldArray($fieldArray, $table, $id, $ref){
		if($table == "tx_gorillary_collections"){
			
		}
    }
	private function getImageRecordsOfCollection($collection){
		$imageRows = $this->db->exec_SELECTgetRows('*', 'tx_gorillary_images', "deleted=0 AND collection='".$collection['uid']."' AND pid='".$collection['pid']."'");
		
		$imgArray = array();
		foreach($imageRows as $imgRow){
			$imgArray[$imgRow['image']] = $imgRow;
		}
		return $imgArray;
	}

	/**
	 * creates an image record for each image which was selected in the select-box
	 */	 	
	private function createRecords(){
		
		$fileNames = explode(',', $this->collection['images']);
		
		foreach($fileNames as $fileName){
				
			if(trim($fileName) && !isset($this->images[$fileName])){
				
				$newRecord = array(
					'pid' => $this->collection['pid'],
					'collection' => $this->collection['uid'],
					'crdate' => time(),
					'cruser_id' => $this->collection['cruser_id'],
					'image' => $fileName,
					'title' => $this->getTitleFromName($fileName)
				);
				$this->db->exec_INSERTquery('tx_gorillary_images', $newRecord);
				$newRecord['uid'] = $this->db->sql_insert_id();
				$this->images[$fileName] = $newRecord;
			}
		}
		
	}

	/**
	 * if an image was removed from the select box, this method deletes the relating 
	 * image record from the DB
	 */	 	 	
	private function deleteRecords(){
	
		$fileNames = explode(',', $this->collection['images']);
		// remove imagerecords which are not used anymore
		foreach($this->images as $filename => $image){
			if(array_search($filename, $fileNames) === false){
		
				$this->db->exec_DELETEquery('tx_gorillary_images', "image='$filename' AND collection='".$this->collection['uid']."'");
				//unset ($this->images[$filename]);
				
			}
		}
	}
	
	/**
	 * generates a title out of a filename (strips extension,...)
	 */	 	
	private function getTitleFromName($name){
		
		$tags = preg_replace("/[^A-Za-z ]/"," ",$name);
		$tagArr = explode(" ",$tags);
		$tags = array();
		foreach($tagArr as $tag){
			if($tag != "png" && $tag != "gif" && $tag != "jpg" && $tag != "jpeg"){
				$tags[] = strtolower($tag);
			}
		}
		return trim(implode(" ",$tags));
	}

	/**
	 * updates the IRRE data in the collection record, so that image records are
	 * displayed correctly beneith the image select box
	 */	 	 	
	private function updateCollectionRecord(){
	
		$collectionId = $this->collection['uid'];
		$imgUids = array();
		$fileNames = $this->collection['images'];
		$fileNames = explode(',', $fileNames);
		
		foreach($fileNames as $filename){
			$imgUids[] = $this->images[$filename]['uid'];
		}
		
		$updateRecord = array('image_records' => implode(',', $imgUids),
							'tstamp' => time());
		
		$this->db->exec_UPDATEquery('tx_gorillary_collections', "uid='$collectionId'", $updateRecord);
	}
}
?>
