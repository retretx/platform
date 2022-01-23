<?php

namespace Rrmode\Platform\Packages;

class Package
{
    private ?array $composerJson;
    
    public function __construct(
        readonly public string $name,
        readonly public string $root,
        readonly public bool $isDev,
        readonly public string $version,
        readonly public string $prettyVersion,
        private ?string $description = null,
        private ?array $author = null,
        private ?string $license = null,
        private ?array $dependencies = null,
        private ?array $extra = null,
    ){}

    public function getDescription(): ?string
    {
        return $this->description ??= ($this->getParsedJson()['description'] ?? null);
    }

    public function getAuthor(): ?array
    {
        return $this->author ??= ($this->getParsedJson()['author'] ?? null);
    }

    public function getLicense(): ?string
    {
        return $this->license ??= ($this->getParsedJson()['license'] ?? null);
    }

    public function getDependencies(): array
    {
        return $this->dependencies ??= ($this->getParsedJson()['require'] ?? []);
    }

    public function getExtra(): array
    {
        return $this->extra ??= ($this->getParsedJson()['extra'] ?? []);
    }

    private function getParsedJson(): array
    {
        return $this->composerJson ??= $this->parseJson();
    }
    
    private function parseJson(): array
    {
        return json_decode($this->getComposerJson(), true);
    }

    private function getComposerJson(): string
    {
        return file_get_contents($this->getComposerJsonPath());
    }

    private function getComposerJsonPath(): string
    {
        return $this->root.DIRECTORY_SEPARATOR.'composer.json';
    }
}
