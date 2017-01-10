<?php

namespace Zogs\DatabaseToolBundle\Importer;

class MySQLImporter
{
	private $files_path;
	private $mysql_path;
	private $dbname;
	private $dbuser;
	private $dbpass;
	private $doctrine;
	private $options = '';
	private $method = 'batch';

	public function __construct($dbname,$dbuser,$dbpass,$doctrine)
	{
		$this->dbname = $dbname;
		$this->dbuser = $dbuser;
		$this->dbpass = $dbpass;
		$this->doctrine = $doctrine;
	}

	/**
	* find all '.sql' files in a directory
	* and try to import them 
	*/
	public function importAll($dir)
	{
		$dir = rtrim($dir,DIRECTORY_SEPARATOR);
		if(!is_dir($dir)) throw new \InvalidArgumentException(sprintf('Import directory %s does not exists', $dir));

		$files = array_diff(scandir($dir), array('..', '.'));

		$res = array();
		foreach($files as $file) {
			if(preg_match('/\.sql$/',$file)){
				
				$res[] = $this->import($dir.DIRECTORY_SEPARATOR.$file);				
			}
		}
		
		return implode('\n',$res);
	}

	/**
	* import a sql file
	*/
	public function import($file)
	{
		if(! file_exists($file)) throw new \InvalidArgumentException(sprintf('SQL file %s does not exists', $file));
		if(! is_readable($file)) throw new \InvalidArgumentException(sprintf('SQL file %s does not have read permission', $file));

		if($this->method == 'batch'){
			return $this->batch_import($file);
		}
		else {
			return $this->dbal_import($file);
		}
	}

	/**
	* import in command line
	*/
	private function batch_import($file)
	{
		$cmd = $this->mysql_path.'mysql -u'.$this->dbuser. ' -p'.$this->dbpass.' '.$this->dbname.' '.$this->getOptions().' < '.$file;
		exec($cmd);

		return $file.' imported';		
	}

	/**
	* import with pdo
	*/
	private function dbal_import($file)
	{
		$sql = file_get_contents($file);
		
		$conn = $this->getDatabaseConnection($this->dbname);
		$stmt = $conn->prepare($sql);
        $stmt->execute();
        
		
		return $file.' executed';
	}

	public function setConfig($config)
	{
		$this->files_path = $config['files_to_import'];
	}

	public function setFiles($files_path)
	{
		$this->files_path = $files_path;
		return $this;
	}

	public function setMethod($method)
	{
		$this->method = $method;
		return $this;
	}

	public function setDatabase($dbname)
	{
		$this->dbname = $dbname;
		return $this;
	}

	public function setOptions(array $options)
	{
		$this->options = $options;
		return $this;
	}

	public function addOption($option)
	{
		$this->options[] = $option;
		return $this;
	}

	public function setMysqlPath($mysql_path)
	{
		$this->mysql_path = $mysql_path;
		return $this;
	}

	public function getOptions()
	{
		if(empty($this->options)) return '';
		$str = '';
		foreach ($this->options as $option) {
			$str .= '--'.$option.' ';
		}
		return $str;
	}


	private function getDatabaseConnection($dbname)
	{
		$defaultdb = $this->doctrine->getManager()->getConnection()->getDatabase();

		if( $defaultdb == $dbname ) return $this->doctrine->getManager()->getConnection();

		return $this->doctrine->getManager($dbname)->getConnection();
	}

	
}