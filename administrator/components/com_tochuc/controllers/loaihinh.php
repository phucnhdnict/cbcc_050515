<?php
class TochucControllerLoaihinh extends JControllerLegacy{
	function display() {
		$document    =& JFactory::getDocument();
		$viewName    = JRequest::getVar( 'view', 'loaihinh');
		// Get some vars for the view
		$viewLayout = JRequest::getVar( 'layout', 'default');
		//var_dump($viewLayout);
		$viewType   = $document->getType();
		// Get the view
		$view =& $this->getView( $viewName, $viewType);
		// Set the layout
		$view->setLayout($viewLayout);
		$view->display();
	}
	public function saveedit(){
		JSession::checkToken() or die( 'Invalid Token' );		
		$model = JModelLegacy::getInstance('Loaihinh','TochucModel');
		$formData = JRequest::get('post');
		if ($model->save($formData)) {
			$this->setRedirect('index.php?option=com_tochuc&controller=loaihinh','Xử lý thành công','success');
		}else{
			$this->setRedirect('index.php?option=com_tochuc&controller=loaihinh&task=edit&id='.$formData['id'],'Lỗi','error');
		}
							
	}
	public function movenode(){
		$formData = JRequest::get('post');
		$id = JRequest::getInt('id',0);
		$parent_id = JRequest::getInt('ref',0);
		$model = JModelLegacy::getInstance('Loaihinh','TochucModel');
		$result = array();
		if ($model->moveNode($id,$parent_id)) {
			$result['status'] = true;
		}else{
			$result['status'] = false;
		}
		AdminTochucHelper::printJson($result);
	}
	public function delete(){
		$node_id =  JRequest::getInt('id',0);
		$model = JModelLegacy::getInstance('Loaihinh','TochucModel');
		$formData = JRequest::get('post');
		if ($model->delete($node_id)) {
			$this->setRedirect('index.php?option=com_tochuc&controller=loaihinh','Xử lý thành công','success');
		}else{
			$this->setRedirect('index.php?option=com_tochuc&controller=loaihinh&task=edit&id='.$formData['id'],'Lỗi','error');
		}
			
	}
}