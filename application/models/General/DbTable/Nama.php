<?php 

class Latihan_Model_DbTable_Nama extends Zend_Db_Table_Abstract 
{
	
	protected $_tblName = 'tbl_nama';
	protected $_primaryKey = 'idNama';
	
	public function getAllData(){
		$db = Zend_Db_Table::getDefaultAdapter();
		
		$sql = $db->select()
		->from($this->$_tblName);
		
		return $db->fetchAll($sql);
	}
	
	public function getNama($name){
		$db = Zend_Db_Table::getDefaultAdapter();
		
		$sql = $db->select()
		->from($this->$_tblName)
		->where('nama like %'.$name.'%')
		
		return $db->fetchAll($sql);
	}
	
	public function insertData($data){
		$this->insert($data);
	}
	
	public function deleteData($id){
		$this->delete($this->_primaryKey.'='.$id)
	}
	
	public function updateData($data){
		$this->update($data, $this->_primaryKey.'='.$id);
	}
}

?>