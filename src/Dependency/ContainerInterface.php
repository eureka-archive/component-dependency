<?php

/**
 * Copyright (c) 2010-2016 Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eureka\Component\Dependency;

;

/**
 * Interface for Container component.
 *
 * @author Romain Cottard
 */
interface ContainerInterface
{
    /**
     * @var string DATABASE Database key
     */
    const DATABASE = 'db';

    /**
     * @var string CACHE Cache key
     */
    const CACHE = 'cache';

    /**
     * @var string CACHE Cache key
     */
    const OBJECT = 'object';

    /**
     * Singleton getter.
     *
     * @return Container
     */
    public static function getInstance();

    /**
     * Get instance for given key.
     *
     * @param  string $key Key name to retrieve the instance
     * @return mixed
     * @throws  \LogicException
     */
    public function get($key);

    /**
     * Attach new instance of any class to this container.
     *
     * @param  string $key Key name to retrieve the instance
     * @param  object $instance Instance to attach
     * @param  string $type Object type
     * @return self
     * @throws \LogicException
     */
    public function attach($key, $instance, $type = self::OBJECT);

    /**
     * Detach Instance from container.
     * Implicit destruct the instance.
     *
     * @param  string $key Key name to retrieve the instance
     * @return self
     */
    public function detach($key);

    /**
     * Get database instance
     *
     * @param  string $config Config name
     * @return \PDO
     * @throws \LogicException
     */
    public function getDatabase($config);

    /**
     * Get cache instance
     *
     * @param  string $config Config name
     * @return \Eureka\Component\Cache\CacheWrapperAbstract
     * @throws \LogicException
     */
    public function getCache($config);
}
