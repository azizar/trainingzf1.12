<?php
class GeneralSetup_SemesterController extends Base_Base {
	private $lobjsemester;
	private $lobjsemesterForm;
	private $lobjsemestermaster;
	private $lobjinitialconfig;
	private $locale;
	private $registry;
	private $_gobjlog;

	public function init() {
		$this->_gobjlog = Zend_Registry::get ( 'log' ); //instantiate log object
		$this->fnsetObj();
		$this->registry = Zend_Registry::getInstance();
		$this->locale = $this->registry->get('Zend_Locale');

	}

	public function fnsetObj(){
		$this->lobjsemester = new GeneralSetup_Model_DbTable_Semester();
		$this->lobjsemesterForm = new GeneralSetup_Form_Semester ();
		$this->lobjsemestermaster = new GeneralSetup_Model_DbTable_Semestermaster ();
		$this->lobjinitialconfig = new GeneralSetup_Model_DbTable_Initialconfiguration ();
        $this->lobcalendaractivity = new GeneralSetup_Model_DbTable_Activity();
	}

	public function indexAction() {
		$this->view->title="Program Setup";
		$this->view->lobjform = $this->lobjform; //send the lobjuniversityForm object to the view
		$this->view->defaultlanguage = $this->gobjsessionsis->UniversityLanguage;
		//$larrresult = $this->lobjsemester->fngetSemesterDetails(); //get user details
		$larrresult = $this->lobjsemestermaster->fngetSemestermainDetails();

		if(!$this->_getParam('search'))
		unset($this->gobjsessionsis->semesterpaginatorresult);

		$lintpagecount = $this->gintPageCount;;// Definitiontype model
		$lintpage = $this->_getParam('page',1); // Paginator instance
		if(isset($this->gobjsessionsis->semesterpaginatorresult)) {
			$this->view->paginator = $this->lobjCommon->fnPagination($this->gobjsessionsis->semesterpaginatorresult,$lintpage,$lintpagecount);
		} else {
			$this->view->paginator = $this->lobjCommon->fnPagination($larrresult,$lintpage,$lintpagecount);
		}

		if ($this->_request->isPost () && $this->_request->getPost ( 'Search' )) {
			$larrformData = $this->_request->getPost ();
			if ($this->lobjform->isValid ( $larrformData )) {
				$larrresult = $this->lobjsemester->fnSearchSemester( $this->lobjform->getValues () ); //searching the values for the user
				$this->view->paginator = $this->lobjCommon->fnPagination($larrresult,$lintpage,$lintpagecount);
				$this->gobjsessionsis->semesterpaginatorresult = $larrresult;
			}
		}
		if ($this->_request->isPost () && $this->_request->getPost ( 'Clear' )) {
			//$this->_redirect($this->view->url(array('module'=>'generalsetup' ,'controller'=>'semester', 'action'=>'index'),'default',true));
			$this->_redirect( $this->baseUrl . '/generalsetup/semester/index');
		}

	}

	public function newsemesterAction() { //title
		$idUniversity =$this->gobjsessionsis->idUniversity;
                $this->view->defaultlanguage = $this->gobjsessionsis->UniversityLanguage;
		$initialConfig = $this->lobjinitialconfig->fnGetInitialConfigDetails($idUniversity);
		$this->view->title="Add New Semester";
		$this->view->lobjsemesterForm = $this->lobjsemesterForm;

		if($this->locale == 'ar_YE')  {
			$this->view->lobjsemesterForm->SemesterMainStartDate->setAttrib('datePackage',"dojox.date.islamic");
			$this->view->lobjsemesterForm->SemesterMainEndDate->setAttrib('datePackage',"dojox.date.islamic");
		}

		/*if($initialConfig['SemesterCodeType'] == 1 ){
			$this->lobjsemesterForm->SemesterCode->setAttrib('readonly','true');
			$this->lobjsemesterForm->SemesterCode->setValue('xxx-xxx-xxx');
			}else{
			$this->lobjsemesterForm->SemesterCode->setAttrib('required','true');
			}
			$larrsemesterlist = $this->lobjsemester->fnSemesterList();
			$this->lobjsemesterForm->CopyFrom->addMultiOptions($larrsemesterlist);
			$lobjsemester = $this->lobjsemestermaster->fnGetSemestermasterList();
			$this->lobjsemesterForm->Semester->addMultiOptions($lobjsemester);
			$this->lobjsemesterForm->SemesterCopyTo->addMultiOptions($lobjsemester);*/
		$ldtsystemDate = date ( 'Y-m-d H:i:s' );
		$this->view->lobjsemesterForm->UpdDate->setValue( $ldtsystemDate );
		$auth = Zend_Auth::getInstance();
		$this->view->lobjsemesterForm->UpdUser->setValue( $auth->getIdentity()->iduser);
		$this->view->lobjsemesterForm->SemesterMainName->addValidator('Db_NoRecordExists', true, array('table' => 'tbl_semestermaster', 'field' => 'SemesterMainName'));
		$this->view->lobjsemesterForm->SemesterMainName->getValidator('Db_NoRecordExists')->setMessage("Record already exists");
                $this->view->lobjsemesterForm->SemesterMainCode->addValidator('Db_NoRecordExists', true, array('table' => 'tbl_semestermaster', 'field' => 'SemesterMainCode'));
                $this->view->lobjsemesterForm->SemesterMainCode->getValidator('Db_NoRecordExists')->setMessage("Record already exists");
		//$this->lobjsemesterForm->SemesterType->addMultiOptions($semestertypelist);
		if ($this->getRequest()->isPost()) {
			$formData = $formData2 = $this->getRequest()->getPost();
			unset($formData['SemesterCode']);
			unset($formData['StudentIntake']);
			unset($formData['SemesterStartDate']);
			unset($formData['SemesterEndDate']);
			unset($formData['Program']);
			//unset($formData['SemesterStatus']);
                        if (!$this->view->lobjsemesterForm->isValid($formData)) {
                          return $this->render("newsemester");
                        }
			/*echo "<pre>";
			 print_r($formData);die();*/
            
			unset ( $formData ['Save'] );
			unset ( $formData ['Back'] );
			$semesterMasterid = $this->lobjsemestermaster->fnaddSemester($formData);
			$rowCount = count($formData2['SemesterCode']);
			//echo $rowCount;
			$datatype = gettype($formData2['SemesterCode']);
                        //asd($datatype);  
			if($datatype=='array') {
			for($i=0; $i < $rowCount; $i++) {
				$semDetailsData = array();

				if($formData2['SemesterCode'][$i]!='') {
					$condition = " SemesterCode = '".$formData2['SemesterCode'][$i]."'  ";
                                        $resultData = $this->lobjsemester->fncheckdupSemCode($condition);

 					$finalRdata = $resultData[0]['num'];
 					if($finalRdata=='0') {
						$semDetailsData['SemesterCode'] = $formData2['SemesterCode'][$i];
						$semDetailsData['StudentIntake'] = $formData2['StudentIntake'][$i];
						$semDetailsData['SemesterStartDate'] = $formData2['SemesterStartDate'][$i];
						$semDetailsData['SemesterEndDate'] = $formData2['SemesterEndDate'][$i];
						$semDetailsData['Program'] = $formData2['Program'][$i];
						//$semDetailsData['SemesterStatus'] = $formData2['SemesterStatus'][$i];
						$semDetailsData['UpdDate'] = $formData2['UpdDate'];
						$semDetailsData['UpdUser'] = $formData2['UpdUser'];
						$semDetailsData['Semester'] = $semesterMasterid;
						if($semDetailsData['SemesterCode']!='' && $semDetailsData['SemesterStartDate']!='0000-00-00' && $semDetailsData['SemesterEndDate']!='0000-00-00') {
						$this->lobjsemester->fnaddSemester($semDetailsData,$idUniversity,$initialConfig['SemesterCodeType'],$this->lobjinitialconfig); }
 					}
				}
			 } }

			// Write Logs
			$priority=Zend_Log::INFO;
			$larrlog = array ('user_id' => $auth->getIdentity()->iduser,
								  'level' => $priority,
								  'hostname' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
				                  'time' => date ( 'Y-m-d H:i:s' ),
				   				  'message' => 'New Semester Add',
								  'Description' =>  Zend_Log::DEBUG,
								  'ip' => $this->getRequest ()->getServer ( 'REMOTE_ADDR' ) );
			$this->_gobjlog->write ( $larrlog ); //insert to tbl_log

			//$this->_redirect($this->view->url(array('module'=>'generalsetup','controller'=>'semester', 'action'=>'index'),'default',true));	//redirect
			$this->_redirect( $this->baseUrl . '/generalsetup/semester/index/');
		}
	}

	public function editsemesterAction(){

        $this->view->defaultlanguage = $this->gobjsessionsis->UniversityLanguage;
		$this->view->title="Edit Semester";  //title
		$this->view->lobjsemesterForm = $this->lobjsemesterForm;
		
		//print_r($this->lobjsemesterForm);
        //send the lobjuniversityForm object to the view
		if($this->locale == 'ar_YE')  {
			$this->view->lobjsemesterForm->SemesterMainStartDate->setAttrib('datePackage',"dojox.date.islamic");
			$this->view->lobjsemesterForm->SemesterMainEndDate->setAttrib('datePackage',"dojox.date.islamic");
		}

                $this->view->lobjsemesterForm->SemesterMainName->addValidator('Db_NoRecordExists', true, array(
                                               'table' => 'tbl_semestermaster',
                                               'field' => 'SemesterMainName',
                                               'exclude' => array(
                                                               'field' => 'IdSemesterMaster',
                                                               'value' => $this->_getParam('id', 0)
                                               )
                               )
                );

                $this->view->lobjsemesterForm->SemesterMainCode->addValidator('Db_NoRecordExists', true, array(
                                               'table' => 'tbl_semestermaster',
                                               'field' => 'SemesterMainCode',
                                               'exclude' => array(
                                                               'field' => 'IdSemesterMaster',
                                                               'value' => $this->_getParam('id', 0)
                                               )
                               )
                );

                $this->view->lobjsemesterForm->SemesterMainName->getValidator('Db_NoRecordExists')->setMessage("Record already exists");
                $this->view->lobjsemesterForm->SemesterMainCode->getValidator('Db_NoRecordExists')->setMessage("Record already exists");


		//$this->lobjsemesterForm->SemesterCode->setAttrib('readonly','true');

		$idUniversity =$this->gobjsessionsis->idUniversity;
		$initialConfig = $this->lobjinitialconfig->fnGetInitialConfigDetails($idUniversity);
		/*if($initialConfig['SemesterCodeType'] == 1 ){
			$this->lobjsemesterForm->SemesterCode->setAttrib('readonly','true');
			$this->lobjsemesterForm->SemesterCode->setValue('xxx-xxx-xxx');
			}else{
			$this->lobjsemesterForm->SemesterCode->setAttrib('required','true');
			}*/

		$IdSemesterMaster = $this->_getParam('id', 0);
		$result = $this->lobjsemestermaster->fetchAll('IdSemesterMaster  ='.$IdSemesterMaster);

		$result = $result->toArray();
		foreach($result as $semesterresult){
		}
		$this->lobjsemesterForm->populate($semesterresult);
		$this->view->lobjsemesterForm->IdSemesterMaster->setValue( $IdSemesterMaster );
		$this->view->IdSemMas = $IdSemesterMaster;
		$ldtsystemDate = date ( 'Y-m-d H:i:s' );
		$this->view->lobjsemesterForm->UpdDate->setValue( $ldtsystemDate );
		$auth = Zend_Auth::getInstance();
		$this->view->lobjsemesterForm->UpdUser->setValue( $auth->getIdentity()->iduser);

		$larrsemesterdetails = $this->lobjsemester->fnGetSemesterDetails($IdSemesterMaster);
		$larractivitylist    = $this->lobcalendaractivity->fngetActivityActive();
       // print_r($larractivitylist);
        $this->view->larractivitylist = $larractivitylist;
        
        $larraddDrop = $this->lobcalendaractivity->getaddDrop($IdSemesterMaster);
		
        if(isset($larraddDrop[0]))
        {
            /*
             * Rearrange array for display purpose
             */ 
            
            foreach($larraddDrop as $key => $value)
            {
                
                $addDropData['StartDate'.$value['IdActivity']] = $value['StartDate'];
                $addDropData['EndDate'.$value['IdActivity']] = $value['EndDate'];
                $addDropData['id'.$value['IdActivity']] = $value['id'];
            
            }
            
            //$this->lobjcalenderactivityForm->populate($addDropData);
            $this->view->larraddDrop = $addDropData;
            //print_r($addDropData);
        }
        
		$this->view->larrsemesterDetails = $larrsemesterdetails;

		if ($this->getRequest()->isPost()) {
			$formData = $larrvalidatedata = $this->getRequest()->getPost();
			
			$formDataActivity = $formData;
            
            unset($larrvalidatedata['SemesterCode']);
			unset($larrvalidatedata['StudentIntake']);
			unset($larrvalidatedata['SemesterStartDate']);
			unset($larrvalidatedata['SemesterEndDate']);
			
			/*
             * unset for all start and end date from activity 
             */
			
            foreach ($larractivitylist as $index => $activity)
            {
                $startDate = 'StartDate'.$activity['key'];
                $endDate   = 'EndDate'.$activity['key'];
                $id   = 'id'.$activity['key'];
                unset($larrvalidatedata[$startDate]);
                unset($larrvalidatedata[$endDate]);
                unset($larrvalidatedata['id']);
                unset($formData[$startDate]);
                unset($formData[$endDate]);
                unset($formData[$id]);
            }
            
            
			if ($this->lobjsemesterForm->isValid($larrvalidatedata)) {
				$lintIdSemester = $formData ['IdSemesterMaster'];
				$this->lobjsemestermaster->fnupdateSemester($formData,$lintIdSemester);//update university
				$this->lobjsemester->fndeleteSemesterMappings($lintIdSemester);
				$rowCount = count($formData['SemesterCode']);
				for($i=0; $i < $rowCount; $i++) {
					$semDetailsData = array();
					$semDetailsData['SemesterCode'] = $formData['SemesterCode'][$i];
					$semDetailsData['StudentIntake'] = $formData['StudentIntake'][$i];
					$semDetailsData['SemesterStartDate'] = $formData['SemesterStartDate'][$i];
					$semDetailsData['SemesterEndDate'] = $formData['SemesterEndDate'][$i];
					$semDetailsData['Program'] = $formData['Program'][$i];
					//$semDetailsData['SemesterStatus'] = $formData['SemesterStatus'][$i];
					$semDetailsData['UpdDate'] = $formData['UpdDate'];
					$semDetailsData['UpdUser'] = $formData['UpdUser'];
					$semDetailsData['Semester'] = $lintIdSemester;
					$IdSemester = $formData['IdSemester'][$i];
					$this->lobjsemester->fnaddSemester($semDetailsData,$idUniversity,$initialConfig['SemesterCodeType'],$this->lobjinitialconfig);
				}
                
                foreach ($larractivitylist as $index => $activity)
                {
                    $startDate = 'StartDate'.$activity['key'];
                    $endDate   = 'EndDate'.$activity['key'];
                    $id   = 'id'.$activity['key'];
                    if((!empty($formDataActivity[$startDate])) && (!empty($formDataActivity[$endDate])))
                    {
                        /*
                         * if id has a value, update. if not insert 
                         */
                        if(empty($formDataActivity[$id]))
                        {
                            unset($formDataActivity['SemesterMainCode']);
                            //unset($formDataActivity['IdSemesterMaster']);
                            unset($formDataActivity['SemesterMainName']);
                            unset($formDataActivity['SemesterMainDefaultLanguage']);
                            unset($formDataActivity['SemesterMainStartDate']);
                            unset($formDataActivity['SemesterMainEndDate']);
                            unset($formDataActivity['Scheme']);
                            unset($formDataActivity['IsCountable']);
                            unset($formDataActivity['id']);
                            
                            $calendarSchedule = array(
                                'StartDate' => $formDataActivity[$startDate],
                                'EndDate' => $formDataActivity[$endDate],
                                'IdActivity' => $activity['key'],
                                'IdSemesterMaster' => $formDataActivity['IdSemesterMaster']
                            );
                           
                            $this->lobcalendaractivity->fnainsertCalenderActivity($calendarSchedule);
                            
                        }
                        else
                        {
                            
                            unset($formDataActivity['SemesterMainCode']);
                            unset($formDataActivity['IdSemesterMaster']);
                            unset($formDataActivity['SemesterMainName']);
                            unset($formDataActivity['SemesterMainDefaultLanguage']);
                            unset($formDataActivity['SemesterMainStartDate']);
                            unset($formDataActivity['SemesterMainEndDate']);
                            unset($formDataActivity['Scheme']);
                            unset($formDataActivity['IsCountable']);
                           
                            $calendarSchedule = array(
                            'StartDate' => $formDataActivity[$startDate],
                            'EndDate' => $formDataActivity[$endDate],
                            'IdActivity' => $activity['key'],
                            'id' => $formDataActivity[$id]
                            );
                          //print_r($calendarSchedule);
                            $this->lobcalendaractivity->fnupdatecalender($calendarSchedule);
                        }
                        
                    }
                }
                
				// Write Logs
				$priority=Zend_Log::INFO;
				$larrlog = array ('user_id' => $auth->getIdentity()->iduser,
								  'level' => $priority,
								  'hostname' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
				                  'time' => date ( 'Y-m-d H:i:s' ),
				   				  'message' => 'Semester Edit Id=' . $IdSemester,
								  'Description' =>  Zend_Log::DEBUG,
								  'ip' => $this->getRequest ()->getServer ( 'REMOTE_ADDR' ) );
				$this->_gobjlog->write ( $larrlog ); //insert to tbl_log

				//$this->_redirect($this->view->url(array('module'=>'generalsetup','controller'=>'semester', 'action'=>'index'),'default',true));
				$this->_redirect( $this->baseUrl . '/generalsetup/semester/index');
			}
		}
	}

	/**
	 *
	 * Action to handle semester copy
	 */
	public function copysemesterAction() {
		$newSemesterData = array();
		$idUniversity =$this->gobjsessionsis->idUniversity;
		$auth = Zend_Auth::getInstance();
		$initialConfig = $this->lobjinitialconfig->fnGetInitialConfigDetails($idUniversity);
		if($this->getRequest()->isPost()) {
			$formData = $this->getRequest()->getPost();
			unset($formData['Copy']);
			//echo "<pre>";print_r($formData);die;
			$IdSemesterMain = $formData['FromSemester'];
			$semesterInfo = $this->lobjsemestermaster->fngetSemestermainDetails($IdSemesterMain);
			$semesterData = $semesterInfo[0];
			unset($semesterData['IdSemesterMaster']);
			//Create the semester array to be inserted into the database
			$semesterData['SemesterMainName'] = $formData['CopySemesterMainName'];
			$semesterData['SemesterMainCode'] = $formData['CopySemesterMainCode'];
			$semesterData['UpdDate'] = $formData['UpdDate'];
			$semesterData['UpdUser'] = $formData['UpdUser'];
			$newsemestermasterid = $this->lobjsemestermaster->fnaddSemester($semesterData);
			$semesterDetails = $this->lobjsemester->fnGetSemesterDetails($IdSemesterMain,"copy");
			//$semesterDetails = $semesterDetails->toArray();
			foreach($semesterDetails as $semester) {
				unset($semester['IdSemester']);
				$semester['Semester'] = $newsemestermasterid;
				$semester['UpdDate'] = $formData['UpdDate'];
				$semester['UpdUser'] = $formData['UpdUser'];
				$this->lobjsemester->fnaddSemester($semester,$idUniversity,$initialConfig['SemesterCodeType'],$this->lobjinitialconfig);
			}
			// Write Logs
			$priority=Zend_Log::INFO;
			$larrlog = array ('user_id' => $auth->getIdentity()->iduser,
								  'level' => $priority,
								  'hostname' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
				                  'time' => date ( 'Y-m-d H:i:s' ),
				   				  'message' => 'New Semester Copied=',
								  'Description' =>  Zend_Log::DEBUG,
								  'ip' => $this->getRequest ()->getServer ( 'REMOTE_ADDR' ) );
			$this->_gobjlog->write ( $larrlog ); //insert to tbl_log
			$this->_redirect( $this->baseUrl . '/generalsetup/semester/index');
		}
	}

	function deletesemesterdetailsAction() {
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		//Get Po details Id
		$IdSemester = $this->_getParam('id');
		$this->lobjsemester->fnDeleteSemesterDetail($IdSemester);
		echo "1";
	}

	/**
	 * Function to check duplicate records of Semester Code
	 * @author Vipul
	 */
     public function checksemcodeAction() {
     	$this->_helper->layout->disableLayout();
	$this->_helper->viewRenderer->setNoRender();
        $semCode = $this->getRequest()->getParam('value');
        $condition = " SemesterCode = '".$semCode."'  ";
        $conditionmainsem = " SemesterMainCode = '".$semCode."'  ";
        $resultData = $this->lobjsemester->fncheckdupSemCode($condition);
        $resultmainsem = $this->lobjsemester->fncheckdupMainSemCode($conditionmainsem);
        if($resultData[0]['num']!='0' || $resultmainsem[0]['num']!='0') { echo 'false'; } else { echo 'true'; }
        die;

     }
     
    /**
     * Semester list program activity
     * @author Jasdy
     */
    public function semesterListAction()
    {
        $this->view->title="Program Setup";
		$this->view->lobjform = $this->lobjform; //send the lobjuniversityForm object to the view
		$this->view->defaultlanguage = $this->gobjsessionsis->UniversityLanguage;
		//$larrresult = $this->lobjsemester->fngetSemesterDetails(); //get user details
		$larrresult = $this->lobjsemestermaster->fngetSemestermainDetails();

		if(!$this->_getParam('search'))
		unset($this->gobjsessionsis->semesterpaginatorresult);

		$lintpagecount = $this->gintPageCount;;// Definitiontype model
		$lintpage = $this->_getParam('page',1); // Paginator instance
		if(isset($this->gobjsessionsis->semesterpaginatorresult)) {
			$this->view->paginator = $this->lobjCommon->fnPagination($this->gobjsessionsis->semesterpaginatorresult,$lintpage,$lintpagecount);
		} else {
			$this->view->paginator = $this->lobjCommon->fnPagination($larrresult,$lintpage,$lintpagecount);
		}

		if ($this->_request->isPost () && $this->_request->getPost ( 'Search' )) {
			$larrformData = $this->_request->getPost ();
			if ($this->lobjform->isValid ( $larrformData )) {
				$larrresult = $this->lobjsemester->fnSearchSemester( $this->lobjform->getValues () ); //searching the values for the user
				$this->view->paginator = $this->lobjCommon->fnPagination($larrresult,$lintpage,$lintpagecount);
				$this->gobjsessionsis->semesterpaginatorresult = $larrresult;
			}
		}
		if ($this->_request->isPost () && $this->_request->getPost ( 'Clear' )) {
			//$this->_redirect($this->view->url(array('module'=>'generalsetup' ,'controller'=>'semester', 'action'=>'index'),'default',true));
			$this->_redirect( $this->baseUrl . '/generalsetup/semester/semester-list');
		}
    }
    
    public function semesterDetailProgramAction()
    {
        $this->view->defaultlanguage = $this->gobjsessionsis->UniversityLanguage;
		$this->view->title="Edit Semester";  //title
        
        $Program = new GeneralSetup_Model_DbTable_Program();
        $program = $Program->fnGetProgramList();
        $this->view->program = $program;
        
        $idUniversity =$this->gobjsessionsis->idUniversity;
		$initialConfig = $this->lobjinitialconfig->fnGetInitialConfigDetails($idUniversity);
		
		$IdSemesterMaster = $this->_getParam('id', 0);
		$result = $this->lobjsemestermaster->getData($IdSemesterMaster);
        
        if ($this->getRequest()->isPost()) {
			$formData = $this->getRequest()->getPost();
			$larractivitylist    = $this->lobcalendaractivity->fngetActivityActive();
            
			foreach ($larractivitylist as $index => $activity)
            {
                $startDate = 'StartDate'.$activity['key'];
                $endDate   = 'EndDate'.$activity['key'];
                $id   = 'id'.$activity['key'];
                if((!empty($formData[$startDate])) && (!empty($formData[$endDate])))
                {
                    /*
                     * if id has a value, update. if not insert 
                     */
                    if(empty($formData[$id]))
                    {
                        
                        $calendarSchedule = array(
                            'StartDate' => $formData[$startDate],
                            'EndDate' => $formData[$endDate],
                            'IdActivity' => $activity['key'],
                            'IdSemesterMaster' => $formData['IdSemesterMaster'],
                            'IdProgram' => $formData['idProgram']
                        );
                       //print_r($calendarSchedule);
                        $this->lobcalendaractivity->fnainsertCalenderActivity($calendarSchedule);
                        
                    }
                    else
                    {  
                        $calendarSchedule = array(
                        'StartDate' => $formData[$startDate],
                        'EndDate' => $formData[$endDate],
                        'IdActivity' => $activity['key'],
                        'IdProgram' => $formData['idProgram'],
                        'id' => $formData[$id]
                        );
                      //print_r($calendarSchedule);
                        $this->lobcalendaractivity->fnupdatecalender($calendarSchedule);
                    }
                    
                }
            }
        
            //$this->_redirect( $this->baseUrl . '/generalsetup/semester/semester-list');
        }
        
        $this->view->result = $result;
        $this->view->IdSemesterMaster = $IdSemesterMaster;
    }
    
    
    public function semesterProgramActivityAction()
    {
        $this->_helper->layout->disableLayout();
        
        $programId = $this->_getParam('programid',0);
        $IdSemesterMaster = $this->_getParam('semestermaster',0);
        $this->view->lobjsemesterForm = $this->lobjsemesterForm;
        $larractivitylist    = $this->lobcalendaractivity->fngetActivityActive();
      
        $this->view->larractivitylist = $larractivitylist;
        
        $larraddDrop = $this->lobcalendaractivity->getaddDrop($IdSemesterMaster,$programId);
		
        if(isset($larraddDrop[0]))
        {
            /*
             * Rearrange array for display purpose
             */ 
            
            foreach($larraddDrop as $key => $value)
            {
                
                $addDropData['StartDate'.$value['IdActivity']] = $value['StartDate'];
                $addDropData['EndDate'.$value['IdActivity']] = $value['EndDate'];
                $addDropData['id'.$value['IdActivity']] = $value['id'];
            
            }
            
            $this->view->larraddDrop = $addDropData;
        }
        else
        {
            //echo 'sini';
            //Ambil value dari main activity
            $larraddDrop = $this->lobcalendaractivity->getaddDrop($IdSemesterMaster,0);
            if(isset($larraddDrop[0]))
            {
                foreach($larraddDrop as $key => $value)
                {
                    
                    $addDropData['StartDate'.$value['IdActivity']] = $value['StartDate'];
                    $addDropData['EndDate'.$value['IdActivity']] = $value['EndDate'];
                    $addDropData['id'.$value['IdActivity']] = "";
                
                }
                //print_r($addDropData);
                $this->view->larraddDrop = $addDropData;
            }
        }
        
        
    }
}