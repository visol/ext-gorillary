<?php
class user_imagefield {

	function __construct() {

	}
	function getHtml($PA, $fobj)    {
		//debug($PA);
		$imageUrl = '../uploads/tx_gorillary/'.$PA['row']['image'];
		
		// onchange="typo3form.fieldGet('data[tx_gorillary_images][12][title]','required','',0,'');"
		$html = '<input type="text" maxlength="256" onchange="'.$PA['fieldChangeFunc']['TBE_EDITOR_fieldChanged'].'" value="'.$PA['itemFormElValue'].'" name="'.$PA['itemFormElName'].'_hr" class="formField2 tceforms-textfield" id="tceforms-textfield-4c86afa346393">';
		$html .= '<a href="'.$imageUrl.'" target="_blank"><img src="'.$imageUrl.'" border="0" width="50" style="float: left; margin: 3px 5px 6px 0px;"/></a>';
		return $html;
	}

}

?>
