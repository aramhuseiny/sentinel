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

class StandardPermissions implements PermissionsInterface
{
    use PermissionsTrait;

    /**
     * {@inheritdoc}
     */
    protected function createPreparedPermissions(): array
    {
        $prepared = [];

        if (! empty($this->getSecondaryPermissions())) {
            foreach ($this->getSecondaryPermissions() as $permissions) {
                $this->preparePermissions($prepared, $permissions);
            }
        }

        if (! empty($this->getPermissions())) {
            $permissions = [];

            $this->preparePermissions($permissions, $this->getPermissions());

            $prepared = array_merge($prepared, $permissions);
        }

        return $prepared;
    }
}
