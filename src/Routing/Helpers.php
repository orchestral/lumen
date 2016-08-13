<?php

namespace Laravel\Lumen\Routing;

use Dingo\Api\Routing\Helpers;

trait Helpers
{
    use Helpers;

    /**
     * Transform and serialize the instance.
     * @param  \Illuminate\Database\Eloquent\Model|\Illuminate\Support\Collection  $instance
     * @param  string  $name
     *
     * @return mixed
     */
    protected function transform($instance, $name)
    {
        $namespace   = $this->getVersionNamespace();
        $transformer = "{$this->namespace}\\Transformers\\{$version}\\{$name}";
        $serializer  = "{$this->namespace}\\Serializers\\{$version}\\{$name}";

        if (class_exists($transformer, false)) {
            $instance = $instance->transform(app($transfomer));
        }

        if (class_exists($serializer, false)) {
            return app($serializer)($instance);
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
