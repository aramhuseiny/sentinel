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

namespace Hedi\Sentinel\Permissions;

interface PermissionsInterface
{
    /**
     * Returns if access is available for all given permissions.
     *
     * @param array|string $permissions
     *
     * @return bool
     */
    public function hasAccess($permissions): bool;

    /**
     * Returns if access is available for any given permissions.
     *
     * @param array|string $permissions
     *
     * @return bool
     */
    public function hasAnyAccess($permissions): bool;
}
