<?php
namespace App\Betting\SportEvent;

class Sport
{
    private string $id;
    private string $name;
    private string $provider;

    public function __construct(string $id, string $name, string $provider)
    {
        $this->id = $id;
        $this->name = $name;
        $this->provider = $provider;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getProvider(): string
    {
        return $this->provider;
    }
}
