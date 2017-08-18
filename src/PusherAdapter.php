<?php namespace Hht\MiPush;

use Hht\MiPush\Builder\Message;
use Hht\MiPush\Builder\IOSBuilder;
use Hht\MiPush\Builder\Builder;
use Hht\MiPush\Client\Result;

class PusherAdapter implements AdapterInterface
{
	/**
     * @var Send field.
     */
	protected $field;
	
	/**
     * @var Send value.
     */
	protected $value;

	/**
     * @var PusherClient
     */
    protected $client;

	public function __construct(PusherClient $client, $config = null)
	{
		$this->client = $client;
	}
	
	/**
	 * Send to ios.
	 *
	 * @param   IOSBuilder    $builder
	 * @return  \Hht\MiPush\Client\Result
	 */
    public function sendToIos(IOSBuilder $builder)
	{
		$header = $this->client->getIosHeader();
		$builder->build();
		
		$fields = $builder->getFields();
		return $this->_handSendTo($fields, $header);
	}
	
	/**
	 * Send to android.
	 *
	 * @param   Builder       $builder
	 * @return  \Hht\MiPush\Client\Result
	 */
	public function sendToAndroid(Builder $builder)
	{
		$header = $this->client->getAndroidHeader();
		$builder->build();

		$fields = $builder->getFields();
		return $this->_handSendTo($fields, $header);
	}
	
	/**
	 * Check message has been send.
	 *
	 * @param   String       $msgId
	 * @param   String       $client
	 * @return  \Hht\MiPush\Client\Result
	 */
	public function checkScheduleJobExist($msgId, $client = 'ios')
	{
		$header = $client == 'android' ? $this->client->getAndroidHeader() : $this->client->getIosHeader();

		return $this->client->postResult($this->client->getUrl('exist_url'), ['job_id' => $msgId], 1, $header);
	}
	
	/**
	 * Delete message.
	 *
	 * @param   String       $msgId
	 * @param   String       $client
	 * @return  \Hht\MiPush\Client\Result
	 */
	public function deleteScheduleJob($msgId, $client = 'ios')
	{
		$header = $client == 'android' ? $this->client->getAndroidHeader() : $this->client->getIosHeader();

		return $this->client->postResult($this->client->getUrl('delete_url'), ['job_id' => $msgId], 1, $header);
	}
	
	/**
	 * Set message receive object.
	 *
	 * @param   String       $field
	 * @param   object       $value
	 * @return  \Hht\MiPush\Client\Result
	 */
	public function setSendTo($field, $value = true)
	{
		switch ($field)
		{
			case 'id':
				$this->field = 'id';
				$this->value = $value[0];
				break;
			case 'ids':
				$this->field = 'ids';
				$this->value = count($value > 0) ? $value : $value[0];
				break;
			case 'alias':
				$this->field = 'alias';
				$this->value = $value[0];
				break;
			case 'aliases':
				$this->field = 'aliases';
				$this->value = count($value > 0) ? $value : $value[0];
				break;
			case 'topic':
				$this->field = 'topic';
				$this->value = $value[0];
				break;
			case 'all':
				$this->field = 'all';
				$this->value = $value[0];
				break;
			default:
		}
	}
	
	/**
	 * Private handler and send.
	 *
	 * @param   IOSBuilder    $builder
	 * @return  \Hht\MiPush\Client\Result
	 */
	private function _handSendTo($fields, $header)
	{
		$url = '';
		switch ($this->field)
		{
			case 'id':
				$fields['registration_id'] = $this->value;
				$url = $this->client->getUrl('reg_url');
				break;
			case 'ids':
				$fields['registration_id'] = implode(',', $this->value);
				$url = $this->client->getUrl('reg_url');
				break;
			case 'alias':
				$fields['alias'] = $this->client->getPrefix() . $this->value;
				$url = $this->client->getUrl('alias_url');
				break;
			case 'aliases':
				$fields['alias'] = $this->client->getPrefix() . implode(',' . $this->client->getPrefix(), $this->value);
				$url = $this->client->getUrl('alias_url');
				break;
			case 'topic':
				$fields['topic'] = $this->client->getPrefix() . $this->value;
				$url = $this->client->getUrl('topic_url');
				break;
			case 'all':
				$url = $this->client->getUrl('all_url');
			default;

		}
		
		if (empty($url))
			return new Result(json_encode(['code' => '999']));
		else
			return $this->client->postResult($url, $fields, 1, $header);
	}
	
	/**
     * Pass dynamic methods call onto PusherAdapter.
     *
     * @param  string  $method
     * @param  array   $parameters
     * @return mixed
     *
     * @throws \BadMethodCallException
     */
	public function __call($method, array $parameters)
	{
		$methods = ['setId', 'setIds', 'setAlias', 'setAliases', 'setTopic', 'setAll'];

		if (in_array($method, $methods))
			$this->setSendTo(lcfirst(substr($method, 3)), $parameters);

		return $this;
	}
}
