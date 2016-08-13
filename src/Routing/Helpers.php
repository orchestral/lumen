<?php

namespace Laravel\Lumen\Routing;

use Dingo\Api\Routing\Helpers as BaseHelpers;

trait Helpers
{
    use BaseHelpers;

    /**
     * Transform and serialize the instance.
     *
     * @param  \Illuminate\Database\Eloquent\Model|\Illuminate\Support\Collection  $instance
     * @param  string  $name
     *
     * @return mixed
     */
    protected function transform($instance, $name)
    {
        $version     = $this->getVersionNamespace();
        $transformer = "{$this->namespace}\\Transformers\\{$version}\\{$name}";
        $serializer  = "{$this->namespace}\\Serializers\\{$version}\\{$name}";

        if (class_exists($transformer)) {
            $instance = $instance->transform(app($transformer));
        }

        if (class_exists($serializer)) {
            return call_user_func(app($serializer), $instance);
        }

        return $instance;
    }

    /**
     * Get the version namespace.
     *
     * @return string
     */
    protected function getVersionNamespace()
    {
        $version   = $this->api()->getVersion();
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
