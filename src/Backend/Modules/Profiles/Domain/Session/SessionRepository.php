<?php

namespace Backend\Modules\Profiles\Domain\Session;

use DateTimeImmutable;
use Doctrine\ORM\EntityRepository;

final class SessionRepository extends EntityRepository
{
    public function add(Session $session): void
    {
        $this->getEntityManager()->persist($session);
        $this->getEntityManager()->flush();
    }

    public function remove(Session $session): void
    {
        $this->getEntityManager()->remove($session);
        $this->getEntityManager()->flush();
    }

    /**
     * Remove all sessions with date older then 1 month
     */
    public function cleanup(): void
    {
        $sessions = $this
            ->createQueryBuilder('s')
            ->where('s.date <= :date')
            ->setParameter(':date', new DateTimeImmutable('-1 month'))
            ->getQuery()
            ->getResult();

        foreach ($sessions as $session) {
            $this->remove($session);
        }
    }
}
