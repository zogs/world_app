<?php

namespace Zogs\UtilsBundle\Downloader;


use Symfony\Component\HttpFoundation\Request;
use Zogs\UtilsBundle\Downloader\DownloaderInterface;


class FtpDownloader implements DownloaderInterface
{
	private $ftp_server;
	private $ftp_port;
	private $ftp_user;
	private $ftp_pass;
	private $root_dir;
	private $file_dir = '';
	private $file_name = 'ftpdownload';
	private $timeout = 120;


	public function __construct($rootDir)
	{
		$this->root_dir = $rootDir.'/../';
	}

	private function getConnection($reset = false) 
	{
		if($reset == false && isset($this->conn_id)) return $this->conn_id;

		$this->conn_id = ftp_connect($this->ftp_server, $this->ftp_port, $this->timeout);

		ftp_login($this->conn_id, $this->ftp_user, $this->ftp_pass);

		ftp_pasv($this->conn_id, true);

		return $this->conn_id;
	}

	public function resetConnection() 
	{
		return $this->getConnection(true);
	}

	public function closeConnection() 
	{
		if(isset($this->conn_id)) ftp_close($this->conn_id);
	}

	public function getFile($file)
	{		
		if(empty($file)) throw new \Exception('The filename paramter can not be empty.');

		// Mise en place d'une connexion basique
		$conn_id = $this->getConnection();

		// Tentative de téléchargement du fichier $server_file et sauvegarde dans le fichier $local_file
		try {
			ftp_get($conn_id, $this->file_dir.$this->file_name, $file, FTP_BINARY);
		}
		catch(\Exception $exception) {				
		    	return $exception;
		}		

		return $this->file_name;
	}

	public function getSize($file)
	{		
		// Mise en place d'une connexion basique
		$conn_id = $this->getConnection();

		//get size
		$size = ftp_size($conn_id,$file);

		return $size;
	}

	public function hasFile($file)
	{		
		$size = $this->getSize($file);
		
		if($size == -1) return false;
		return true;
	}

	public function getList($dir = ".")
	{
		// Mise en place d'une connexion basique
		$conn_id = $this->getConnection();

		// Tentative de téléchargement du fichier $server_file et sauvegarde dans le fichier $local_file		
		$list = ftp_nlist($conn_id, $dir);

		//catch error
		if($list === false) throw new \Exception("Une erreur a eu lieu lors de la récupération de la liste des fichiers du ftp... (ftp_nlist() is returning false)");	


		return $list;
	}


	public function setServer($server)
	{
		$this->ftp_server = $server;
		return $this;
	}

	public function setPort($port)
	{
		$this->ftp_port = $port;
		return $this;
	}

	public function setUser($user)
	{
		$this->ftp_user = $user; 
		return $this;
	}

	public function setPassword($pass)
	{
		$this->ftp_pass = $pass;
		return $this;
	}

	public function setFilename($filename)
	{
		$this->file_name = $filename;
		return $this;
	}

	public function setDirectory($dir)
	{
		$this->file_dir = $dir;
		return $this;
	}

	public function setTimeout($time)
	{
		$this->timeout = $time;
		return $this;
	}
}