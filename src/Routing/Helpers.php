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
     * @param  string|null  $serializer
     *
     * @return mixed
     */
    protected function transform($instance, $transformer, $serializer = null)
    {
        if (is_null($serializer)) {
            $serializer = $transformer;
        }

        return $this->serializeWith($this->transformWith($instance, $transformer), $serializer);
    }

    /**
     * Transform the instance.
     *
     * @param  \Illuminate\Database\Eloquent\Model|\Illuminate\Support\Collection  $instance
     * @param  string  $name
     *
     * @return mixed
     */
    protected function transformWith($instance, $name)
    {
        $transformer = $this->getVersionedResourceClassName($name);

        if (class_exists($transformer)) {
            return $instance->transform(app($transformer));
        }

        return $instance;
    }

    /**
     * Transform the instance.
     *
     * @param  mixed  $instance
     * @param  string  $name
     *
     * @return mixed
     */
    protected function serializeWith($instance, $name)
    {
        $serializer = $this->getVersionedResourceClassName($name);

        if (class_exists($serializer)) {
            return call_user_func(app($serializer), $instance);
        }

        return $instance;
    }

    /**
     * Get versioned resource class name.
     *
     * @param  string  $name
     *
     * @return string
     */
    protected function getVersionedResourceClassName($name)
    {
        $class   = str_replace('.', '\\', $name);
        $version = $this->getVersionNamespace();

        return sprintf('%s\%s\%s', $this->namespace, $version, $class);
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
