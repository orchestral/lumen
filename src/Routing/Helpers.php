<?php

namespace Laravel\Lumen\Routing;

use Orchestra\Routing\VersionedHelpers;
use Dingo\Api\Routing\Helpers as BaseHelpers;

trait Helpers
{
    use BaseHelpers, VersionedHelpers;

    /**
     * Get versioned resource class name.
     *
     * @param  string  $group
     * @param  string  $name
     *
     * @return string
     */
    protected function getVersionedResourceClassName($group, $name)
    {
        $class = str_replace('.', '\\', $name);
        $version = $this->getVersionNamespace();

        return sprintf('%s\%s\%s\%s', $this->namespace, $group, $version, $class);
    }

    /**
     * Get the version namespace.
     *
     * @return string
     */
    protected function getVersionNamespace()
    {
        $version = $this->api()->getVersion();
        $supported = $this->getSupportedVersionNamespace();

        if (isset($supported[$version])) {
            return $supported[$version];
        }

        return 'Base';
    }

    /**
     * Get supported version namespace.
     *
     * @return array
     */
    abstract protected function getSupportedVersionNamespace();
}
