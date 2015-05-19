<?php
/**
 * Author: Phucnh
 * Date created: May 12, 2015
 * Company: DNICT
 */
class Tochuc_Model_Tochuc{
	/**
	 * Lấy all quá trình khen thưởng theo đơn vị id
	 * @param int $donvi_id
	 * @return array
	 */
	public function getAllKhenthuongById($donvi_id){
		return $this->getThongtin('a.*, b.name as hinhthuc', 'ins_quatrinhkhenthuong a', array('left'=>'ins_dmkhenthuongkyluat b ON b.id=a.rew_code_kt'), array("iddonvi_kt= $donvi_id"), 'start_date_kt desc');
	}
	/**
	 * Lấy all quá trình kỷ luật theo đơn vị id
	 * @param int $donvi_id
	 * @return array
	 */
	public function getAllKyluatById($donvi_id){
		return $this->getThongtin('a.*, b.name as hinhthuc', 'ins_quatrinhkyluat a', array('left'=>'ins_dmkhenthuongkyluat b ON b.id=a.rew_code_kl'), array("a.iddonvi_kl= $donvi_id"), 'a.start_date_kl desc');
	}
	/**
	 * Lấy all quá trình khen thưởng + kỷ luật theo đơn vị id
	 * @param int $donvi_id
	 * @return array
	 */
	public function getAllKhenthuongkyluatById($donvi_id){
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query = "select a.*, b.type as ktkl, b.name as hinhthuc from ins_quatrinhkhenthuong a
						left join ins_dmkhenthuongkyluat b on a.rew_code_kt = b.id
						where a.iddonvi_kt = $donvi_id
						union
						select c.*, d.type as ktkl, d.name as hinhthuc from ins_quatrinhkyluat c
						left join ins_dmkhenthuongkyluat d on c.rew_code_kl = d.id
						where c.iddonvi_kl = $donvi_id
						order by start_date_kt desc";
		$db->setQuery($query);
		return $db->loadObjectList();
	}
	/**
	 * Lấy 1 quá trình kỷ luật theo  id
	 * @param int $donvi_id
	 * @return array
	 */
	public function getEditKhenthuongById($id_kt){
		return $this->getThongtin('*', 'ins_quatrinhkhenthuong', null, array("id_kt= $id_kt"), null);
	}
	/**
	 * Lấy 1 quá trình khen thưởng theo  id
	 * @param int $donvi_id
	 * @return array
	 */
	public function getEditKyluatById($id_kl){
		return $this->getThongtin('*', 'ins_quatrinhkyluat', null, array("id_kl= $id_kl"), null);
	}
	/**
	 * Hàm lưu quá trình khen thưởng
	 * @param unknown $formData
	 * @return boolean
	 */
	public function saveKhenthuong($formData){
		$db =  JFactory::getDbo();
		$query = $db->getQuery(true);
			$fields = array(
					$db->quoteName('iddonvi_kt').'='.$db->quote($formData['iddonvi_kt']),
					$db->quoteName('rew_code_kt').'='.$db->quote($formData['rew_code_kt']),
					$db->quoteName('reason_kt').'='.$db->quote($formData['reason_kt']),
					$db->quoteName('approv_number_kt').'='.$db->quote($formData['approv_number_kt']),
					$db->quoteName('approv_unit_kt').'='.$db->quote($formData['approv_unit_kt']),
					$db->quoteName('approv_per_kt').'='.$db->quote($formData['approv_per_kt']),
					$db->quoteName('start_date_kt').'='.$db->quote($formData['start_date_kt']),
					$db->quoteName('end_date_kt').'='.$db->quote($formData['end_date_kt']),
					$db->quoteName('approv_date_kt').'='.$db->quote($formData['approv_date_kt']),
			);
			if (isset($formData['id_kt']) && $formData['id_kt']>0){
				$conditions = array(
						$db->quoteName('id_kt').'='.$db->quote($formData['id_kt'])
				);
				$query->update($db->quoteName('ins_quatrinhkhenthuong'))->set($fields)->where($conditions);
			}
			else{
				$query->insert($db->quoteName('ins_quatrinhkhenthuong'));
				$query->set($fields);
			}
		$db->setQuery($query);
		if (!$db->query()) {
			JError::raiseError(500, $db->getErrorMsg());
			return false;
		} else {
			return true;
		}
	}
	/**
	 * Hàm lưu quá trình kỷ luật
	 * @param unknown $formData
	 * @return boolean
	 */
	public function saveKyluat($formData){
		$db =  JFactory::getDbo();
		$query = $db->getQuery(true);
			$fields = array(
					$db->quoteName('iddonvi_kl').'='.$db->quote($formData['iddonvi_kl']),
					$db->quoteName('rew_code_kl').'='.$db->quote($formData['rew_code_kl']),
					$db->quoteName('reason_kl').'='.$db->quote($formData['reason_kl']),
					$db->quoteName('approv_number_kl').'='.$db->quote($formData['approv_number_kl']),
					$db->quoteName('approv_unit_kl').'='.$db->quote($formData['approv_unit_kl']),
					$db->quoteName('approv_per_kl').'='.$db->quote($formData['approv_per_kl']),
					$db->quoteName('start_date_kl').'='.$db->quote($formData['start_date_kl']),
					$db->quoteName('end_date_kl').'='.$db->quote($formData['end_date_kl']),
					$db->quoteName('approv_date_kl').'='.$db->quote($formData['approv_date_kl']),
			);
			if (isset($formData['id_kl']) && $formData['id_kl']>0){
				$conditions = array(
						$db->quoteName('id_kl').'='.$db->quote($formData['id_kl'])
				);
				$query->update($db->quoteName('ins_quatrinhkyluat'))->set($fields)->where($conditions);
			}
			else{
				$query->insert($db->quoteName('ins_quatrinhkyluat'));
				$query->set($fields);
			}
		$db->setQuery($query);
		if (!$db->query()) {
			JError::raiseError(500, $db->getErrorMsg());
			return false;
		} else {
			return true;
		}
	}
	/**
	 * Xóa quá trình khen thưởng
	 * @param int $id_kt
	 * @return boolean
	 */
	public function removeKhenthuong($id_kt){
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$conditions = array(
				$db->quoteName('id_kt').' IN ('.$db->quote($id_kt).')'
		);
		$query->delete($db->quoteName('ins_quatrinhkhenthuong'));
		$query->where($conditions);
		$db->setQuery($query);
		if (!$db->query()) {
			JError::raiseError(500, $db->getErrorMsg());
			return false;
		} else {
			return true;
		}
	}
	/**
	 * Xóa quá trình kỷ luật
	 * @param int $id_kl
	 * @return boolean
	 */
	public function removeKyluat($id_kl){
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$conditions = array(
				$db->quoteName('id_kl').' IN ('.$db->quote($id_kl).')'
		);
		$query->delete($db->quoteName('ins_quatrinhkyluat'));
		$query->where($conditions);
		$db->setQuery($query);
		if (!$db->query()) {
			JError::raiseError(500, $db->getErrorMsg());
			return false;
		} else {
			return true;
		}
	}
	/**
	 * Hàm lấy thông tin từ 1 table, có thể join thêm bảng và điều kiện, trả về 1 list đối tượng
	 * @param array $field
	 * @param string $table
	 * @param array $arrJoin array(key => value)
	 * @param array $where array(where1, where2)
	 * @param string $order
	 * @return object
	 */
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
}