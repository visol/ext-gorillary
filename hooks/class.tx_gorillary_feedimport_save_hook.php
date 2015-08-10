<?php
/**
 * creates (or deletes) a record for each image which is selected in the select box of the feedimport record
 * gets triggered when hitting the save button in the backend 
 */
class tx_gorillary_feedimport_save_hook{

	/**
	 * @var t3lib_DB
	 */
	private $db;
	private $feedImport;
	private $images;



    function tx_gorillary_feedimport_save_hook(){
    
    }
    
	function processDatamap_afterDatabaseOperations($status, $table, $id, $fieldArray, $ref){
		$this->db = $GLOBALS['TYPO3_DB'];
		//debug($status);
		
		if($table == "tx_gorillary_feedimports"){
			
			switch($status){
				case "new":
					// we have to retrieve the real uid from the database
					$feedImportUid = $ref->substNEWwithIDs[$id];
					break;
				case "update":
					$feedImportUid = $id;
					break;
			}
			
			$rows = $this->db->exec_SELECTgetRows('*', 'tx_gorillary_feedimports', "uid='$feedImportUid' AND deleted=0");
			$this->feedImport = $rows[0];
			$this->images = $this->getImageRecordsOfFeedImport($this->feedImport);
			$this->createRecords();
			$this->updateFeedImportRecord();
			
		}
	}
	
	function processDatamap_afterAllOperations(&$ref){
		
	}
	
    function processDatamap_postProcessFieldArray($status, $table, $id, $fieldArray, $ref){
		if($table == "tx_gorillary_feedimport"){
			
		}
    }
    function processDatamap_preProcessFieldArray($fieldArray, $table, $id, $ref){
		if($table == "tx_gorillary_feedimport"){
			
		}
    }
	private function getImageRecordsOfFeedImport($feedImport){
		$imageRows = $this->db->exec_SELECTgetRows('*', 'tx_gorillary_images', "deleted=0 AND feedimport='".$feedImport['uid']."' AND pid='".$feedImport['pid']."'");
		
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

        $url = $this->feedImport['feed_url'];        
		// TODO: import image from media feed
        //$fileNames = explode(',', );
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $data = curl_exec($ch);
        curl_close($ch);
		$xml = new SimpleXMLElement($data, LIBXML_NOCDATA);
        $maxImages = 5;


        foreach($xml->channel[0]->item as $item){
            $mediaProperties = $item->children('http://search.yahoo.com/mrss/');
			$mediaContent = $mediaProperties->content[0];

            $imageUrl = $mediaContent->attributes()->url;
            
           
			$fileName = $this->getFilenameFromUrl($imageUrl);
			
            if(trim($fileName) && !isset($this->images[$fileName])){
                $filepath = PATH_site.'uploads/tx_gorillary/'.$fileName;
                $this->downloadFile($imageUrl,$filepath);

                $newRecord = array(
                    'pid' => $this->feedImport['pid'],
                    'feedimport' => $this->feedImport['uid'],
                    'crdate' => time(),
                    'cruser_id' => $this->feedImport['cruser_id'],
                    'image' => $fileName,
                    'title' => trim($item->title),
                    'description' => trim($item->description),
                    'link' => $item->link
                );
                $this->db->exec_INSERTquery('tx_gorillary_images', $newRecord);
                $newRecord['uid'] = $this->db->sql_insert_id();
                $this->images[$fileName] = $newRecord;
            }
			$maxImages--;
			if($maxImages == 0){
				break;
			}
        }
        
	}
    
    private function getFilenameFromUrl($imageUrl) {
        $seg = explode("/",$imageUrl);
        return array_pop($seg);
    }
    private function downloadFile($url,$filePath){
        $ch = curl_init($url);
        $fp = fopen($filePath, 'wb');
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_exec($ch);
        curl_close($ch);
        fclose($fp);
    }
	
	

	/**
	 * updates the IRRE data in the feedimport record, so that image records are
	 * displayed correctly beneith the image select box
	 */	 	 	
	private function updateFeedImportRecord(){
	
		$feedImportUid = $this->feedImport['uid'];
		$imgUids = array();
		
		
		foreach($this->images as $fileName => $image){
			$imgUids[] = $image['uid'];
		}
		
		$updateRecord = array('image_records' => implode(',', $imgUids),
							'tstamp' => time());
		
		$this->db->exec_UPDATEquery('tx_gorillary_feedimports', "uid='$feedImportUid'", $updateRecord);
	}
}
?>
