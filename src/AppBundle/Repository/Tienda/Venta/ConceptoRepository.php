<?php

namespace AppBundle\Repository\Tienda\Venta;

/**
 * ConceptoRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ConceptoRepository extends \Doctrine\ORM\EntityRepository
{
    public function getAllByVenta($ventaId)
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT concepto, producto '.
                'FROM AppBundle:Tienda\Venta\Concepto concepto '.
                'LEFT JOIN concepto.producto producto '.
                'WHERE IDENTITY(concepto.venta) = ?1'
            )
            ->setParameter(1, $ventaId)
            ->getResult();
    }

    /**
     * El metodo de getOneWithCatalogo es para las facturaciones genera los conceptos de la tabla
     *
     * @param $id
     *
     * @return array
     */
    public function getOneWithCatalogo($id)
    {
        $manager = $this->getEntityManager();

        $pseudoQuery = 'SELECT '.
            'concepto.cantidad AS conceptoCantidad, concepto.total AS conceptoImporte, '.
            'producto.nombre AS conceptoDescripcion, '.
            'cps.id AS cpsId, cps.descripcion as cpsDescripcion, '.
            'cu.id AS cuId, cu.nombre AS cuDescripcion '.
            'FROM AppBundle:Tienda\Venta\Concepto concepto '.
            'LEFT JOIN concepto.producto producto '.
            'LEFT JOIN producto.claveProdServ cps '.
            'LEFT JOIN producto.claveUnidad cu ';

        if ($id === 'ALL') {
            // Fixme el valor fijo de nuevo donde se espera que el cliente publico en general sea igual a 413

            return $manager->createQuery(
                $pseudoQuery.
                'LEFT JOIN concepto.venta venta '.
                'WHERE IDENTITY(venta.cliente) = 413')
                ->getArrayResult();
        }

        return $manager->createQuery($pseudoQuery. 'WHERE concepto.venta = :id')
            ->setParameter('id', $id)
            ->getArrayResult();
    }

    public function getReporteVentas($idproducto,$inicio,$fin)
    {
        $resultado = [];
        $condicion_producto = '';
        if($idproducto !== '0'){$condicion_producto=' AND producto.id = :idproducto ';}
        $qry = 'SELECT concepto, producto, venta '.
            'FROM AppBundle:Tienda\Venta\Concepto concepto '.
            'LEFT JOIN concepto.producto producto '.
            'LEFT JOIN concepto.venta venta '.
            'WHERE venta.createdAt BETWEEN :inicio AND :fin '.
            $condicion_producto.
            'ORDER BY venta.createdAt ASC';
        $ventas = $this->getEntityManager()->createQuery($qry);
        $ventas->setParameter('inicio',$inicio);
        $ventas->setParameter('fin',date('Y-m-d H:i:s',strtotime('+23 hour +59 minutes +59 seconds',strtotime($fin))));
        if($idproducto !== '0'){$ventas->setParameter('idproducto',$idproducto);}
        if($ventas){
            foreach ($ventas->getArrayResult() as $venta){
                array_push($resultado,[
                    'fecha' => $venta['venta']['createdAt']->format('d/m/Y'),
                    'producto' => $venta['producto']['nombre'],
                    'cantidad' => $venta['cantidad'],
                    'precio' => $this->esMoneda($venta['precioUnitario']),
                    'subtotal' => $this->esMoneda($venta['subtotal']),
                    'iva' => $this->esMoneda($venta['iva']),
                    'descuento' => $this->esMoneda($venta['descuento']),
                    'total' => $this->esMoneda($venta['total']),
                ]);
            }
        }else{
            array_push($resultado,[
                'fecha' => '',
                'producto' => '',
                'cantidad' => 0,
                'precio' => $this->esMoneda(0),
                'subtotal' => $this->esMoneda(0),
                'iva' => $this->esMoneda(0),
                'descuento' => $this->esMoneda(0),
                'total' => $this->esMoneda(0),
            ]);
        }
        return $resultado;
    }
    function esMoneda($valor){
        return '$'.number_format($valor/100,2);
    }
}
