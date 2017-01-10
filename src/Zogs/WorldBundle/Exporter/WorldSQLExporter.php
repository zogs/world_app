<?php

namespace Zogs\WorldBundle\Exporter;

use Symfony\Component\HttpFoundation\Request;

class WorldSQLExporter 
{
	private $export_dir;
	private $file_name;
	private $dropTable = false;
	private $createTable = false;
	private $countries = array();

	public function __construct($exporter)
	{
		$this->exporter = $exporter;
	}

	public function setConfig($config)
	{
		$this->export_dir =  $config['export_dir'];
		$this->file_name = $config['file_name'];	
		return $this;
	}

	public function setExportDir($dir)
	{
		$this->export_dir = $dir;
		return $this;
	}

	public function export()
	{

        $file = $this->exporter->setTables(array('world_country','world_states','world_cities'))
        				->setWhere('CC1 IN ('.$this->getCountriesCodeString().')')
        				->setFilename($this->getFileName())
        				->setFilePath($this->getExportDir())
						->setDropTable($this->dropTable)
						->setCreateTable($this->createTable)
						->export()
        				;	            

        return basename($file);
	}

	public function setCountries($countries)
	{
		$this->countries = $countries;
	}

	private function getCountriesNameString()
	{
		$str = '';
		foreach ($this->countries as $country) {
			$str .= '_'.strtolower($country->getName());
		}
		return $str = rtrim($str,'_');		
	}	

	private function getCountriesCodeString()
	{
		$str = '';
		foreach ($this->countries as $country) {
			$str .= '"'.$country->getCode().'",';
		}
		return $str = rtrim($str,',');		
	}

	public function getExportDir()
	{
		return rtrim($this->export_dir,'/').'/'.$this->exporter->getDBSystem();
	}

	public function getWebPath()
	{
		return substr($this->getExportDir(),strpos($this->getExportDir(),'web')+3).'/';
	}

	private function getFileName()
	{
		if( !empty($this->filename)) return $this->file_name.'_'.$this->getCountriesNameString();
		else return $this->getCountriesNameString();
	}

	public function dropTable($bool = false)
	{
		$this->dropTable = $bool;
		return $this;
	}

	public function createTable($bool = false)
	{
		$this->createTable = $bool;
		return $this;
	}

	public function forceInserts($bool = false)
	{
		if($this->exporter->system == 'mysql') return;
		if($this->exporter->system == 'pgsql') $this->exporter->forceInserts($bool);
		return $this;
	}
}