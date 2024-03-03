<?php

declare(strict_types=1);

namespace App\Announcement\Domain;

use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Ramsey\Uuid\Uuid;

class Announcement
{
    public const int ALLOWED_FILES_COUNT = 5;

    public readonly string $id;

    public readonly DateTimeImmutable $postedAt;

    /** @var ArrayCollection<int, AnnouncementFile> */
    private Collection $files;

    /**
     * @throws AnnouncementNotValidException
     */
    public function __construct(
        ?string $id,
        public readonly string $title,
        public readonly string $description,
        public readonly float $cost,
    ) {
        $this->id = $id ?? Uuid::uuid7()->toString();
        $this->postedAt = new DateTimeImmutable();
        $this->files = new ArrayCollection();

        $errors = $this->validate();
        if (!empty($errors)) {
            throw new AnnouncementNotValidException(json_encode($errors));
        }
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'cost' => $this->cost,
            'postedAt' => $this->postedAt,
            'files' => array_map(fn (AnnouncementFile $file) => $file->jsonSerialize(), $this->getFiles()),
        ];
    }

    /**
     * @throws AnnouncementFilesCountExceededException
     */
    public function addFile(AnnouncementFile $file): self
    {
        $allowedFilesCount = self::ALLOWED_FILES_COUNT;
        if ($this->files->count() >= $allowedFilesCount) {
            throw new AnnouncementFilesCountExceededException("Announcement cannot have more than $allowedFilesCount files");
        }

        $this->files->set($file->id, $file);

        return $this;
    }

    /**
     * @return array<int, AnnouncementFile>
     */
    public function getFiles(): array
    {
        return $this->files->toArray();
    }

    public function updateFile(AnnouncementFile $announcementFile): self
    {
        $file = $this->files->get($announcementFile->id);
        $file->update($announcementFile);

        return $this;
    }

    public function getFile(string $fileId): ?AnnouncementFile
    {
        return $this->files->get($fileId);
    }

    private function validate(): array
    {
        $errors = [];

        if (empty($this->id)) {
            $errors['id'] = 'Id cannot be empty';
        }
        if (strlen($this->id) > 50) {
            $errors['id'] = 'Id cannot be longer than 50 characters';
        }

        if (empty($this->title)) {
            $errors['title'] = 'Title cannot be empty';
        }
        if (strlen($this->title) > 80) {
            $errors['title'] = 'Title cannot be longer than 80 characters';
        }

        if (empty($this->description)) {
            $errors['description'] = 'Description cannot be empty';
        }

        if ($this->cost < 0) {
            $errors['cost'] = 'Cost cannot be negative';
        }

        return $errors;
    }
}
