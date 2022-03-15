<?php
/**
 * Salus per Aquam
 * Copyright since 2021 Flavio Pellizzer and Contributors
 * <Flavio Pellizzer> Property
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the MIT
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/MIT
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to flappio.pelliccia@gmail.com so we can send you a copy immediately.
 *
 * @author    Flavio Pellizzer
 * @copyright Since 2021 Flavio Pellizzer
 * @license   https://opensource.org/licenses/MIT
 */
declare(strict_types=1);

namespace Flavioski\Module\SalusPerAquam\Grid\Query;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use PrestaShop\PrestaShop\Core\Grid\Query\AbstractDoctrineQueryBuilder;
use PrestaShop\PrestaShop\Core\Grid\Query\DoctrineSearchCriteriaApplicatorInterface;
use PrestaShop\PrestaShop\Core\Grid\Search\SearchCriteriaInterface;

class TreatmentQueryBuilder extends AbstractDoctrineQueryBuilder
{
    /**
     * @var DoctrineSearchCriteriaApplicatorInterface
     */
    private $searchCriteriaApplicator;

    /**
     * @var int
     */
    private $languageId;

    /**
     * @var int
     */
    private $shopId;

    /**
     * @param Connection $connection
     * @param string $dbPrefix
     * @param DoctrineSearchCriteriaApplicatorInterface $searchCriteriaApplicator
     * @param int $languageId
     * @param int $shopId
     */
    public function __construct(
        Connection                                $connection,
                                                  $dbPrefix,
        DoctrineSearchCriteriaApplicatorInterface $searchCriteriaApplicator,
        $languageId,
        $shopId
    )
    {
        parent::__construct($connection, $dbPrefix);

        $this->searchCriteriaApplicator = $searchCriteriaApplicator;
        $this->languageId = $languageId;
        $this->shopId = $shopId;
    }

    /**
     * {@inheritdoc}
     */
    public function getSearchQueryBuilder(SearchCriteriaInterface $searchCriteria)
    {
        $qb = $this->getQueryBuilder($searchCriteria->getFilters());
        $qb
            ->select('q.id_treatment, q.name, q.code, q.price')
            ->groupBy('q.id_treatment');

        $this->searchCriteriaApplicator
            ->applySorting($searchCriteria, $qb)
            ->applyPagination($searchCriteria, $qb)
        ;

        return $qb;
    }

    /**
     * {@inheritdoc}
     */
    public function getCountQueryBuilder(SearchCriteriaInterface $searchCriteria)
    {
        $qb = $this->getQueryBuilder($searchCriteria->getFilters())
            ->select('COUNT(DISTINCT q.id_treatment)');

        return $qb;
    }

    /**
     * Get generic query builder.
     *
     * @param array $filters
     *
     * @return QueryBuilder
     */
    private function getQueryBuilder(array $filters)
    {
        $allowedFilters = [
            'id_treatment',
            'name',
            'code',
            'price',
            'product_name',
            'product_attribute_name',
            'active',
        ];

        $allowedFiltersMap = [
            'id_treatment' => 'q.id_treatment',
            'name' => 'q.name',
            'code' => 'q.code',
            'price' => 'q.price',
            'product_name' => 'pl.name',
            'product_attribute_name' => 'al.name',
            'active' => 'q.active',
        ];

        $qb = $this->connection->createQueryBuilder();
        $qb
            ->from($this->dbPrefix . 'treatment', 'q')
            ->leftJoin('q',
                $this->dbPrefix . 'product_lang',
                'pl',
                $qb->expr()->andX(
                    $qb->expr()->eq('pl.`id_product`', 'q.`id_product`'),
                    $qb->expr()->andX($qb->expr()->isNotNull('q.`id_product`')),
                    $qb->expr()->andX($qb->expr()->eq('pl.`id_shop`', ':shopId')),
                    $qb->expr()->andX($qb->expr()->eq('pl.`id_lang`', ':langId'))
                )
            )
            ->leftJoin('q',
                $this->dbPrefix . 'product_attribute_combination',
                'pac',
                $qb->expr()->andX(
                    $qb->expr()->eq('q.`id_product_attribute`', 'pac.`id_product_attribute`'),
                    $qb->expr()->andX($qb->expr()->isNotNull('q.`id_product_attribute`'))
                )
            )
            ->leftJoin('pac',
                $this->dbPrefix . 'attribute_lang',
                'al',
                $qb->expr()->andX(
                    $qb->expr()->eq('pac.`id_attribute`', 'al.`id_attribute`'),
                    $qb->expr()->andX($qb->expr()->isNotNull('pac.`id_attribute`')),
                    $qb->expr()->andX($qb->expr()->eq('al.`id_lang`', ':langId'))
                )
            )
        ;
        $qb->andWhere('q.`deleted` = 0');
        $qb->setParameter('shopId', $this->languageId);
        $qb->setParameter('langId', $this->languageId);

        foreach ($filters as $name => $value) {
            if (!in_array($name, $allowedFilters, true)) {
                continue;
            }

            if ('id_treatment' === $name) {
                $qb->andWhere($allowedFiltersMap[$name] . ' = :' . $name);
                $qb->setParameter($name, $value);

                continue;
            }

            if ('active' === $name) {
                $qb->andWhere($allowedFiltersMap[$name] . ' = :' . $name);
                $qb->setParameter($name, $value);

                continue;
            }

            $qb->andWhere($allowedFiltersMap[$name] . ' LIKE :' . $name);
            $qb->setParameter($name, '%' . $value . '%');
        }

        return $qb;
    }
}
