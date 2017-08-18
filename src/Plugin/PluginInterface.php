<?php namespace Hht\MiPush\Plugin;

use Hht\MiPush\PusherInterface;

interface PluginInterface
{
    /**
     * Get the method name.
     *
     * @return string
     */
    public function getMethod();

    /**
     * Set the Pusher object.
     *
     * @param PusherInterface $pusher
     */
    public function setPusher(PusherInterface $pusher);
}
