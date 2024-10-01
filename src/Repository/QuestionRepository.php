<?php

namespace App\Repository;

use App\Entity\Question;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Question>
 */
class QuestionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Question::class);
    }

    /**
     * @param array<int> $ids
     * @return Question[]
     */
    public function getModelsAsAssociativeArray(array $ids): array
    {
        $query = $this->createQueryBuilder('q')
            ->select('q')
            ->where('q.id in (:ids)')
            ->setParameter('ids', $ids)
            ->indexBy('q', 'q.id');

        return $query->getQuery()->getResult();
    }
}
