<?php namespace Hht\MiPush;

use Hht\MiPush\Config\ConfigAwareTrait;
use Hht\MiPush\Plugin\PluggableTrait;
use Hht\Support\Contracts\Message;
use Hht\MiPush\Builder\IOSBuilder;
use Hht\MiPush\Builder\Builder;

class Pusher implements PusherInterface
{
    use PluggableTrait;
    use ConfigAwareTrait;

    /**
     * @var AdapterInterface
     */
    protected $adapter;

    /**
     * Constructor.
     *
     * @param AdapterInterface $adapter
     * @param Config|array     $config
     */
    public function __construct(AdapterInterface $adapter, $config = null)
    {
        $this->adapter = $adapter;
        $this->setConfig($config);
    }

    /**
     * Get the Adapter.
     *
     * @return AdapterInterface adapter
     */
    public function getAdapter()
    {
        return $this->adapter;
    }
	
	/**
     * Send a message to ios.
     *
     * @return \Hht\MiPush\Client\Result
     */
	public function sendToIos(Message $message)
	{
		return $this->adapter->sendToIos($message);
	}
	
	/**
     * Send a message to android.
     *
     * @return \Hht\MiPush\Client\Result
     */
	public function sendToAndroid(Message $message)
	{
		return $this->adapter->sendToAndroid($message);
	}
	
	/**
     * Send a message.
     *
     * @return \Hht\MiPush\Client\Result
     */
	public function send(Message $message)
	{
		if ($message instanceof IOSBuilder)
			return $this->sendToIos($message);
		else if ($message instanceof Builder)
			return $this->sendToAndroid($message);
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
        $back = call_user_func_array([$this->adapter, $method], $parameters);

		if ($back instanceof AdapterInterface)
			return $this;
		else
			return $back;
    }
}
