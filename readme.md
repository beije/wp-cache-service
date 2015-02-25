# Wordpress cache service

This is a small class that wraps the [Transient API](http://codex.wordpress.org/Transients_API) into a easier to use service. Note this is not a replacement for Transients API only an enhancement.

The class is built to behave in the same way as [Laravel 4.2's cache service](http://laravel.com/docs/4.2/cache).

[Read more about the class here.](http://benjaminhorn.io/code/improving-transient-cache-in-wordpress/)

## Basic usage
	function generateValue() {
		// Return the value either through the cache or by regenerating and updating the cache.
		return Cache::remember(
			'myCachedValue',
			12*(60*60),   // cache for 12 hours.
			function() {
				return // Something value
			}
		);
	}

## Available methods
These are all the available static methods in the class.

### get()
    /**
	 * Gets an item from the cache
	 *
	 * @param  string $key     The unique key for the cache.
	 * @param  mixed  $default (Optional) If value doesn't exist, return default
	 *
	 * @return mixed           The item from the cache.
	 */
	CacheService::get($key, $default = false);

### delete()
	/**
	 * Deletes an item from the cache
	 *
	 * @param  string $key The unique key for the cache.
	 *
	 * @return void
	 */
	CacheService::delete($key);

### forget()
	/**
	 * Deletes an item from the cache
	 *
	 * @param  string $key The unique key for the cache.
	 *
	 * @return void
	 */
	CacheService::forget($key);

### pull()
	/**
	 * Retrieve an item from the cache and then delete it
	 *
	 * @param  string $key The unique key for the cache.
	 *
	 * @return mixed       The cached data.
	 */
	CacheService::pull($key, $default);

### put()
	/**
	 * Sets an item to the cache.
	 *
	 * @param string $key        The unique key for the cache.
	 * @param mixed  $value      The data to cache.
	 * @param int    $expiration Time until expiration in seconds from now, or 0 for never expires. Ex: For one day, the expiration value would be: (60 * 60 * 24).
	 *
	 * @return void
	 */
	CacheService::put($key, $value, $expiration);

### add()
	/**
	 * Adds an item to cache if it doesn't previously exist.
	 *
	 * @param string $key        The unique key for the cache.
	 * @param mixed  $value      The data to cache.
	 * @param int    $expiration Time until expiration in seconds from now, or 0 for never expires. Ex: For one day, the expiration value would be: (60 * 60 * 24).
	 *
	 * @return bool              True if added to cache
	 */
	CacheService::add($key, $value, $expiration);

### forever()
	/**
	 * Adds an item to cache without expiration.
	 *
	 * @param string $key        The unique key for the cache.
	 * @param mixed  $value      The data to cache.
	 *
	 * @return bool              True if added to cache
	 */
	CacheService::forever($key, $value);

### has()
	/**
	 * Checks if an item exists in the cache.
	 *
	 * @param  string $key The unique key for the cache.
	 *
	 * @return boolean     True if item exists.
	 */
	CacheService::has($key);

### rememberForever()
	/**
	 * Remembers a value.
	 *
	 * @param  string   $key        The unique key for the cache
	 * @param  callable $cb         Callback that runs if the cache has expired.
	 * @param  array    $params     (optional) Callback parameters
	 *
	 * @return mixed             Depending on the callback.
	 */
	CacheService::rememberForever($key, callable $cb, array $params = array());

### remember()
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
	CacheService::remember($key, $expiration, callable $cb, array $params = array());

### emptyCache()
	/**
	 * Clears the cache completely
	 *
	 * @return void
	 */
	CacheService::emptyCache()
