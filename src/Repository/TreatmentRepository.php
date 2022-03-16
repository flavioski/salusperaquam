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

namespace Flavioski\Module\SalusPerAquam\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

class TreatmentRepository extends EntityRepository
{
    public function getRandom($langId = 0, $limit = 0)
    {
        /** @var QueryBuilder $qb */
        $qb = $this->createQueryBuilder('q')
            ->addSelect('q')
        ;

        $ids = $this->getAllIds();
        shuffle($ids);
        if ($limit > 0) {
            $ids = array_slice($ids, 0, $limit);
        }
        $qb
            ->andWhere('q.id in (:ids)')
            ->setParameter('ids', $ids)
        ;

        $treatments = $qb->getQuery()->getResult();
        uasort($treatments, function ($a, $b) use ($ids) {
            return array_search($a->getId(), $ids) - array_search($b->getId(), $ids);
        });

        return $treatments;
    }

    public function getAllIds()
    {
        /** @var QueryBuilder $qb */
        $qb = $this
            ->createQueryBuilder('q')
            ->select('q.id')
        ;

        $treatments = $qb->getQuery()->getScalarResult();

        return array_map(function ($treatment) {
            return $treatment['id'];
        }, $treatments);
    }
}
