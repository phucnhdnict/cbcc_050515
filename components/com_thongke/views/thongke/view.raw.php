<?php
/**
 * Author: Phucnh
 * Date created: Apr 04, 2015
 * Company: DNICT
 */
defined( '_JEXEC' ) or die( 'Restricted access' );
class ThongkeViewThongke extends JViewLegacy {
	function display($tpl = null) {
	  $task = JRequest::getVar('task');
		  switch($task){
	  		case 'dsach_cctsbnn':
	  			$this->dsach_cctsbnn();
	  			break;
	  		case 'dsach_ldhd_ccvc':
	  			$this->dsach_ldhd_ccvc();
	  			break;
	  	}
	  	parent::display($tpl);
	 }

 	public function dsach_cctsbnn(){
 		$model	=	Core::model('Thongke/Thongke');
 		$donvi_id 	=	$_POST['donvi_id'];
 		$ketqua = array();
		$kq=$model->getCCtsbnn($donvi_id,Core::config('cbcc/bienche/tapsu'), array('3','2','25'));
		Core::PrintJson($kq);
 		die;
 	}
 	public function dsach_ldhd_ccvc(){
 		$model	=	Core::model('Thongke/Thongke');
 		$donvi_id 	=	$_POST['donvi_id'];
 		$ketqua = array();
		$kq=$model->getCCtsbnn($donvi_id,'select id from bc_hinhthuc where is_hopdong = 1', array('3','2','25'));
		Core::PrintJson($kq);
 		die;
 	}
}
?>