<?php
class TochucViewGoibienche extends JViewLegacy
{
    /**
     * @since  1.5
     */
    public function display($tpl = null)
    {    	
    	$task = JFactory::getApplication()->input->get('task');
    	$task = ($task == null)?'default':strtoupper($task);        
        $this->setLayout(strtolower($task));        
        switch($task){
        	case 'EDIT':        		       
        		$this->_initEditPage();
        		break;
            default:
                $this->_initDefaultPage();
                break;
        }
        parent::display($tpl);
    }
    
    private function _initEditPage(){
    	$id = JRequest::getInt('id');
    	$model = JModelLegacy::getInstance('Goibienche','TochucModel');
    	if ((int)$id > 0 ) {
    		$row = $model->read($id);    		
    	}else{
    		$row = new stdClass();
    		$row->active = 1;    		
    	}
    	
    	//var_dump($row);
     	$hinhthuc = $model->getHinhThucBiencheById($id);
    	$this->assignRef('hinhthuc', $hinhthuc);
    	$this->assignRef('row', $row);
    }
    
    private function _initDefaultPage(){
     	$document = JFactory::getDocument();
//     	//$document->addCustomTag('<link href="'.JURI::base(true).'/media/cbcc/js/jquery/select2/select2.css" rel="stylesheet" />');
     	$document->addCustomTag('<link href="'.JURI::root(true).'/media/cbcc/js/jstree/themes/default/style.css" rel="stylesheet" />');
//     	$document->addScript(JURI::base(true).'/media/cbcc/js/jquery/jquery.dataTables.min.js');
//     	$document->addScript(JURI::base(true).'/media/cbcc/js/jquery/jquery.dataTables.bootstrap.js');
     	$document->addScript(JURI::root(true).'/media/jui/js/jquery.min.js');
     	$document->addScript(JURI::root(true).'/media/cbcc/js/jstree/jquery.jstree.js');     	
//     	//$document->addScript(JURI::base(true).'/media/cbcc/js/jquery/select2/select2.min.js');
//     	$document->addScript(JURI::base(true).'/media/cbcc/js/jquery/chosen.jquery.min.js');
    }   
}
