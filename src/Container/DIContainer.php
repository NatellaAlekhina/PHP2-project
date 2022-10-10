<?php

namespace App\Container;

use App\Exceptions\NotFoundException;
use Psr\Container\ContainerInterface;
use ReflectionClass;

class DIContainer implements ContainerInterface
{
    private array $resolvers = [];

    public function bind(string $abstract, mixed $resolver)
    {
        $this->resolvers[$abstract] = $resolver;
    }

    public function get(string $abstract)
    {
        if(array_key_exists($abstract,$this->resolvers))
        {
            $concrete = $this->resolvers[$abstract];

            if(is_object($concrete))
            {
                return $concrete;
            }

            return $this->get($concrete);
        }

        if(!class_exists($abstract))
        {
            throw new NotFoundException("Cannot resolve type: $abstract");
        }

        $reflectionClass = new ReflectionClass($abstract);
        $construct = $reflectionClass->getConstructor();

        if(!$construct)
        {
            return new $abstract();
        }

        $parameters = [];

        foreach ($construct->getParameters() as $parameter)
        {
            $parentAbstract = $parameter->getType()->getName();
            $parameters[] = $this->get($parentAbstract);
        }

        return new $abstract(...$parameters);
    }

    public function has(string $id): bool
    {
        return $this->resolvers[$id] ?? false;
    }
}