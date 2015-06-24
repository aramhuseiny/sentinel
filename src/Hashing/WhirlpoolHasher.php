<?php namespace Cartalyst\Sentinel\Hashing;
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

class WhirlpoolHasher implements HasherInterface {

	use Hasher;

	/**
	 * {@inheritDoc}
	 */
	public function hash($value)
	{
		$salt = $this->createSalt();

		return $salt.hash('whirlpool', $salt.$value);
	}

	/**
	 * {@inheritDoc}
	 */
	public function check($value, $hashedValue)
	{
		$salt = substr($hashedValue, 0, $this->saltLength);

		return $this->slowEquals($salt.hash('whirlpool', $salt.$value), $hashedValue);
	}

}
