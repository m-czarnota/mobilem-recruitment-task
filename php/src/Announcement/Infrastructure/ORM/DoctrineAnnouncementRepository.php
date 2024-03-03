<?php

declare(strict_types=1);

namespace App\Announcement\Infrastructure\ORM;

use App\Announcement\Domain\Announcement;
use App\Announcement\Domain\AnnouncementRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Announcement>
 *
 * @method Announcement|null find($id, $lockMode = null, $lockVersion = null)
 * @method Announcement|null findOneBy(array $criteria, array $orderBy = null)
 * @method Announcement[]    findAll()
 * @method Announcement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DoctrineAnnouncementRepository extends ServiceEntityRepository implements AnnouncementRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Announcement::class);
    }

    public function add(Announcement $announcement): void
    {
        $em = $this->getEntityManager();
        $em->persist($announcement);

        foreach ($announcement->getFiles() as $file) {
            $em->persist($file);
        }
    }

    public function findOneById(string $id): ?Announcement
    {
        return $this->find($id);
    }

    public function findPage(int $pageNumber, int $pageSize): array
    {
        return $this->createQueryBuilder('a')
            ->setMaxResults($pageSize)
            ->setFirstResult(($pageNumber - 1) * $pageSize)
            ->getQuery()
            ->getResult();
    }

    public function findCount(): int
    {
        return $this->count();
    }
}
