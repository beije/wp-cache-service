<?php

class CacheService {
	const DOMAIN = 'cached';

	/**
	 * Generates a transient key.
	 * 
	 * @param  string   $key     The unique key for the cache.
	 *
	 * @return string            The transient key.
	 */
	private static function _generateTransientKey($key) {
		return self::DOMAIN . '_' . $key;
	}

	/**
	 * Sets an item to the cache.
	 * 	
	 * @param string $key        The unique key for the cache.
	 * @param mixed  $value      The data to cache.
	 * @param int    $expiration Time until expiration in seconds from now, or 0 for never expires. Ex: For one day, the expiration value would be: (60 * 60 * 24).
	 * 
	 * @return void 
	 */
	private static function set($key, $value, $expiration) {
		if (!$key || !is_string($key)) {
			throw new \InvalidArgumentException('Invalid cache key');
		}
		if (!is_numeric($expiration)) {
			throw new \InvalidArgumentException('Invalid expiration');
		}

		$transientKey = self::_generateTransientKey($key);
		return set_transient($transientKey, $value, $expiration);
	}

	/**
	 * Deletes an item from the cache
	 * 
	 * @param  string $key     The unique key for the cache.
	 * @param  mixed  $default (Optional) If value doesn't exist, return default
	 *
	 * @return mixed           The item from the cache.
	 */
	public static function get($key, $default = false) {
		if (!$key || !is_string($key)) {
			throw new \InvalidArgumentException('Invalid cache key');
		}

		$transientKey = self::_generateTransientKey($key);
		$cached = get_transient($transientKey);

		return ($cached !== false ? $cached : $default);
	}

	/**
	 * Deletes an item from the cache
	 * 
	 * @param  string $key The unique key for the cache.
	 *
	 * @return void
	 */
	public static function delete($key) {
		if (!$key || !is_string($key)) {
			throw new \InvalidArgumentException('Invalid cache key');
		}

		$transientKey = self::_generateTransientKey($key);
		delete_transient($transientKey);
	}

	/**
	 * Deletes an item from the cache
	 * 
	 * @param  string $key The unique key for the cache.
	 *
	 * @return void
	 */
	public static function forget($key) {
		self::delete($key);
	}

	/**
	 * Retrieve an item from the cache and then delete it
	 * 
	 * @param  string $key The unique key for the cache.
	 *
	 * @return mixed       The cached data.
	 */
	public static function pull($key, $default) {
		$cached = self::get($key, $default);
		self::delete($key);

		return $cached;
	}

	/**
	 * Sets an item to the cache.
	 * 	
	 * @param string $key        The unique key for the cache.
	 * @param mixed  $value      The data to cache.
	 * @param int    $expiration Time until expiration in seconds from now, or 0 for never expires. Ex: For one day, the expiration value would be: (60 * 60 * 24).
	 * 
	 * @return void
	 */
	public static function put($key, $value, $expiration) {
		self::set($key, $value, $expiration);
	}

	/**
	 * Adds an item to cache if it doesn't previously exist.
	 * 	
	 * @param string $key        The unique key for the cache.
	 * @param mixed  $value      The data to cache.
	 * @param int    $expiration Time until expiration in seconds from now, or 0 for never expires. Ex: For one day, the expiration value would be: (60 * 60 * 24).
	 * 
	 * @return bool              True if added to cache
	 */
	public static function add($key, $value, $expiration) {
		if (!self::has($key)) {
			self::put($key, $value, $expiration);
			return true;
		}

		return false;
	}

	/**
	 * Adds an item to cache without expiration.
	 * 	
	 * @param string $key        The unique key for the cache.
	 * @param mixed  $value      The data to cache.
	 * 
	 * @return bool              True if added to cache
	 */
	public static function forever($key, $value) {
		self::put($key, $value, 0);
	}

	/**
	 * Checks if an item exists in the cache.
	 * 
	 * @param  string $key The unique key for the cache.
	 *
	 * @return boolean     True if item exists.
	 */
	public static function has($key) {
		$cached = self::get($key);

		if ($cached !== false) {
			return true;
		}

		return false;
	}

	/**
	 * Remembers a value.
	 * 
	 * @param  string   $key        The unique key for the cache
	 * @param  callable $cb         Callback that runs if the cache has expired.
	 * @param  array    $params     (optional) Callback parameters
	 *
	 * @return mixed             Depending on the callback.
	 */
	public static function rememberForever($key, callable $cb, array $params = array()) {
		self::remember($key, 0, $cb, $params);
	}

	/**
	 * Remembers a value for a set amount of time.
	 * 
	 * @param  string   $key        The unique key for the cache
	 * @param  int      $expiration Time until expiration in seconds from now, or 0 for never expires. Ex: For one day, the expiration value would be: (60 * 60 * 24).
	 * @param  callable $cb         Callback that runs if the cache has expired.
	 * @param  array    $params     (optional) Callback parameters
	 *
	 * @return mixed             Depending on the callback.
	 */
	public static function remember($key, $expiration, callable $cb, array $params = array()) {
		$cached = self::get($key);

		if ($cached === false) {
			$cached = call_user_func_array($cb, $params);
			self::set($key, $cached, $expiration);
		}
		
		return $cached;
	}
}