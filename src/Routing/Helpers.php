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
        return $this->serializeWith($this->transformWith($instance, $name), $name);
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
        $version = $this->getVersionNamespace();
        $transformer = "{$this->namespace}\\Transformers\\{$version}\\{$name}";

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
        $version = $this->getVersionNamespace();
        $serializer = "{$this->namespace}\\Serializers\\{$version}\\{$name}";

        if (class_exists($serializer)) {
            return call_user_func(app($serializer), $instance);
        }

        if ($name !== 'Serializer') {
            return $this->serializeWith($instance, 'Serializer');
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
