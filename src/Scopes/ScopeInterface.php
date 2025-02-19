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

interface ScopeInterface
{
    /**
     * Returns the role's primary key.
     *
     * @return int
     */
    public function getScopeId(): int;

    /**
     * Returns the role's slug.
     *
     * @return string
     */
    public function getScopeSlug(): string;

    /**
     * Returns all users for the scope.
     *
     * @return \IteratorAggregate
     */
    public function getUsers(): IteratorAggregate;

    /**
     * Returns all roles for the scope.
     *
     * @return \IteratorAggregate
     */
    public function getRoles(): IteratorAggregate;

    /**
     * Returns the users model.
     *
     * @return string
     */
    public static function getUsersModel(): string;

    /**
     * Returns the roles model.
     *
     * @return string
     */
    public static function getRolesModel(): string;

    /**
     * Sets the users model.
     *
     * @param string $usersModel
     *
     * @return void
     */
    public static function setUsersModel(string $usersModel): void;

    /**
     * Sets the roles model.
     *
     * @param string $rolesModel
     *
     * @return void
     */
    public static function setRolesModel(string $rolesModel): void;
}
