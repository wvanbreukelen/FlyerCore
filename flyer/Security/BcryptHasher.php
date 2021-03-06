<?php

namespace Flyer\Components\Security;

use RuntimeException;

class BcryptHasher {

	/**
	 * Default crypt cost factor.
	 *
	 * @var int
	 */
	protected $rounds = 10;

	/**
	 * Construct a new BcryptHasher instance
	 */
	public function __construct()
	{
		if (phpversion() < "5.50")
		{
			throw new RuntimeException("This PHP version does not support Bcrypt based hashing, please update to PHP 5 of higher");
		}
	}

	/**
	 * Hash the given value.
	 *
	 * @param  string  $value
	 * @param  array   $options
	 * @return string
	 *
	 * @throws \RuntimeException
	 */
	public function make($value, array $options = array())
	{
		$cost = isset($options['rounds']) ? $options['rounds'] : $this->rounds;

		$hash = password_hash($value, PASSWORD_BCRYPT, array('cost' => $cost));

		if ($hash === false)
		{
			throw new RuntimeException("Bcrypt hashing not supported.");
		}

		return $hash;
	}

	/**
	 * Check the given plain value against a hash.
	 *
	 * @param  string  $value
	 * @param  string  $hashedValue
	 * @return bool
	 */
	public function check($value, $hashedValue)
	{	
		return password_verify($value, $hashedValue);
	}

	/**
	 * Check if the given hash has been hashed using the given options.
	 *
	 * @param  string  $hashedValue
	 * @param  array   $options
	 * @return bool
	 */
	public function needsRehash($hashedValue, array $options = array())
	{
		$cost = isset($options['rounds']) ? $options['rounds'] : $this->rounds;

		return password_needs_rehash($hashedValue, PASSWORD_BCRYPT, array('cost' => $cost));
	}

}