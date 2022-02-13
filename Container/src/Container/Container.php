<?php


namespace App\container;

use App\Container\ContainerInterface;
use App\Container\Exception\DependencyIsNotInstantiableException;
use App\Container\Exception\DependencyHasNoDefaultValueException;

use ReflectionClass;

class Container implements ContainerInterface
{
    private array $instance = [];

    private function set($id, $concrete = null)
    {
        if($concrete === null)
        {
           $concrete = $id ; 
        }
        $this->instance[$id] = $concrete;
    }
    public function get($id)
    {
       
        if(!$this->has($id))
        { 
            $this->set($id);
        }

        
        $concrete = $this->instance[$id];
        return $this->resolve($concrete);

    }

    public function has($id)
    {
        return isset($this->instance[$id]);
    }

    private function resolve($concrete)
    {
      
        $reflection = new ReflectionClass($concrete);
        if(! $reflection->isInstantiable())
        {
            //chech ifthere is a class or not
            //so if not will throw exception ""go first create a class

            throw new DependencyIsNotInstantiableException("DependencyIsNotInstantiableException");
        }    

        $constructor = $reflection->getConstructor();
        if(is_null($constructor))
        {
            return $reflection->newInstance();
        }

        $parameters = $constructor->getParameters();
        $dependencies = $this->getDependencies($parameters,$reflection);

        return $reflection->newInstanceArgs($dependencies);

    }

    public function getDependencies($parameters,$reflection)
    {
        $dependencies = [];

        foreach($parameters as $parameter)
        {
            $dependency = $parameter->getClass();
            if(is_null($dependency))
            {
                if($parameter->isDefaultValueAvailable())
                {
                    $dependencies[] = $parameter->getDefaultValue();
                }else
                {
                    throw new DependencyHasNoDefaultValueException("DependencyHasNoDefaultValueException");
                }
            }else
            {
                $dependencies[] = $this->get($dependency->name);
            }


        }
        return $dependencies;
    }
}
