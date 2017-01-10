<?php 

namespace Zogs\UtilsBundle\Utils;

class String {

	static public $email_pattern = '/[a-zA-Z0-9\._%+-]+@[a-zA-Z0-9\.-]+\.[a-zA-Z]{2,4}/';

	static function random($length = 10){

		return substr(str_shuffle(MD5(microtime())), 0, $length);

	}

	static function directorySeparation($string){

		return str_replace(array('/','\\'), DIRECTORY_SEPARATOR, $string);
	}

	static function slugify($text)
	{
		    // replace non letter or digits by -
		    $text = preg_replace('~[^\\pL\d]+~u', '-', $text);
		 
		    // trim
		    $text = trim($text, '-');
		 
		    // transliterate
		    if (function_exists('iconv'))
		    {
		        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
		    }
		 
		    // lowercase
		    $text = strtolower($text);
		 
		 
		    // remove unwanted characters
		    $text = preg_replace('~[^-\w]+~', '', $text);
		 
		    if (empty($text))
		    {
		        return 'n-a';
		    }
		 
	    return $text;
	}

	static function unixify($string){
		return strtolower(str_replace(' ','-',$string));
	}

	static function br2nl($foo) {
		return preg_replace("/\<br\s*\/?\>/i", "\n", $foo);
	}

	static function parse_url($_url = null){
    
	    try{
	    	
	    	//add http if missing
	    	if(strpos($_url,'http://')!==0 && strpos($_url,'https://'!==0)) $_url = 'http://'.$_url;	    	
	    	
			$parsed = parse_url($_url);
			
			$arr = array();
			$arr['www']='';
			$arr['protocol']='';
			$arr['domain']='';
			$arr['subdomain']='';
			$arr['extension']='';
			$arr['path']='';
			$arr['query']='';
			$arr['all']='';

			if(strpos($parsed['host'],'www.')===0){				
				$arr['www'] = 'www.';
				$parsed['host'] = str_replace('www.','',$parsed['host']);
			}			
			$host = explode('.',$parsed['host']);
			$ln = count($host);
			$arr['extension'] = $host[$ln-1];
			$arr['domain'] = $host[$ln-2];
			$arr['subdomain'] = ($ln>2)? $host[$ln-3] : '';
			
			if(!empty($parsed['path'])) $arr['path'] = $parsed['path'];
			if(!empty($parsed['query'])) $arr['query'] = $parsed['query'];
			if(!empty($parsed['scheme'])) $arr['protocol'] = $parsed['scheme'].'://';
			
			$arr['all'] = $_url;

			return $arr;
			}
	    catch(Exception $e){
	    	return false;
	    }
	}

	static function findEmailsInString($str){
		
	 	$mail = preg_match_all(self::$email_pattern, $str,$matches);
	 	return $matches[0];
	}

	static function isEmail($str){

		return preg_match(self::$email_pattern,$str);
	}

	static function isSerialized($string){
		$array = @unserialize($string);
		if ($array === false && $string !== 'b:0;') {
		    return false;
		}
		return true;
	}

	static function htmlEncode($s,$encode = 'UTF-8') {
    	return htmlspecialchars($s, ENT_QUOTES, $encode);
	}

	static function htmlDecode($s){
		return htmlspecialchars_decode($s, ENT_QUOTES);
	}

	static function randomHash($length = 10)
	{
		return bin2hex(mcrypt_create_iv($length/2, MCRYPT_DEV_URANDOM));
	}

	/**
	* Translates a camel case string into a string with
	* underscores (e.g. firstName -> first_name)
	*
	* @param string $str String in camel case format
	* @return string $str Translated into underscore format
	*/
	static function decamelize($str) {
		$str[0] = strtolower($str[0]);
		$func = create_function('$c', 'return "_" . strtolower($c[1]);');
		return preg_replace_callback('/([A-Z])/', $func, $str);
	}
	/**
	* Translates a string with underscores
	* into camel case (e.g. first_name -> firstName)
	*
	* @param string $str String in underscore format
	* @param bool $capitalise_first_char If true, capitalise the first char in $str
	* @return string $str translated into camel caps
	*/
	static function camelize($str, $capitalise_first_char = false) {
		if($capitalise_first_char) {
		$str[0] = strtoupper($str[0]);
		}
		$func = create_function('$c', 'return strtoupper($c[1]);');
		return preg_replace_callback('/_([a-z])/', $func, $str);
	} 



} ?>