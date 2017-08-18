<?php namespace Hht\MiPush\Client;

class Config 
{
	public $bundle_id;

	public $app_id;

	public $app_key;

	public $app_secret;

	public function __construct($config)
	{
		if (isset($config['bundle_id']))
			$this->bundle_id = $config['bundle_id'];

		if (isset($config['app_id']))
			$this->app_id = $config['app_id'];

		if (isset($config['app_key']))
			$this->app_key = $config['app_key'];

		if (isset($config['app_secret']))
			$this->app_secret = $config['app_secret'];
	}
}

?>
