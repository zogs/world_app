<?php

namespace Zogs\WorldBundle\Importer;

use Symfony\Component\HttpFoundation\Request;

class WorldSQLImporter
{
	private $files_path;
	private $mysql_path;
	private $options = '';
	private $method = 'batch';

	public function __construct($importer)
	{
		$this->importer = $importer;
	}

	public function importAll($dir)
	{
		return $this->importer->importAll($dir);
	}

	public function import($file)
	{
		return $this->importer->import($file);
	}

	public function setConfig($config)
	{
		$this->importer->setFiles($config['files_to_import']);
		return $this;
	}

	public function setMethod($method)
	{
		$this->importer->setMethod($method);
		return $this;
	}

	public function setOptions(array $options)
	{
		$this->importer->setOptions($options);
		return $this;
	}

	public function setDatabase($database)
	{
		$this->importer->setDatabase($database);
		return $this;
	}

	public function setMysqlPath($mysql_path)
	{
		$this->importer->setMysqlPath($mysql_path);
		return $this;
	}

	public function addOption($option)
	{
		$this->importer->options[] = $option;
		return $this;

	}

	public function getOptions()
	{
		$this->importer->getOptions();
	}
	
}