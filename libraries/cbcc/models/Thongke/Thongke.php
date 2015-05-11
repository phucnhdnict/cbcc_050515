<?php
defined('_JEXEC') or die( 'Restricted access' );
class Thongke_Model_Thongke extends JModelLegacy{
	function __construct() {
		parent::__construct ();
		global $mainframe;
		$mainframe = JFactory::getApplication ();
	}
	function getThongtin($field, $table, $arrJoin=null, $where=null, $order=null){
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select($field)
		->from($table);
		if (count($arrJoin)>0)
			foreach ($arrJoin as $key=>$val){
				$query->join($key, $val);
			}
		for($i=0;$i<count($where);$i++)
			if ($where[$i]!='')
				$query->where($where);
		if($order!=null)$query->order($order);
		$db->setQuery($query);
		return $db->loadObjectList();
	}
	
	public function getCCtsbnn($donvi_id, $hinhthuc, $bienche_id){
	$str_bienche_id = implode(',', $bienche_id);
	$db = JFactory::getDbo();
	$query = $db->getQuery(true);
	$query= 'select distinct(hsc.id), hsc.e_name,
			date_format(qtbienche_first.ngaybatdau, "%d/%m/%Y") as ngaytapsu,
			date_format(qtbienche_second.ngaybatdau, "%d/%m/%Y") ngaybonhiemngach, 
			qthientai.ngaysinh, qthientai.danhdaunamsinh, 
			qthientai.luong_tenngach, qthientai.luong_bac, qthientai.luong_heso, 
			qthientai.congtac_chucvu, qthientai.congtac_phong_id, ins.name congtac_phong   
			from hosochinh hsc
			join bc_quatrinhbienche qtbienche_first on qtbienche_first.emp_id_bc = hsc.id
			join bc_quatrinhbienche qtbienche_second on qtbienche_second.emp_id_bc = hsc.id
			join hosochinh_quatrinhhientai  qthientai on qthientai.hosochinh_id= hsc.id 
			left join ins_dept ins on qthientai.congtac_phong_id = ins.id
			where
			qthientai.congtac_donvi_id ='.$donvi_id.'
					AND qtbienche_second.ngaybatdau >= qtbienche_first.ngaybatdau
				AND qtbienche_first.hinhthuc_id IN ('.$hinhthuc.')
						and qtbienche_second.hinhthuc_id in ('.$str_bienche_id.')
						and qtbienche_second.id = ( 
								-- lấy ra id của hình thức ngay sau hình thức tập sự
					select id from bc_quatrinhbienche c
					where c.emp_id_bc= hsc.id
					AND c.ngaybatdau>= qtbienche_first.ngaybatdau
					order by (c.hinhthuc_id IN ('.$hinhthuc.')) desc ,c.ngaybatdau asc
					limit 1,1
					)';
		$db->setQuery($query);
			return $db->loadObjectList();
	}
}