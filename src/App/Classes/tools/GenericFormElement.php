<?php

declare(strict_types=1);

namespace tools;

abstract class GenericFormElement implements InputRenderInterface
{
    protected string $type;

    protected bool $required = false;

    protected mixed $value = '';

    public function __construct(
        protected readonly string $name, // Readonly cause property is immutable
        bool $required = false, 
        string $defaultValue = ''
    ) {
        $this->required = $required;
        $this->value = $defaultValue;
    }

    public function __toString(): string
    {
        return $this->render();
    }

    public function getId(): string 
    {
        return sprintf('form_%s', $this->name);
    }

    public function getName(): string 
    {
        return $this->name;
    }

    public function getValue(): mixed 
    {
        return $this->value;
    }

    public function isRequired(): bool
    {
        return $this->required;
    }
}

?>