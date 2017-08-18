<?php namespace Hht\MiPush;

use Illuminate\Support\Arr;
use Hht\MiPush\Client\ErrorCode;
use Hht\MiPush\Client\Result;
use Hht\MiPush\Client\Config;

class PusherClient 
{
	/**
	 * @var Http request address.
	 */
	protected $url;

	/**
	 * @var Http request header.
	 */
	protected $header;

	/**
	 * @var Http request timeout.
	 */
	protected $timeout;
	
	/**
	 * @var config address array.
	 */
	private $urls;
	
	/**
	 * @var Config ios config.
	 */
	private $ios;
	
	/**
	 * @var Config android config.
	 */
	private $android;
	
	/**
	 * @var Config prefix.
	 */
	private $prefix;
	
	public function __construct($config)
	{
		$this->urls = Arr::only($config, ['reg_url', 'alias_url', 'topic_url', 'multi_topic_url', 'all_url', 'exist_url', 'exist_url', 'delete_url']);
		$this->android = new Config(Arr::get($config, 'android'));
		$this->ios = new Config(Arr::get($config, 'ios'));
		$this->prefix = Arr::get($config, 'prefix');
	}
	
	/**
	 * Get ios header.
	 *
	 * @return  Array
	 */
	public function getIosHeader()
	{
		return ['Authorization: key=' . $this->ios->app_secret, 'Content-Type: application/x-www-form-urlencoded'];
	}
	
	/**
	 * Get android header.
	 *
	 * @return  Array
	 */
	public function getAndroidHeader()
	{
		return ['Authorization: key=' . $this->android->app_secret, 'Content-Type: application/x-www-form-urlencoded'];
	}

	/**
	 * Get url.
	 *
	 * @param   String    $name
	 * @return  String
	 */
	public function getUrl($name)
	{
		if (isset($this->urls[$name]))
			return $this->urls[$name];
		else
			return '';
	}
	
	/**
	 * Get prefix.
	 *
	 * @return  String
	 */
	public function getPrefix()
	{
		return $this->prefix;
	}
		
	/**
	 * Post result.
	 *
	 * @param   Array    $fields
	 * @param   Int      $retries
	 * @param   String   $url
	 * @param   Array    $header
	 * @param   Int      $timeout
	 * @return  \Hht\MiPush\Client\Result
	 */
	public function postResult($url = '', $fields, $retries = 1, $header = [], $timeout = 0) 
	{
		$url = $url ? $url : $this->url;
		$header = $header ? $header : $this->header;
		$timeout = $timeout ? $timeout : $this->timeout;

		$result = new Result($this->request($url, $fields, $header, $timeout));
		if($result->getErrorCode() == ErrorCode::Success)
		{
		    return $result;
		}

		for($i = 0; $i < $retries; $i ++) 
		{
			$result = new Result($this->request($url, $fields, $header, $timeout));
		    if ($result->getErrorCode() == ErrorCode::Success) break;
		}

		return $result;
	}
	
	/**
	 * Request.
	 *
	 * @param   String   $url
	 * @param   Array    $data
	 * @param   Array    $header
	 * @param   Int      $timeout
	 * @return  String
	 */
	public function request($url = '', $data = [], $header = [], $timeout = 30) 
	{
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		//curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, true);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, $timeout); 

		$response = curl_exec($ch);

		if ($error = curl_error($ch)) {
			//die($error);
		}

		curl_close($ch);

		return $response;
	}
}
