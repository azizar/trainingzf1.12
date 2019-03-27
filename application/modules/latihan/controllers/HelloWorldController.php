<?php 

class Latihan_HelloWorldController extends Base_Base {
	
	function indexAction() {
		
		$dbName = new Latihan_Model_DbTable_Nama();
				
		if($this->_request->isPost()) {
		$larrformData = $this->_request->getPost();
			if (isset($larrformData['Save'])) {
				if ($larrformData['id']=='') {
					$dbName->insertData(array(
							'nama' => $larrformData['nama'],
							'alamat' => $larrformData['alamat'],
							'tempat_lahir' => $larrformData['tempat'],
							'tanggal_lahir' => $larrformData['tanggal'],
							'nim' => $larrformData['nimreg']
						));
				} else {
					$dbName->updateData(array(
							'nama' => $larrformData['nama'],
							'alamat' => $larrformData['alamat'],
							'tempat_lahir' => $larrformData['tempat'],
							'tanggal_lahir' => $larrformData['tanggal'],
							'nim' => $larrformData['nimreg']
						), $larrformData['id']);
				}
			}else if (isset($larrformData['Delete'])){
				$dbName->deleteData($larrformData['idNama']);
			} else if (isset($larrformDataS['Cancel'])) {
				$this->view->data = array('id'=>'', 'nama'=>'');
			} else if (isset($larrformData['Search'])){
				$nim = $larrformData['nim'];
				$this ->view->listnama = $dbName->getNim($nim);
				
			}else {
				$id = $larrformData['idNama'];
				$this ->view->student = $dbName->getById($id);
				//echo var_dump($this ->view->student);exit;
			}
		}
				
		$this->view->namalist = $dbName->getAllData();
		
	}
	
}

?>