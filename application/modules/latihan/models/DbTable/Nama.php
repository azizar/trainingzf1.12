<?php

class Latihan_Model_DbTable_Nama extends Zend_Db_Table_Abstract
{

	protected $_name = "tbl_nama";
	protected $_primary = "idNama";

	public function getAllData(){
		$db = Zend_Db_Table::getDefaultAdapter();
		$sql = $db->select()
			->from($this->_name);

		return $db->fetchAll($sql);
	}

	public function getById($id){
		$db = Zend_Db_Table::getDefaultAdapter();
		$sql = $db->select()
		->from($this->_name)
		->where(" idNama = ?",$id);

		return $db->fetchRow($sql);
	}

	public function getNama($nama){
		$db = Zend_Db_Table::getDefaultAdapter();
		$sql = $db->select()
		->from($this->_name)
		->where(" nama like '%".$nama."%'");

		return $db->fetchAll($sql);
	}

	public function getNim($nim){
		$db = Zend_Db_Table::getDefaultAdapter();
		$sql = $db->select()
		->from($this->_name)
		->where(" nim like '%".$nim."%'");
			
		return $db->fetchAll($sql);
	}

	public function insertData($data){
		$this->insert($data);
	}

	public function deleteData($id){
		$this->delete($this->_primary.'='.$id);
	}

	public function updateData($data, $id){
		$this->update($data, $this->_primary.'='.$id);
	}
}

?>