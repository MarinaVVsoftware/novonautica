<?php

namespace AppBundle\Repository\Contabilidad\Facturacion\Concepto;

/**
 * ClaveUnidadRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ClaveUnidadRepository extends \Doctrine\ORM\EntityRepository
{
    public function findAllLikeSelect2($query)
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT claveUnidad.id, claveUnidad.nombre AS text '.
                'FROM AppBundle:Contabilidad\Facturacion\Concepto\ClaveUnidad claveUnidad '.
                'WHERE claveUnidad.nombre LIKE ?1 OR claveUnidad.claveUnidad LIKE ?1'
            )
            ->setParameter(1, "%{$query}%")
            ->setMaxResults(10)
            ->getArrayResult();
    }
}
