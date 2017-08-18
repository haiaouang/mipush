<?php namespace Hht\MiPush\Builder;

class Builder extends Message 
{
	const soundUri = 'sound_uri';
	const notifyForeground = 'notify_foreground';
	const notifyEffect = 'notify_effect';
	const intentUri = 'intent_uri';
	const webUri = 'web_uri';
	const flowControl = 'flow_control';
	const callback = 'callback';

	public function __construct() {
		parent::__construct();

		$this->notify_id = 0;
		$this->notify_type = -1;
		$this->payload = '';
		$this->restricted_package_name = '';
		
	}
	
	public function payload($payload) {
		$this->payload = $payload;
	}
	
	public function title($title) {
		$this->title = $title;
	}
	
	public function description($description) {
		$this->description = $description;
	}
	
	public function passThrough($passThrough) {
		$this->pass_through = $passThrough;
	}
	
	public function notifyType($type) {
		$this->notify_type = $type;
	}
	
	public function restrictedPackageNames($packageNames) {
		$this->restricted_package_name = implode(',', $packageNames);
	}
	
	public function timeToLive($ttl) {
		$this->time_to_live = $ttl;
	}

	public function timeToSend($timeToSend) {
		$this->time_to_send = $timeToSend;
	}
	
	public function notifyId($notifyId) {
		$this->notify_id = $notifyId;
	}

	public function extra($key,$value) {
		$this->extra[$key] = $value;
	}
	
	public function build() {
		$keys = ['payload','title','description','pass_through','notify_type', 'restricted_package_name','time_to_live','time_to_send','notify_id'];

		foreach ($keys as $key)
		{
			if(isset($this->$key))
			{
				$this->fields[$key] = $this->$key;
				$this->json_infos[$key] = $this->$key;
			}
		}
		
		$JsonExtra = [];

		if(count($this->extra) > 0)
		{
			foreach($this->extra as $extraKey => $extraValue)
			{
				$this->fields[Message::EXTRA_PREFIX.$extraKey] = $extraValue;
				$JsonExtra[$extraKey] = $extraValue;
			}
		}

		$this->json_infos['extra'] = $JsonExtra;
		
	}
}
