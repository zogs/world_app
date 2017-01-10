<?php

namespace Zogs\DatabaseToolBundle\Exporter;

use Zogs\DatabaseToolBundle\Exporter\DBExporterInterface;

/**
 * Export table(s) from an SQL Database 
 * to a SQL file
 */
class PgSQLExporter 
{	
	/**
	 * System of database
	 */
	public $system = "pgsql";
	/**
	 * Path where to run mysql cmd
	 */
	private $psql_path;
	/**
	 * Database name
	 */
	private $dbname;
	/**
	 * Database user
	 */
	private $dbuser;
	/**
	 * Database password
	 */
	private $dbpassword;
	/**
	* Tables to export
	*/	
	private $tables = array();
	/**
	 * Where conditions
	 */
	private $where = '';
	/**
	 * Option for the command line
	 */
	private $options = '';
	/**
	 * Path to export the file
	 */
	private $filepath = '';
	/**
	 * name of the export file
	 */
	private $filename = 'dump';
	/**
	* File writing method, can be "override" to create new file or "append" to append to file if exist
	*/
	private $write_method;

	/**
	 * set default connection params
	 * called on init
	 */
	public function __construct($dbname,$dbuser,$dbpassword)
	{
		$this->dbname = $dbname;
		$this->dbuser = $dbuser;
		$this->dbpassword = $dbpassword;
		$this->write_method = 'override';
	}

	/**
	 * Export tables to sql file 
	 * Required:
	 * setTables() or addTable()
	 * setFilename()
	 *
	 * Optional:
	 * setWhere()
	 * setDatabase()
	 * setUser()
	 * setPassword()
	 * setOptions(), addOption()
	 * append(), override()
	 */
	public function export()
	{
		//create temporary tables
		foreach ($this->tables as $k => $table) {	
			$cmd =  $this->getPath().'psql -h localhost -U '.$this->dbuser.' -d '.$this->getDatabase().' -c "CREATE TABLE '.$this->getTempTable($table).' AS SELECT * FROM '.$table.' WHERE '.$this->getWhere().';"';
			exec($cmd);
		}

		//print the tables in a file
	    $cmd = $this->getPath().'pg_dump -h localhost -U '.$this->getUser().' '.$this->getDatabase().' '.$this->getOptions().' '.$this->getTablesList().' '.$this->getWriteMethod().' '.$this->getFilePath();
	    exec($cmd);
	    
	    //rename temp tables inside the generated files
	    foreach ($this->tables as $k => $table) {
	    	$cmd = "sed -ire 's/".$this->getTempTable($table)."/".$table."/g' ".$this->getFilePath();
	    	exec($cmd);
	    }

	    //delete temp tables
	    foreach ($this->tables as $k => $table) {	
			$cmd =  $this->getPath().'psql -h localhost -U '.$this->dbuser.' -d '.$this->getDatabase().' -c "DROP TABLE '.$this->getTempTable($table).';"';
	    	exec($cmd);
		}

	    return $this->getFilePath();
	}


	/**
	 * set mysqlpath from config params
	 * called on int
	 */
	public function setPsqlPath($path)
	{
		$this->psql_path =  $path;
	}

	public function getPath()
	{
		return 'PGPASSWORD='.$this->dbpassword.' '.$this->psql_path;
	}

	/**
	 * set exporter config values
	 * called on init
	 */
	public function setConfig($config)
	{
		$this->setFilePath($config['exportpath']);
	}

	public function setTables(array $tables)
	{
		$this->tables = $tables;
		return $this;
	}

	public function addTable($table)
	{
		$this->tables[] = $table;
		return $this;
	}

	private function getTablesList()
	{
		$list = '';
		foreach ($this->tables as $k => $table) {
			$list .= ' -t '.$this->getTempTable($table);
		}
		return $list;
	}

	private function getTempTable($table)
	{
		return 'temp_world_export_'.$table;
	}

	public function setWhere($where)
	{
		$this->where = $where;
		return $this;
	}

	private function getWhere()
	{
		return str_replace('"',"'", $this->where);
	}

	public function setFilename($name)
	{
		$this->filename = $name;
		return $this;
	}

	private function getFilename()
	{
		return $this->filename.'.sql';
	}

	private function getFilePath()
	{
		$file = $this->filepath.DIRECTORY_SEPARATOR.$this->getFilename();		
		if( file_exists($file) && ! is_writable($file))  new \InvalidArgumentException(sprintf('Export file %s can not be writable', $file));
		return $file;
	}

	public function setFilePath($path)
	{

		if(! is_dir($path)) mkdir($path, 0777, true);
		if(! is_writable($path)) throw new \InvalidArgumentException(sprintf('Export directory %s is not writable', $path));

		$this->filepath = $path;
		return $this;
	}

	public function setPassword($pw)
	{
		$this->dbpassword = $pw;
		return $this;
	}

	private function getPassword()
	{
		return ($this->dbpassword !== null)? ' -p'.$this->dbpassword.' ' : '';
	}

	public function setDatabase($dbname)
	{
		$this->dbname = $dbname;
		return $this;
	}

	private function getDatabase()
	{
		return $this->dbname;
	}

	public function setUser($user)
	{
		$this->dbuser = $user;
		return $this;
	}

	private function getUser()
	{
		return $this->dbuser;
	}

	public function getDBSystem()
	{
		return $this->system;
	}

	public function setDropTable($bool)
	{		
		($bool === true)? $this->addOption('--c') : $this->removeOption('--c');
		return $this;
	}

	public function setCreateTable($bool)
	{
		($bool === true)? $this->addOption('--C') : $this->addOption('-a')->removeOption('--C');
		return $this;
	}

	public function forceInserts($bool)
	{
		($bool === true)? $this->addOption('--column-inserts') : $this->removeOption('--column-inserts');
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

	public function removeOption($option)
	{
		if(isset($this->options[$option])) unset($this->options[$option]);
		return $this;
	}

	private function getOptions()
	{
		if(empty($this->options)) return '';
		$str = '';
		foreach ($this->options as $option) {
			$str .= $option.' ';
		}
		return $str;
	}


	public function getWriteMethod()
	{		
		if($this->write_method == 'append') return '>>';
		return '>';		
	}

	private function append()
	{
		$this->write_method = 'append';
		return $this;
	}

	private function override()
	{
		$this->write_method = 'write';
		return $this;
	}

}