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

namespace Hedi\Sentinel\Sessions;

interface SessionInterface
{
    /**
     * Put a value in the Sentinel session.
     *
     * @param mixed $value
     *
     * @return void
     */
    public function put($value): void;

    /**
     * Returns the Sentinel session value.
     *
     * @return mixed
     */
    public function get();

    /**
     * Removes the Sentinel session.
     *
     * @return void
     */
    public function forget(): void;
}
