<?php namespace Cartalyst\Sentinel\Roles;
/**
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
 * @version    1.0.11
 * @author     Cartalyst LLC
 * @license    BSD License (3-clause)
 * @copyright  (c) 2011-2015, Cartalyst LLC
 * @link       http://cartalyst.com
 */

interface RoleableInterface {

	/**
	 * Returns all the associated roles.
	 *
	 * @return \IteratorAggregate
	 */
	public function getRoles();

	/**
	 * Checks if the user is in the given role.
	 *
	 * @param  mixed  $role
	 * @return bool
	 */
	public function inRole($role);

}
