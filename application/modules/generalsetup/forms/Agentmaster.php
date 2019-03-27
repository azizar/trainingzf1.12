<?php
class GeneralSetup_Form_Agentmaster extends Zend_Dojo_Form { //Formclass for the user module
    public function init() {
    	$gstrtranslate =Zend_Registry::get('Zend_Translate'); 	
    	
    	$month= date("m"); // Month value
		$day= date("d"); //today's date
		$year= date("Y"); // Year value
		$yesterdaydate= date('Y-m-d', mktime(0,0,0,$month,($day),$year));
		$joiningdate = "{max:'$yesterdaydate',datePattern:'dd-MM-yyyy'}"; 
    
    	$IdAgentMaster = new Zend_Form_Element_Hidden('IdAgentMaster');
        $IdAgentMaster->removeDecorator("DtDdWrapper");
        $IdAgentMaster->removeDecorator("Label");
        $IdAgentMaster->removeDecorator('HtmlTag');
        
        $AgentName = new Zend_Form_Element_Text('AgentName');
        $AgentName->setAttrib('dojoType',"dijit.form.ValidationTextBox");
        $AgentName->setAttrib('required',"true")
        		 ->setAttrib('maxlength','50');                                                  
        $AgentName->removeDecorator("DtDdWrapper");
        $AgentName->removeDecorator("Label");
        $AgentName->removeDecorator('HtmlTag');
        
        $AgentAdd1 = new Zend_Form_Element_Text('AgentAdd1');
        $AgentAdd1->setAttrib('dojoType',"dijit.form.ValidationTextBox");
        $AgentAdd1->setAttrib('required',"true")
        		 ->setAttrib('maxlength','50');
        $AgentAdd1->removeDecorator("DtDdWrapper");
        $AgentAdd1->removeDecorator("Label");
        $AgentAdd1->removeDecorator('HtmlTag');
       
        $AgentAdd2 = new Zend_Form_Element_Text('AgentAdd2');	
        $AgentAdd2->setAttrib('dojoType',"dijit.form.ValidationTextBox");
        $AgentAdd2->setAttrib('maxlength','50');
        $AgentAdd2->removeDecorator("DtDdWrapper");
        $AgentAdd2->removeDecorator("Label");
        $AgentAdd2->removeDecorator('HtmlTag');
        
        $Country = new Zend_Dojo_Form_Element_FilteringSelect('Country');
        $Country->removeDecorator("DtDdWrapper");
        $Country->setAttrib('required',"true") ;
        $Country->removeDecorator("Label");
        $Country->removeDecorator('HtmlTag');
        $Country->setAttrib('OnChange', 'fnGetCountryStateList');
        $Country->setRegisterInArrayValidator(false);
		$Country->setAttrib('dojoType',"dijit.form.FilteringSelect");
		
		$State = new Zend_Dojo_Form_Element_FilteringSelect('State');
        $State->removeDecorator("DtDdWrapper");
        $State->setAttrib('required',"true") ;
        $State->removeDecorator("Label");
        $State->removeDecorator('HtmlTag');
        $State->setRegisterInArrayValidator(false);
        $State->setAttrib('OnChange', 'fnGetStateCityList');
		$State->setAttrib('dojoType',"dijit.form.FilteringSelect");
                                            
                  
       	$City = new Zend_Dojo_Form_Element_FilteringSelect('City');
        $City->removeDecorator("DtDdWrapper");
        $City->removeDecorator("Label");
        $City->removeDecorator('HtmlTag');
        $City->setAttrib('required',"true");
        $City->setRegisterInArrayValidator(false);
		$City->setAttrib('dojoType',"dijit.form.FilteringSelect");	 
               
        $Phonecountrycode = new Zend_Form_Element_Text('Phonecountrycode',array('regExp'=>"[0-9]+",'invalidMessage'=>"Only digits"));
		$Phonecountrycode->setAttrib('dojoType',"dijit.form.ValidationTextBox");	
        $Phonecountrycode->setAttrib('maxlength','3');  
        $Phonecountrycode->setAttrib('style','width:30px');  
        $Phonecountrycode->setAttrib('required',"true");
        $Phonecountrycode->removeDecorator("DtDdWrapper");
        $Phonecountrycode->removeDecorator("Label");
        $Phonecountrycode->removeDecorator('HtmlTag');
        
        $Phonestatecode = new Zend_Form_Element_Text('Phonestatecode',array('regExp'=>"[0-9]+",'invalidMessage'=>"Only digits"));
		$Phonestatecode->setAttrib('dojoType',"dijit.form.ValidationTextBox");	
        $Phonestatecode->setAttrib('maxlength','2');
        $Phonestatecode->setAttrib('style','width:30px'); 
        $Phonestatecode->setAttrib('required',"true"); 
        $Phonestatecode->removeDecorator("DtDdWrapper");
        $Phonestatecode->removeDecorator("Label");
        $Phonestatecode->removeDecorator('HtmlTag'); 
        
        $Phone = new Zend_Form_Element_Text('Phone',array('regExp'=>"[0-9()+-]+",'invalidMessage'=>"Not a valid Phone No."));
		$Phone->setAttrib('dojoType',"dijit.form.ValidationTextBox");	
        $Phone->setAttrib('maxlength','9');
        $Phone->setAttrib('style','width:93px');   
        $Phone->setAttrib('required',"true");
        $Phone->removeDecorator("DtDdWrapper");
        $Phone->removeDecorator("Label");
        $Phone->removeDecorator('HtmlTag'); 
                  
        $faxcountrycode = new Zend_Form_Element_Text('faxcountrycode',array('regExp'=>"[0-9]+",'invalidMessage'=>"Only digits"));
		$faxcountrycode->setAttrib('dojoType',"dijit.form.ValidationTextBox");	
        $faxcountrycode->setAttrib('maxlength','3');  
        $faxcountrycode->setAttrib('style','width:30px');  
        $faxcountrycode->removeDecorator("DtDdWrapper");
        $faxcountrycode->removeDecorator("Label");
        $faxcountrycode->removeDecorator('HtmlTag');
        
        $faxstatecode = new Zend_Form_Element_Text('faxstatecode',array('regExp'=>"[0-9]+",'invalidMessage'=>"Only digits"));
		$faxstatecode->setAttrib('dojoType',"dijit.form.ValidationTextBox");	
        $faxstatecode->setAttrib('maxlength','5');  
        $faxstatecode->setAttrib('style','width:30px');  
        $faxstatecode->removeDecorator("DtDdWrapper");
        $faxstatecode->removeDecorator("Label");
        $faxstatecode->removeDecorator('HtmlTag');
        
        $Fax = new Zend_Form_Element_Text('Fax',array('regExp'=>"[0-9()+-]+",'invalidMessage'=>"Not a valid Fax"));
		$Fax->setAttrib('dojoType',"dijit.form.ValidationTextBox");	
		$Fax->setAttrib('style','width:93px'); 
        $Fax->setAttrib('maxlength','20');
        $Fax->removeDecorator("DtDdWrapper");
        $Fax->removeDecorator("Label");
        $Fax->removeDecorator('HtmlTag');
        
        $Email = new Zend_Form_Element_Text('Email',array('regExp'=>"^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.[a-zA-Z]{2,3})$",'invalidMessage'=>"Not a valid email"));
		$Email->setAttrib('dojoType',"dijit.form.ValidationTextBox");
        $Email->setAttrib('required',"true")  			 
        		->setAttrib('maxlength','50')         		     
        		->removeDecorator("DtDdWrapper")
        	    ->removeDecorator("Label")
        		->removeDecorator('HtmlTag');
        $passwd = new Zend_Form_Element_Password('passwd',array('regExp'=>"[\w]+[@#$%^&*?>)(<!~+-/}{=_]+[\w@#$%^&*!~+-/]*",'invalidMessage'=>"No Spaces Allowed and One special Character required"));
        $passwd->setAttrib('dojoType',"dijit.form.ValidationTextBox");
        $passwd->setAttrib('required',"true") ;
        $passwd->removeDecorator("DtDdWrapper");
        $passwd->removeDecorator("Label");
        $passwd->removeDecorator('HtmlTag');		
	    
        $URL = new Zend_Form_Element_Text('URL',array('regExp'=>"^(www)\.([a-zA-Z0-9\.\-]+(\:[a-zA-Z0-9\.&amp;%\$\-]+)*@)*((25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9])\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9]|0)\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9]|0)\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[0-9])|localhost|([a-zA-Z0-9\-]+\.)*[a-zA-Z0-9\-]+\.(com|edu|gov|int|mil|net|org|biz|arpa|info|name|pro|aero|coop|museum|[a-zA-Z]{2}))(\:[0-9]+)*(/($|[a-zA-Z0-9\.\,\?\'\\\+&amp;%\$#\=~_\-]+))*$"));
		$URL->setAttrib('dojoType',"dijit.form.ValidationTextBox");
        $URL->setAttrib('required',"false");      
        $URL->setAttrib('title',"http://www.trisakti.com")       			 
        		->setAttrib('maxlength','255')       
        		->removeDecorator("DtDdWrapper")
        	    ->removeDecorator("Label")
        		->removeDecorator('HtmlTag');
        
        $ContactPerson = new Zend_Form_Element_Text('ContactPerson');	
        $ContactPerson->setAttrib('dojoType',"dijit.form.ValidationTextBox")  
        			->setAttrib('maxlength','100');        
        $ContactPerson->removeDecorator("DtDdWrapper");
        $ContactPerson->removeDecorator("Label");
        $ContactPerson->removeDecorator('HtmlTag');
              
        $Desgination = new Zend_Dojo_Form_Element_FilteringSelect('Desgination');
        $Desgination->removeDecorator("DtDdWrapper");
        $Desgination->setAttrib('required',"false") ;
        $Desgination->removeDecorator("Label");
        $Desgination->removeDecorator('HtmlTag');
        $Desgination->setRegisterInArrayValidator(false);
		$Desgination->setAttrib('dojoType',"dijit.form.FilteringSelect");
              
        
        $ContactPhonecountrycode = new Zend_Form_Element_Text('ContactPhonecountrycode',array('regExp'=>"[0-9]+",'invalidMessage'=>"Only digits"));
		$ContactPhonecountrycode->setAttrib('dojoType',"dijit.form.ValidationTextBox");	
        $ContactPhonecountrycode->setAttrib('maxlength','3');  
        $ContactPhonecountrycode->setAttrib('style','width:30px');  
        $ContactPhonecountrycode->removeDecorator("DtDdWrapper");
        $ContactPhonecountrycode->removeDecorator("Label");
        $ContactPhonecountrycode->removeDecorator('HtmlTag');
        
        $ContactPhonestatecode = new Zend_Form_Element_Text('ContactPhonestatecode',array('regExp'=>"[0-9]+",'invalidMessage'=>"Only digits"));
		$ContactPhonestatecode->setAttrib('dojoType',"dijit.form.ValidationTextBox");	
        $ContactPhonestatecode->setAttrib('maxlength','2');
        $ContactPhonestatecode->setAttrib('style','width:30px');  
        $ContactPhonestatecode->removeDecorator("DtDdWrapper");
        $ContactPhonestatecode->removeDecorator("Label");
        $ContactPhonestatecode->removeDecorator('HtmlTag'); 
        
        $ContactPhone = new Zend_Form_Element_Text('ContactPhone',array('regExp'=>"[0-9()+-]+",'invalidMessage'=>"Not a valid Phone No."));
		$ContactPhone->setAttrib('dojoType',"dijit.form.ValidationTextBox");	
        $ContactPhone->setAttrib('maxlength','9');
        $ContactPhone->setAttrib('style','width:93px');   
        $ContactPhone->removeDecorator("DtDdWrapper");
        $ContactPhone->removeDecorator("Label");
        $ContactPhone->removeDecorator('HtmlTag'); 
                          
       
        $countrycode = new Zend_Form_Element_Text('countrycode',array('regExp'=>"[0-9]+",'invalidMessage'=>"Only digits"));
		$countrycode->setAttrib('dojoType',"dijit.form.ValidationTextBox");	
        $countrycode->setAttrib('maxlength','4');  
        $countrycode->setAttrib('style','width:50px');  
        $countrycode->removeDecorator("DtDdWrapper");
        $countrycode->removeDecorator("Label");
        $countrycode->removeDecorator('HtmlTag');
        
        $ContactCell = new Zend_Form_Element_Text('ContactCell',array('regExp'=>"[0-9]+",'invalidMessage'=>"Only digits"));
		$ContactCell->setAttrib('dojoType',"dijit.form.ValidationTextBox");	
        $ContactCell->setAttrib('maxlength','20');  
        $ContactCell->setAttrib('style','width:109px');  
        $ContactCell->removeDecorator("DtDdWrapper");
        $ContactCell->removeDecorator("Label");
        $ContactCell->removeDecorator('HtmlTag');
        
        $EffectiveDate = new Zend_Form_Element_Text('EffectiveDate');
		$EffectiveDate->setAttrib('dojoType',"dijit.form.DateTextBox");
        $EffectiveDate->setAttrib('required',"true")
			     ->setAttrib('constraints', "$joiningdate")	 
        		->removeDecorator("DtDdWrapper")
        	    ->removeDecorator("Label")
        		->removeDecorator('HtmlTag');
        		
        		
        $AgentField1 = new Zend_Form_Element_Text('AgentField1');
        $AgentField1->setAttrib('dojoType',"dijit.form.ValidationTextBox") 	 
        		->setAttrib('maxlength','20')       
        		->removeDecorator("DtDdWrapper")
        	    ->removeDecorator("Label")
        		->removeDecorator('HtmlTag');
        		
        $AgentField2 = new Zend_Form_Element_Text('AgentField2');
        $AgentField2->setAttrib('dojoType',"dijit.form.ValidationTextBox")        			 
        		->setAttrib('maxlength','20')       
        		->removeDecorator("DtDdWrapper")
        	    ->removeDecorator("Label")
        		->removeDecorator('HtmlTag');
        $AgentField3 = new Zend_Form_Element_Text('AgentField3');
        $AgentField3->setAttrib('dojoType',"dijit.form.ValidationTextBox") 	 
        		->setAttrib('maxlength','20')       
        		->removeDecorator("DtDdWrapper")
        	    ->removeDecorator("Label")
        		->removeDecorator('HtmlTag');
        		
        $AgentField4 = new Zend_Form_Element_Text('AgentField4');
        $AgentField4->setAttrib('dojoType',"dijit.form.ValidationTextBox")        			 
        		->setAttrib('maxlength','20')       
        		->removeDecorator("DtDdWrapper")
        	    ->removeDecorator("Label")
        		->removeDecorator('HtmlTag');
        $AgentField5 = new Zend_Form_Element_Text('AgentField5');
        $AgentField5->setAttrib('dojoType',"dijit.form.ValidationTextBox")        			 
        		->setAttrib('maxlength','20')       
        		->removeDecorator("DtDdWrapper")
        	    ->removeDecorator("Label")
        		->removeDecorator('HtmlTag');				
        
       
        $Active  = new Zend_Form_Element_Checkbox('Active');
        $Active->setAttrib('dojoType',"dijit.form.CheckBox");
        $Active->setvalue('1');
        $Active->removeDecorator("DtDdWrapper");
        $Active->removeDecorator("Label");
        $Active->removeDecorator('HtmlTag');
                          
        $Save = new Zend_Form_Element_Submit('Save');
        $Save->dojotype="dijit.form.Button";
        $Save->label = $gstrtranslate->_("Save");
        $Save->removeDecorator("DtDdWrapper");
        $Save->removeDecorator("Label");
        $Save->removeDecorator('HtmlTag')
         		->class = "NormalBtn";
        
        $UpdDate = new Zend_Form_Element_Hidden('UpdDate');
        $UpdDate->removeDecorator("DtDdWrapper");
        $UpdDate->removeDecorator("Label");
        $UpdDate->removeDecorator('HtmlTag');
        
        $UpdUser = new Zend_Form_Element_Hidden('UpdUser');
        $UpdUser->removeDecorator("DtDdWrapper");
        $UpdUser->removeDecorator("Label");
        $UpdUser->removeDecorator('HtmlTag');	

        
        
        $IdProgram = new Zend_Form_Element_MultiCheckbox('IdProgram');
        $IdProgram->setAttrib('required',"true");
        $IdProgram->removeDecorator("DtDdWrapper"); 
        $IdProgram->removeDecorator("Label");
        $IdProgram->removeDecorator('HtmlTag');
		$IdProgram->setAttrib('dojoType',"dijit.form.CheckBox");
		$IdProgram->class = "IdProgram";
         
        $this->addElements(array($IdAgentMaster,
        						 $AgentName,
        						 $AgentAdd1,
        						 $AgentAdd2,
        						 $Country,
        						 $State,
        						 $City,
        						 $Phonecountrycode,
        						 $Phonestatecode,
        						 $Phone,
        						 $faxcountrycode,
        						 $faxstatecode,
        						 $Fax,
        						 $Email,
        						 $passwd,
        						 $URL,
        						 $ContactPerson,
        						 $Desgination,
        						 $ContactPhonecountrycode,
        						 $ContactPhonestatecode,
        						 $ContactPhone,
        						 $countrycode,
        						 $ContactCell,
        						 $EffectiveDate,
        						 $AgentField1,
        						 $AgentField2,
        						 $AgentField3,
        						 $AgentField4,
        						 $AgentField5,
        						 $Active,
        						 $Save,
        						 $UpdDate,
        						 $UpdUser,
        						 $IdProgram
			        				));
    }
}