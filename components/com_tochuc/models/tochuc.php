<?php
class TochucModelTochuc extends JModelLegacy
{
	public function __construct() {
		JTable::addIncludePath( JPATH_COMPONENT_ADMINISTRATOR.'/tables' );
	}
	public function changeName($dept_id,$name){
		return Core::update('ins_dept', array('id'=>$dept_id,'name'=>$name), 'id');
	}
	public function deleteDept($id){
		$table =  Core::table('Tochuc/InsDept');
		$table->load($id);
		$rows = Core::loadAssocList('ins_dept_quatrinh', array('id'), array('dept_id = '=>$id));
		for ($i = 0; $i < count($rows); $i++) {
			$this->delQuaTrinh($rows[$i]['id']);
		}
		$rows = Core::loadAssocList('ins_dept_quatrinh_bienche', array('id'), array('dept_id = '=>$id));
		for ($i = 0; $i < count($rows); $i++) {
			$this->delQuaTrinhGiaoBienche($rows[$i]['id']);
		}
		return $table->delete();
	}
	public function saveDept($formData){		
		$table =  Core::table('Tochuc/InsDept');
		//var_dump($table);exit;
		$reference_id = (int)$formData['parent_id'];
		$data = array(
				'id'=>$formData['id'],
				'parent_id'=>$formData['parent_id'],
				'name'=>$formData['name'],
				's_name'=>$formData['s_name'],
				'ss_name'=>$formData['ss_name'],
				'code'=>$formData['code'],
				'ins_loaihinh'=>$this->getLoaihinhByIdCap((int)$formData['ins_cap']),
				'ins_cap'=>(int)$formData['ins_cap'],
				'ins_cap_sn'=>$formData['ins_cap_sn'],
				'number_created'=>$formData['number_created'],
				'date_created'=>$formData['date_created'],
				'type_created'=>$formData['type_created'],
				'active'=>$formData['active'],
				'ins_created'=>$formData['ins_created'],
				'chitiet'=>$formData['chitiet'],
				'ghichu'=>$formData['ghichu'],
				'dienthoai'=>$formData['dienthoai'],
				'email'=>$formData['email'],
				'diachi'=>$formData['diachi'],
				'goibienche'=>$formData['goibienche'],
				'goiluong'=>$formData['goiluong'],
				'goichucvu'=>$formData['goichucvu'],
				'type'=>$formData['type'],
				'giao_bc'=>$formData['giao_bc'],
				'number_bc'=>$formData['number_bc'],
				'year_bc'=>$formData['year_bc'],
				'rep_hc_parent_id'=>$formData['rep_hc_parent_id'],
				'rep_hc_exp_lev'=>$formData['rep_hc_exp_lev'],
				'rep_hc_name'=>$formData['rep_hc_name'],
				'rep_sn_parent_id'=>$formData['rep_sn_parent_id'],
				'rep_sn_exp_lev'=>$formData['rep_sn_exp_lev'],
				'rep_bc_parent_id'=>$formData['rep_bc_parent_id'],
				'rep_bc_exp_lev'=>$formData['rep_bc_exp_lev'],
				'rep_bc_name'=>$formData['rep_bc_name'],
				'rep_tkn_parent_id'=>$formData['rep_tkn_parent_id'],
				'masothue'=>$formData['masothue'],
				'masotabmis'=>$formData['masotabmis'],
				'eng_name'=>$formData['eng_name'],
				'fax'=>$formData['fax'],
				'phucapdacthu'=>$formData['phucapdacthu'],
				'ins_level'=>$this->getHangDonviByIdGoiChucvu($formData['goichucvu']),
				'vanban_active'=>$formData['vanban_active'],
				'vanban_created'=>$formData['vanban_created'],
				'goihinhthuchuongluong'=>$formData['goihinhthuchuongluong'],
				'goidaotao'=>$formData['goidaotao'],
				'chucnang'=>$formData['chucnang']
		);
		
		if((int)$formData['id'] == 0){
			// Specify where to insert the new node.
			
			//$reference_id = (int)$formData['parent_id'];
			if ($reference_id == 0 ) {
				$reference_id = $table->getRootId();
			}
			if ($reference_id === false) {
				$reference_id = $table->addRoot();
			}
			$table->setLocation( $reference_id, 'last-child' );
			// Bind data to the table object.
			unset($data['id']);
			foreach ($data as $key => $value) {
				//var_dump($value);
				if ($value == '' || $value == null) {
					unset($data[$key]);
				}
			}	
			//exit;	
			$table->bind( $data );
			// Check that the node data is valid.
			$table->check();		
			// Store the node in the database table.
			$table->store();
			return $table->id;
		}else{
			if($reference_id != Core::loadResult('ins_dept', array('parent_id'), array('id = '=>$formData['id']))){				
				$table->setLocation( $reference_id, 'last-child' );
 			} 		
 			$table->bind($data); 
 			$table->check();
 			$table->store();
 			Core::update('ins_dept', $data, 'id',true);
 			return $table->id;
		}
		//var_dump($data);exit;
		
	}
	public function saveLinhvuc($dept_id,$arrLinhvuc){
		$table = JTable::getInstance( 'TochucLinhvuc', 'TochucTable' );
		Core::delete('ins_dept_linhvuc', array('ins_dept_id = '=>$dept_id));
		for ($i = 0; $i < count($arrLinhvuc); $i++) {
			if ((int)$arrLinhvuc[$i] > 0) {							
				$data = array('ins_dept_id'=>$dept_id,'linhvuc_id'=>(int)$arrLinhvuc[$i]);
				//var_dump($data);
				$table->bind($data);
				$table->check();		
				// Store the node in the database table.
				$table->store();							
			}
		}	
	}

	public function getLinhvucByIdDept($dept_id){
		return Core::loadColumn('ins_dept_linhvuc', array('linhvuc_id'), array('ins_dept_id = '=>(int)$dept_id));
	}
	public function saveVanban($formData){	
		$table = JTable::getInstance( 'Vanban', 'TochucTable' );
		$data = array(
			'id'=>(int)$formData['id'],
			'mahieu'=>$formData['mahieu'],
			'tieude'=>$formData['tieude'],
			'trichdan'=>$formData['trichdan'],
			'ngaybanhanh'=>$formData['ngaybanhanh'],
			'nguoiky'=>$formData['nguoiky'],
			'ordering'=>$formData['ordering'],
			'coquan_banhanh_id'=>$formData['coquan_banhanh_id'],
			'coquan_banhanh'=>$formData['coquan_banhanh'],
			'ngaytao'=>date('Y-m-d H:i:s'),
			'nguoitao'=> JFactory::getUser()->id
				
		);
		//var_dump($data);
		$table->bind( $data );	
		$table->check();
		$table->store();
		return $table->id;
	}
	public function saveTaptin($vanban_id,$fileupload_id) {
		$user = JFactory::getUser();
		$mapper = Core::model('Core/Attachment');
		$type_id = 1;
		if (is_array($fileupload_id)) {
			for ($i = 0; $i < count($fileupload_id); $i++) {
				$mapper->attachment($fileupload_id[$i],$vanban_id,$type_id,$user->id);
			}
		}else{
			if ((int)$fileupload_id > 0 ) {
				$mapper->attachment($fileupload_id,$vanban_id,$type_id,$user->id);
			}
			
		}
		$mapper->clearTempFileByUser($user->id);
	}
// 	public function saveQuatrinh($formData) {
// 		$table = JTable::getInstance( 'Quatrinh', 'TochucTable' );
// 		$data = array(
// 				'id'=>$formData['id'],
// 				'vanban_id'=>$formData['vanban_id'],
// 				'cachthuc_id'=>$formData['cachthuc_id'],
// 				'ngaytao'=>date('Y-m-d H:i:s'),
// 				'user_id'=>JFactory::getUser()->id,
// 				'ghichu'=>$formData['ghichu'],
// 				'ngay_hieuluc'=>$formData['ngay_hieuluc'],
// 				'coquan_chuquan'=>$formData['coquan_chuquan'],
// 				'old_name'=>$formData['old_name'],
// 				'name'=>$formData['name'],
// 				'vanban_cnnv_id'=>$formData['vanban_cnnv_id'],
// 				'trangthai'=>$formData['trangthai'],
// 				'dept_id'=>$formData['dept_id']
	
// 		);
// 		//var_dump($data);
// 		foreach ($data as $key => $value) {
// 			if ($value == '' || $value == null) {
// 				unset($data[$key]);
// 			}
// 		}	
// 		$table->bind( $data );		
// 		$table->check();
// 		$table->store();
// 		return $table->id;
// 	}

	public function delQuaTrinh($quatrinh_id){
		$type_id = 1;
		$attachmentMapper = Core::model('Core/Attachment');
		$flag = false;
		$vanban_id = Core::loadResult('ins_dept_quatrinh', array('vanban_id'),  array('id = '=>$quatrinh_id));		
		if($attachmentMapper->deleteByObjectIdAndTypeId((int)$vanban_id,$type_id)){
			Core::delete('ins_vanban', array('id = '=>(int)$vanban_id));
			Core::delete('ins_dept_quatrinh', array('id = '=>(int)$quatrinh_id));
			$flag = true;
		}		
		return $flag;
	}

	
	public function saveQuatrinh($formData) {
		$table = JTable::getInstance( 'Quatrinh', 'TochucTable' );
		$data = array(
				'id'=>(int)$formData['id'],
				'quyetdinh_so'=>$formData['quyetdinh_so'],
				'quyetdinh_ngay'=>$formData['quyetdinh_ngay'],				
				'user_id'=>JFactory::getUser()->id,
				'ghichu'=>$formData['ghichu'],
				'chitiet'=>$formData['chitiet'],			
				'name'=>$formData['name'],
				'hieuluc_ngay'=>$formData['hieuluc_ngay'],
				'dept_id'=>$formData['dept_id'],
				'cachthuc_id'=>$formData['cachthuc_id'],
				'vanban_id'=>$formData['vanban_id'],
				'ordering'=>$formData['ordering'],				
				'ngay_tao'=>date('Y-m-d H:i:s')
	
		);
		//var_dump($data);
		foreach ($data as $key => $value) {
			if ($value == '' || $value == null) {
				unset($data[$key]);
			}
		}	
		$table->bind( $data );		
		$table->check();
		$table->store();
		return $table->id;
	}
	/*
	 * 
	 */
	public function updatePhanQuyen($dept_id,$parent_id){
		$db = JFactory::getDbo();
		$query = "UPDATE cb_detail_groups_actions SET iddonvi=CONCAT(iddonvi,'".$dept_id."','') WHERE iddonvi LIKE CONCAT('%','".$parent_id."','%'))";
		$db->setQuery($query);
		$db->query();
	}
	
	public function read($id){
		$table =  Core::table('Tochuc/InsDept');
		$table->load($id);
		return $table;
	}
	public function getOneQuaTrinhById($quatrinh_id){
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*')
			->from('ins_dept_quatrinh')
			->where('id = '.$db->q($quatrinh_id));
		$db->setQuery($query);
		return $db->loadAssoc();
	}
	public function getAllQuaTrinhById($dept_id){
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*')
			->from('ins_dept_quatrinh')
			->where('dept_id = '.$db->q($dept_id))
			->order('hieuluc_ngay DESC')
		;
		$db->setQuery($query);
		return $db->loadAssocList();
	}
	public function getVanbanById($vanban_id){
		return Core::loadAssoc('ins_vanban', array('id',
				'mahieu',
				'tieude',
				'trichdan',			
				'ngaybanhanh',
				'nguoiky',
				'ordering',		
				'coquan_banhanh_id',
				'coquan_banhanh',
				'ngaytao',
				'nguoitao'), array('id = '=>(int)$vanban_id));
	}
	public function getFilebyIdVanban($vanban_id){	
		// Type_id đối chiếu table type_filedinhkem
		return Core::loadAssoc('core_attachment', array('*'), array('type_id = '=>1,'object_id = '=>$vanban_id));
	}
	public function saveChangeActive($dept_id,$active){
		$table = Core::table('Tochuc/InsDept');
		$table->load($dept_id);
		$table->active = $active;
		$table->check();
		return $table->store();
	}
	/*
	 * tranferHosoTwoTochuc
	 * @papram integer
	 * @papram integer
	 * @return boolean
	 */
	public function tranferHosoTwoTochuc($dept_from_id,$dept_to_id){	
		$db = JFactory::getDbo();
		//lay tat ca cac hoso thuoc don vi co id dept_from_id 
		$query = $db->getQuery(true);
		$query->select(array('lft','rgt'))
			->from('ins_dept')
			->where('id = '.$db->q($dept_from_id));
		$db->setQuery($query);
		$node = $db->loadResult();
		if ($node != null) {
			$query = 'UPDATE hosochinh_quatrinhhientai 
						SET congtac_phong_id='.$db->q($dept_to_id).' 
							WHERE hosochinh_id IN (
								SELECT a.hosochinh_id 
									FROM hosochinh_quatrinhhientai a
									INNER JOIN ins_dept b ON a.congtac_phong_id = b.id
									WHERE (b.lft >='.$node['lft'].' AND b.rgt <= 6'.$node['lft'].') 
										AND (a.hoso_trangthai = "00" OR a.hoso_trangthai IS NULL)
							)';
			$db->setQuery($query);
			return $db->query();
		}
		return false;
	}
	public function saveSapnhap($formData){
		$table = JTable::getInstance( 'Sapnhap', 'TochucTable' );
		$data = array(
				'dept_chinh_id'=>$formData['dept_chinh_id'],
				'dept_phu_id'=>$formData['dept_phu_id'],
				'quatrinh_id'=>$formData['quatrinh_id'],
				'dept_chinh_name'=>$formData['dept_chinh_name'],
				'dept_phu_name'=>$formData['dept_phu_name']
		);
		foreach ($data as $key => $value) {
			if ($value == '' || $value == null) {
				unset($data[$key]);
			}
		}		
		return $table->save($data);
	}
	public function getQuatrinhBiencheById($id){
		$table = JTable::getInstance( 'Quatrinhbienche', 'TochucTable' );
		$table->load($id);
		return 	$table;	
	}
	public function saveQuatrinhBienche($formData){
		$table = JTable::getInstance( 'Quatrinhbienche', 'TochucTable' );
		$data = array(
				'id'=>$formData['id'],				
				'quyetdinh_so'=>$formData['quyetdinh_so'],
				'quyetdinh_ngay'=>$formData['quyetdinh_ngay'],
				'hieuluc_ngay'=>TochucHelper::strDateVntoMySql($formData['hieuluc_ngay']),
				'user_id'=>JFactory::getUser()->id,
				'ghichu'=>$formData['ghichu'],				
				'dept_id'=>$formData['dept_id'],
				'nghiepvu_id'=>$formData['nghiepvu_id'],
				'ngay_tao'=>date('Y-m-d H:i:s'),
				'vanban_id'=>$formData['vanban_id'],
				'ordering'=>99,
				'nam'=>$formData['nam']
		);
		$table->bind( $data );
		$table->check();
		$table->store();
		return $table->id;	
	}
	public function delQuaTrinhGiaoBienche($quatrinh_id){
		$flag = false;
		$tableQuatrinhbienche = JTable::getInstance( 'Quatrinhbienche', 'TochucTable' );
		//$tableQuatrinhBiencheChitiet = JTable::getInstance( 'QuatrinhBiencheChitiet', 'TochucTable' );
		$tableVanban = JTable::getInstance( 'Vanban', 'TochucTable' );
		$tableQuatrinhbienche->load($quatrinh_id);
		$tableVanban->load($tableQuatrinhbienche->vanban_id);
		Core::delete('ins_dept_quatrinh_bienche_chitiet', array('quatrinh_id = '=>$quatrinh_id));
		if(Core::delete('core_attachment', array('object_id = '=>$tableQuatrinhbienche->vanban_id,'type_id='=>1))){
			if($tableVanban->delete()){
				if($tableQuatrinhbienche->delete()){					
					$flag = true;
				}				
			}
		}
		return $flag;
	}
	public function saveQuatrinhBiencheChitiet($quatrinh_id,$hinhthuc_id,$bienche){
		$table = JTable::getInstance( 'QuatrinhBiencheChitiet', 'TochucTable' );	
		return $table->save(array('quatrinh_id'=>$quatrinh_id,'hinhthuc_id'=>$hinhthuc_id,'bienche'=>$bienche));		
	}
	public function getQuatrinhBiencheByDeptId($dept_id){
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select(array('*'))->from($db->quoteName('ins_dept_quatrinh_bienche'))		
				->where('dept_id = '.$db->q($dept_id));
		$db->setQuery($query);
		return $db->loadAssocList();
	}
	public function getHinhThucBienCheByQuatrinh($quatrinh_id){
		$db = JFactory::getDbo();
		$query = 'SELECT b.id,b.name,a.bienche,a.quatrinh_id FROM bc_hinhthuc b INNER JOIN ins_dept_quatrinh_bienche_chitiet a ON (a.hinhthuc_id = b.id AND a.quatrinh_id = '.$db->q((int)$quatrinh_id).')';
		$db->setQuery($query);
		return $db->loadAssocList();
	}
	public function getHinhThucBienche($goibienche_id){
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		
		$query->select(array('a.id','a.name'))->from($db->quoteName('bc_hinhthuc','a'))
				->join('INNER', 'bc_goibienche_hinhthuc AS b ON a.id = b.hinhthuc_id')
				->where('b.goibienche_id = '.$db->q($goibienche_id));
			
		//$query->select(array('a.ID','a.NAME'))->from($db->quoteName('bc_hinhthuc','a'));
		
		$db->setQuery($query);
		return $db->loadAssocList();
	}
	public function getHangDonviByIdGoiChucvu($goichucvu_id){
		return Core::loadResult('cb_goichucvu', array('ins_level_id'), array('id = '=>(int)$goichucvu_id));
	}
	public function getLoaihinhByIdCap($ins_cap_id){
		/**
		 *  lay node cha cua ins_cap
		1: Hanh chinh
		2: Su Nghiep
		*/
		$db = JFactory::getDbo();
		$query = 'SELECT parent.id FROM ins_cap AS node, ins_cap AS parent WHERE node.lft BETWEEN parent.lft AND parent.rgt AND parent.lft > 0 AND node.id = '.$db->q($ins_cap_id).' GROUP BY parent.id ORDER BY node.lft LIMIT 1';
		$db->setQuery($query);
		return $db->loadResult();
	}
	// Thinh add bienche
	public function sumBienchegiao($id_donvi){
		$year	=	date("Y");
		$db		=	JFactory::getDbo();
		// Tổng biên chế của tất cả đơn vị con
		/* $query	=	'SELECT SUM(a.bienche) FROM ins_dept_quatrinh_bienche_chitiet a
			INNER JOIN ins_dept_quatrinh_bienche b ON b.id = a.quatrinh_id
		INNER JOIN (
				SELECT node.id FROM ins_dept AS node, ins_dept AS parent
				WHERE node.lft BETWEEN parent.lft AND parent.rgt AND parent.id = '.$db->quote($id_donvi).'
		) AS dvbc ON b.dept_id = dvbc.id
		WHERE b.nam = '.$db->quote($year); */
	
		// Tổng biên chế của đơn vị
		$query	=	'SELECT SUM(a.bienche) FROM ins_dept_quatrinh_bienche_chitiet a
		INNER JOIN ins_dept_quatrinh_bienche b ON b.id = a.quatrinh_id
		WHERE b.dept_id = '.$db->quote($id_donvi).' and b.nam = '.$db->quote($year);
		$db->setQuery($query);
		return $db->loadResult();
	
	}
	public function sumBienchehienco($id_donvi){
		$db		=	JFactory::getDbo();
		$query	=	'	SELECT count(hs.hosochinh_id) 
							FROM hosochinh_quatrinhhientai as hs
							INNER JOIN (
								SELECT gbc.hinhthuc_id 
									FROM ins_dept AS ind
									INNER JOIN bc_goibienche_hinhthuc as gbc on ind.goibienche = gbc.goibienche_id
									WHERE ind.id = '.$db->quote($id_donvi).') AS ht ON hs.bienche_hinhthuc_id = ht.hinhthuc_id
							WHERE hs.congtac_donvi_id = '.$db->quote($id_donvi);
		$db->setQuery($query);
		return $db->loadResult();
	
	}
	// #Thinh
	public function checkTochucTrung(){
		$name_tc = JRequest::getVar('name_tc');
		$parent_id = JRequest::getInt('parent_id',null);
		$db = JFactory::getDbo();
		$query = 'SELECT COUNT(*)
						FROM ins_dept
						WHERE LOWER(name) = LOWER('.$db->quote($name_tc).') 
							AND parent_id = '.$db->quote($parent_id);
		$db->setQuery($query);
		return $db->loadResult();
	}
}