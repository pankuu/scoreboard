<?php

namespace App\Repository;

use App\Entity\Game;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Game>
 *
 * @method Game|null find($id, $lockMode = null, $lockVersion = null)
 * @method Game|null findOneBy(array $criteria, array $orderBy = null)
 * @method Game[]    findAll()
 * @method Game[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GameRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Game::class);
    }

    public function save(Game $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Game $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return array<int, array<string, mixed>>
     *
     * @throws Exception
     */
    public function games(): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT home_team as homeTeam,
                   away_team as awayTeam, 
                   home_team_score as homeTeamScore,
                   away_team_score as awayTeamScore,
                   home_team_score + away_team_score AS score
                FROM game
                WHERE end_time IS NOT NULL
                GROUP BY start_time
                ORDER BY score DESC;';

        $stmt = $conn->prepare($sql);

        return $stmt->executeQuery()
            ->fetchAllAssociative();
    }

    /**
     * @param int|null $id
     *
     * @return int
     */
    public function finish(int|null $id): int
    {
        $qb = $this->createQueryBuilder('u');

        return $qb->update(Game::class, 'u')
            ->set('u.endTime', ':endTime')
            ->where('u.id = :id')
            ->setParameter('endTime', new \DateTime())
            ->setParameter('id', $id)
            ->getQuery()
            ->execute();
    }
}
