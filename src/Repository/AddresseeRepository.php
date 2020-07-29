<?php


namespace App\Repository;

use App\Entity\Addressee;
use App\Pagination\Paginator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use function Symfony\Component\String\u;


class AddresseeRepository extends ServiceEntityRepository
{
    public const NUM_ITEMS = 10;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Addressee::class);
    }

    public function getAll(int $page = 1): Paginator
    {
        $qb = $this->createQueryBuilder('p')
            ->select('p.id, p.firstname, p.lastname, p.phone_number, p.email, p.birthday')
            ->orderBy('p.lastname', 'ASC')
        ;


        return (new Paginator($qb))->paginate($page);
    }

    /**
     * @return array
     */
    public function findBySearchQuery(string $query, int $limit = self::NUM_ITEMS): array
    {
        $searchTerms = $this->extractSearchTerms($query);

        if (0 === \count($searchTerms)) {
            return [];
        }

        $queryBuilder = $this->createQueryBuilder('p');

        foreach ($searchTerms as $key => $term) {
            $queryBuilder
                ->orWhere('p.firstname LIKE :t_'.$key)
                ->orWhere('p.lastname LIKE :t_'.$key)
                ->setParameter('t_'.$key, '%'.$term.'%')
            ;
        }

        return $queryBuilder
            ->orderBy('p.lastname', 'ASC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * Transforms the search string into an array of search terms.
     */
    private function extractSearchTerms(string $searchQuery): array
    {
        $searchQuery = u($searchQuery)->replaceMatches('/[[:space:]]+/', ' ')->trim();
        $terms = array_unique(u($searchQuery)->split(' '));

        // ignore the search terms that are too short
        return array_filter($terms, function ($term) {
            return 2 <= u($term)->length();
        });
    }
}
