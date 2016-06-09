<?php

/**
 * Copyright (c) 2010-2016 Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eureka\Component\Dependency;

use Eureka\Component\Cache\CacheWrapperAbstract;
use Eureka\Component\Database\Database;

/**
 * Container class.
 *
 * @author  Romain Cottard
 * @version 1.0.0
 */
class Container implements ContainerInterface
{
    /**
     * @var Container $instance
     */
    private static $instance = null;

    /**
     * @var \SplObjectStorage List of instances saved
     */
    protected $instances = array();

    /**
     * Container constructor.
     */
    protected function __construct()
    {
        $this->instances = array();
    }

    /**
     * Singleton getter.
     *
     * @return Container
     */
    public static function getInstance()
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Get instance for given key.
     *
     * @param  string $key Key name to retrieve the instance
     * @return object
     * @throws \LogicException
     */
    public function get($key)
    {
        if (!isset($this->instances[$key])) {
            throw new \LogicException('No instance for given key! (key: ' . $key . ')');
        }

        return $this->instances[$key];
    }

    /**
     * Attach new instance of any class to this container.
     *
     * @param  string $key Key name to retrieve the instance
     * @param  object $instance Instance to attach
     * @param  string $type Type of object
     * @return $this
     * @throws \LogicException
     */
    public function attach($key, $instance, $type = self::OBJECT)
    {
        switch ($type) {
            case self::OBJECT:
                if (!is_object($instance)) {
                    throw new \LogicException('Instance is not an object!');
                }
                break;
            case self::DATABASE:
                if (!($instance instanceof \PDO)) {
                    throw new \LogicException();
                }
                $key = self::DATABASE . '_' . $key;
                break;
            case self::CACHE:
                if (!($instance instanceof CacheWrapperAbstract)) {
                    throw new \LogicException();
                }
                $key = self::CACHE . '_' . $key;
                break;
        }

        if (isset($this->instances[$key])) {
            return $this;
        }

        $this->instances[$key] = $instance;

        return $this;
    }

    /**
     * Detach Instance from container.
     * Implicit destruct the instance.
     *
     * @param  string $key Key name to retrieve the instance
     * @return $this
     */
    public function detach($key)
    {
        if (isset($this->instances[$key])) {
            unset($this->instances[$key]);
        }

        return $this;
    }

    /**
     * Get database instance
     *
     * @param  string $config Database config name
     * @return \PDO
     * @throws \LogicException
     */
    public function getDatabase($config)
    {
        return $this->get(self::DATABASE . '_' . $config);
    }

    /**
     * Get cache instance
     *
     * @param  string $config Cache config name
     * @return CacheWrapperAbstract
     * @throws \LogicException
     */
    public function getCache($config)
    {
        return $this->get(self::CACHE . '_' . $config);
    }
}
