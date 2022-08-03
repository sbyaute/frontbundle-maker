<?= "<?php\n" ?>

namespace <?= $namespace ?>;

use <?= $entity_full_class_name ?>;
use Cnam\FrontBundleBundle\Table\QueryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method <?= $entity_class_name ?>|null find($id, $lockMode = null, $lockVersion = null)
 * @method <?= $entity_class_name ?>|null findOneBy(array $criteria, array $orderBy = null)
 * @method <?= $entity_class_name ?>[]    findAll()
 * @method <?= $entity_class_name ?>[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class <?= $repository_class_name ?> extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, <?= $entity_class_name ?>::class);
    }

    // /**
    //  * @return <?= $entity_class_name ?>[] Returns an array of <?= $entity_class_name ?> objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?<?= $entity_class_name ?>
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function findByTableQuery(QueryInterface $tableQuery): Paginator
    {
        $queryBuilder = $this->createQueryBuilder('e');

        // @TODO Implement search criteria you want
//        if ($tableQuery->getSearch()) {
//            $queryBuilder->andWhere('e.libelle LIKE :search OR e.description LIKE :search')
//                ->setParameter('search', '%' . $tableQuery->getSearch(). '%')
//            ;
//            if (is_numeric($tableQuery->getSearch())) {
//                $queryBuilder->orWhere(' e.id = :search')
//                    ->setParameter('search', '' . $tableQuery->getSearch() . '');
//            }
//            if (strtolower($tableQuery->getSearch()) == 'true') {
//                $queryBuilder->orWhere(' e.actif = TRUE');
//            }
//            elseif (strtolower($tableQuery->getSearch()) == 'false') {
//                $queryBuilder->orWhere(' e.actif = FALSE');
//            }
//        }

        // @TODO Implement order criteria you want
        foreach ($tableQuery->getColumnOrders() as $order) {
            $queryBuilder->addOrderBy('e.' . $order->getColumnName(), $order->isAscending() ? 'asc' : 'desc');
        }

        $queryBuilder->setMaxResults($tableQuery->getLimit());
        $queryBuilder->setFirstResult($tableQuery->getOffset());

        return new Paginator($queryBuilder->getQuery());
    }
}