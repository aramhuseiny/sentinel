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

namespace Hedi\Sentinel\Scopes;

use IteratorAggregate;

interface ScopeableInterface
{
    /**
     * Returns all the associated scopes.
     *
     * @return \IteratorAggregate
     */
    public function getScopes(): IteratorAggregate;

    /**
     * Checks if the user is in the given scope.
     *
     * @param mixed $scope
     *
     * @return bool
     */
    public function inScope($scope): bool;

    /**
     * Checks if the user is in any of the given roles.
     *
     * @param array $scopes
     *
     * @return bool
     */
    public function inAnyScope(array $scopes): bool;
}
