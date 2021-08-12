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

interface ScopeRepositoryInterface
{
    /**
     * Finds a role by the given primary key.
     *
     * @param int $id
     *
     * @return \Hedi\Sentinel\Scopes\ScopeInterface|null
     */
    public function findById(int $id): ?ScopeInterface;

    /**
     * Finds a role by the given name.
     *
     * @param string $name
     *
     * @return \Hedi\Sentinel\Scopes\ScopeInterface|null
     */
    public function findByName(string $name): ?ScopeInterface;
}
