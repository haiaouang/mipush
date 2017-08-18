<?php namespace Hht\MiPush;

use Hht\MiPush\Builder\IOSBuilder;
use Hht\MiPush\Builder\Builder;

interface AdapterInterface
{
    public function sendToIos(IOSBuilder $builder);

	public function sendToAndroid(Builder $builder);

	public function checkScheduleJobExist($msgId);

	public function deleteScheduleJob($msgId);

	public function setSendTo($field, $value);
}
