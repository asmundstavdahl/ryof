<?php

declare(strict_types=1);

use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class MyContainer extends ArrayContainer
{
    /**
     * @var self
     */
    private static $instance;

    public function get($id)
    {
        /**
         * Generate singleton on first get
         */
        if (!parent::has($id) && parent::has("singletons")) {
            $singletons = parent::get("singletons");
            if (array_search($id, $singletons) !== false) {
                $factories = parent::get("factories");
                if (isset($factories[$id])) {
                    $instance = parent::get("factories")[$id]($this);
                    $this->set($id, $instance);
                }
            }
        }

        return parent::get($id);
    }

    public function has($id): bool
    {
        if (parent::has($id)) {
            return true;
        }

        if (parent::has("singletons")) {
            $singletonClasses = parent::get("singletons");
            if (array_search($id, $singletonClasses) !== false) {
                return true;
            }
        }

        return false;
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            throw new LogicException("Container not initialized");
        }

        return self::$instance;
    }

    public static function setInstance(self $myContainer)
    {
        self::$instance = $myContainer;
    }
}
