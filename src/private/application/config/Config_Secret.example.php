<?php
require_once 'Config_Advanced.php';

class Config_Secret extends Config_Advanced
{
	public $secret = '';
	
	public $dbDsn				= array(
									'host' 		=> 'localhost',
									'dbname'	=> ''
									);
	public $dbUser 				= '';
	public $dbPassword 			= '';	
	
	public $twitterUser			= "updatemybrowser";
	public $twitterToken		= "";
	public $twitterTokenSecret	= "";
	
}