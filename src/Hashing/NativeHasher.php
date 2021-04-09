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

namespace Hedi\Sentinel\Hashing;

class NativeHasher implements HasherInterface
{
    /**
     * {@inheritdoc}
     */
    public function hash(string $value): string
    {
        return password_hash($value, PASSWORD_DEFAULT);
    }

    /**
     * {@inheritdoc}
     */
    public function check(string $value, string $hashedValue): bool
    {
        return password_verify($value, $hashedValue);
    }
}
