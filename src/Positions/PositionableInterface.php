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
 * @version    5.1.0
 * @author     Cartalyst LLC
 * @license    BSD License (3-clause)
 * @copyright  (c) 2011-2020, Cartalyst LLC
 * @link       https://cartalyst.com
 */

namespace Hedi\Sentinel\Positions;

use IteratorAggregate;

interface PositionableInterface
{
    /**
     * Returns all the associated roles.
     *
     * @return \IteratorAggregate
     */
    public function getPositions(): IteratorAggregate;

    /**
     * Checks if the user is in the given role.
     *
     * @param mixed $position
     *
     * @return bool
     */
    public function inPosition($position): bool;

    /**
     * Checks if the user is in any of the given roles.
     *
     * @param array $positions
     *
     * @return bool
     */
    public function inAnyPosition(array $positions): bool;
}
