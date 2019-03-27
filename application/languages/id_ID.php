<?php 

        $config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini', 'development');		
		$params = array('host'=>$config->resources->db->params->host,
					'username' => $config->resources->db->params->username,
					'password'=>$config->resources->db->params->password,
					'dbname'=>$config->resources->db->params->dbname,
					'unix_socket'    => $config->resources->db->params->unix_socket,
					'driver_options' => array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8')
				);
		$db= Zend_Db::factory('Pdo_Mysql', $params);
		
		$sql = 'SELECT * FROM tbl_language where Language = 2'; 
		$result = $db->fetchAll($sql);

			
	 $info=array();
	 foreach($result as $data){
        	$key = $data["system"];
        	$val = $data["arabic"];
        	$info[$key]=$val;        	
      } 
       
	return $info;