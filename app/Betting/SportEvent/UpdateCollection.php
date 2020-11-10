<?php

namespace App\Betting\SportEvent;

class UpdateCollection
{
    private array $updates = [];
    private array $externalIds = [];
    private string $provider;

    public function __construct(string $provider, Update ...$updates)
    {
        foreach ($updates as $update) {
            $this->updates[$update->getExternalId()] = $update;
            $this->externalIds[] = $update->getExternalId();
        }

        $this->provider = $provider;
    }

    public function getProvider(): string
    {
        return $this->provider;
    }

    public function getExternalIds(): array
    {
        return $this->externalIds;
    }

    public function getUpdates(): array
    {
        return $this->updates;
    }

    public function hasUpdate(string $externalId): bool
    {
        return isset($this->updates[$externalId]);
    }

    public function getUpdate(string $externalId): Update
    {
        return $this->updates[$externalId];
    }
}
