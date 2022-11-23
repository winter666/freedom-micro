<?php


namespace Freedom\Modules;


class ReflectionService
{
    protected array $dependencies = [];
    protected \ReflectionMethod $rMethod;
    protected \ReflectionClass $rClass;

    public function __construct(protected string $class, protected string $method, ...$replaceInstances) {
        $this->rMethod = new \ReflectionMethod($class, $method);
        $this->rClass = new \ReflectionClass($class);
        $parameters = $this->rMethod->getParameters();
        $this->setDependencies($parameters, $replaceInstances);
    }

    public function reflectInstance(): object
    {
        return new $this->class(...$this->dependencies);
    }

    public function getDependencies(): array
    {
        return $this->dependencies;
    }

    protected function setDependencies(array $parameters, array $replaceInstances) {
        $dependencies = [];
        foreach ($parameters as $parameter) {
            $dependenceClass = (string) $parameter->getType();
            $dependencies[] = new $dependenceClass();
        }

        foreach ($replaceInstances as $instance) {
            $dependencies = array_map(fn ($item) => ($item instanceof $instance) ? $instance : $item, $dependencies);
        }

        $this->dependencies = $dependencies;
    }
}
