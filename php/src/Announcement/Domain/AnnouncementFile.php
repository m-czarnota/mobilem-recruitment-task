<?php

declare(strict_types=1);

namespace App\Announcement\Domain;

use JsonSerializable;
use Ramsey\Uuid\Uuid;

class AnnouncementFile implements JsonSerializable
{
    public readonly string $id;

    /**
     * @throws AnnouncementFileNotValidException
     */
    public function __construct(
        ?string $id,
        private string $name,
        private string $path,
    ) {
        $this->id = $id ?? Uuid::uuid7()->toString();

        $errors = $this->validate();
        if (!empty($errors)) {
            throw new AnnouncementFileNotValidException(json_encode($errors));
        }
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'path' => $this->path,
        ];
    }

    public function update(self $announcementFile): self
    {
        $this->name = $announcementFile->getName();
        $this->path = $announcementFile->getPath();

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    private function validate(): array
    {
        $errors = [];

        if (strlen($this->name) > 255) {
            $errors['name'] = 'Name cannot be longer than 255 characters';
        }

        if (empty($this->path)) {
            $errors['path'] = 'Path cannot be empty';
        }

        return $errors;
    }
}
