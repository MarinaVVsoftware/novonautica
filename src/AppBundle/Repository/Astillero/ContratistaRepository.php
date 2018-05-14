<?php

namespace AppBundle\Repository\Astillero;

/**
 * ContratistaRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ContratistaRepository extends \Doctrine\ORM\EntityRepository
{
    public function getTrabajosByProveedor($proveedor, $inicio, $fin)
    {
        return $this->createQueryBuilder('t')
            ->select('t', 'o', 'pa', 'c', 'b')
            ->leftJoin('t.proveedor', 'p')
            ->leftJoin('t.astilleroODT', 'o')
            ->leftJoin('t.contratistapagos', 'pa')
            ->leftJoin('o.astilleroCotizacion', 'c')
            ->leftJoin('c.barco', 'b')
            ->where('o.fecha BETWEEN :inicio AND :fin')
            ->andWhere('p.id = :proveedor')
            ->setParameters([
                'proveedor' => $proveedor,
                'inicio' => $inicio,
                'fin' => $fin
            ])
            ->getQuery()
            ->getResult();
    }

    public function getTrabajosByOdt()
    {

    }
}
