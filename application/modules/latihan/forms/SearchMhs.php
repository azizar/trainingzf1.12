<?php 

class Latihan_Form_SearchMhs extends Zend_Form
{
	protected $idNama;
	protected $nama;
	protected $nim;
	protected $tempatlhr;
	protected $tanggallhr;
	
	//Nama
	public function getNama(){
		return $this->nama;
	}
	public function setNama($nama){
		$this->nama = $nama;
	}
	//Nim
	public function getNim(){
		return $this->nim;
	}
	
	public function setNim($nim){
		$this->nim = $nim;
	}
	//Tempat Lahir
	public function getTempatlhr(){
		return $this->tempatlhr;
	}
	
	public function setTempatlhr($tempatlhr){
		$this->tempatlhr = $tempatlhr;
	}
	
	public function getTanggallhr(){
		return $this->tanggallhr;
	}
	
	public function setTanggallhr($tanggallhr){
		$this->tempatlhr = $tanggallhr;
	}
	
	public function init()
	{
		$this->setMethod('post');
		$this->setAttrib('id','serachMhs');
		$this->setAttrib ('method','post');
		
		$this->setAttrib ('action','latihan/hello-world/index');
		
		//add hidden element id
		$this->addElement('hidden', 'idNamaMhs', array ('value'=>$this->idNama));
		
		$this->addElement('text', 'Nama', array(
			'label'=>$this->getView()->translate('Name'),
			'decorators'=>array('ViewHelper')
		));
		
		$this->addElement('text', 'Nim', array(
			'label'=>$this->getView()->translate('NIM'),
			'decorators'=>array('ViewHelper')
		));
		
		$this->addElement('text', 'Tanggallhr', array(
			'label'=>$this->getView()->translate('Date of Birth'),
			'decorators'=>array('ViewHelper')
		));
		
		$this->addElement('text', 'Tempatlhr', array(
			'label'=>$this->getView()->translate('Place of Birth'),
			'decorators'=>array('ViewHelper')
		));
		
		$this->addElement('submit', 'Cancel', array(
			'label'=>$this->getView()->translate('Cancel'),
			'decorators'=>array('ViewHelper')
		));
		
		$this->addElement('submit', 'Save', array(
			'label'=>$this->getView()->translate('Save'),
			'decorators'=>array('ViewHelper')
		));
		
		$this->addElement('submit', 'Search', array(
			'label'=>$this->getView()->translate('Search'),
			'decorators'=>array('ViewHelper')
		));
		
		$this->addElement('submit', 'Delete', array(
			'label'=>$this->getView()->translate('Delete')
		));
		
		$this->addDisplayGroup (
			array ('Save','Delete','Cancel','Search'), 'buttons', array(
			'decorators'=>array(
			'FormElements', array('HtmlTag', array('tag'=>'div', 'class'=>'button')),
			'DtDdWrapper'
			))
		);
	}
	
	
}

?>