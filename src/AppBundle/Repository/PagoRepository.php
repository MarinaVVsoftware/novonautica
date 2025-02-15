<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Pago;
use Doctrine\ORM\NonUniqueResultException;

/**
 * PagoRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PagoRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * @param $id
     *
     * @return Pago|null
     */
    public function getOne($id)
    {
        $query = $this->getEntityManager()
            ->createQuery(
                'SELECT pago '.
                'FROM AppBundle:Pago pago '.
                'WHERE pago.id = ?1'
            )
            ->setParameter(1, $id);

        try {
            return $query->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            return null;
        }
    }
}
