<?php

/*
 * Part of the Sentinel package.
 *
 * NOTICE OF LICENSE
 *
 * Licensed under the 3-clause BSD License.
 *
 * This source file is subject to the 3-clause BSD License that is
 * bundled with this package in the LICENSE file.
 *
 * @package    Sentinel
 * @version    6.0.0
 * @author     Cartalyst LLC
 * @license    BSD License (3-clause)
 * @copyright  (c) 2011-2022, Cartalyst LLC
 * @link       https://cartalyst.com
 */

namespace Hedi\Sentinel\Native\Facades;

use Hedi\Sentinel\Native\SentinelBootstrapper;

class Sentinel
{
    /**
     * The Sentinel instance.
     *
     * @var \Hedi\Sentinel\Sentinel
     */
    protected $sentinel;

    /**
     * The Native Bootstraper instance.
     *
     * @var \Hedi\Sentinel\Native\SentinelBootstrapper
     */
    protected static $instance;

    /**
     * Constructor.
     *
     * @param \Hedi\Sentinel\Native\SentinelBootstrapper $bootstrapper
     *
     * @return void
     */
    public function __construct(SentinelBootstrapper $bootstrapper = null)
    {
        if ($bootstrapper === null) {
            $bootstrapper = new SentinelBootstrapper();
        }

        $this->sentinel = $bootstrapper->createSentinel();
    }

    /**
     * Returns the Sentinel instance.
     *
     * @return \Hedi\Sentinel\Sentinel
     */
    public function getSentinel()
    {
        return $this->sentinel;
    }

    /**
     * Creates a new Native Bootstraper instance.
     *
     * @param \Hedi\Sentinel\Native\SentinelBootstrapper $bootstrapper
     *
     * @return \Hedi\Sentinel\Native\SentinelBootstrapper
     */
    public static function instance(SentinelBootstrapper $bootstrapper = null)
    {
        if (static::$instance === null) {
            static::$instance = new static($bootstrapper);
        }

        return static::$instance;
    }

    /**
     * Handle dynamic, static calls to the object.
     *
     * @param string $method
     * @param array  $args
     *
     * @return mixed
     */
    public static function __callStatic($method, $args)
    {
        $instance = static::instance()->getSentinel();

        switch (count($args)) {
            case 0:
                return $instance->{$method}();
            case 1:
                return $instance->{$method}($args[0]);
            case 2:
                return $instance->{$method}($args[0], $args[1]);
            case 3:
                return $instance->{$method}($args[0], $args[1], $args[2]);
            case 4:
                return $instance->{$method}($args[0], $args[1], $args[2], $args[3]);
            default:
                return call_user_func_array([$instance, $method], $args);
        }
    }
}
