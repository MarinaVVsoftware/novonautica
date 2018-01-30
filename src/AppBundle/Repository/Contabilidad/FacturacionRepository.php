<?php

namespace AppBundle\Repository\Contabilidad;

/**
 * FacturacionRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class FacturacionRepository extends \Doctrine\ORM\EntityRepository
{
    public function getCotizaciones($folio)
    {
        $folios = explode('-', $folio);

        $marinaCotizaciones = $this->getMarinaCotizacionesByFolio($folios[0], $folios[1] ?? null);
        $astilleroCotizaciones = $this->getAstilleroCotizacionesByFolio($folios[0], $folios[1] ?? null);

        return array_merge($marinaCotizaciones, $astilleroCotizaciones);
    }

    public function getPagosFacturaGlobal()
    {
        $em = $this->getEntityManager();
        $dql = '
        SELECT
        cotizacion,
        pagos,
        servicios,
        servicioBasico,
        servicioProducto,
        servicioRegular
        FROM AppBundle:AstilleroCotizacion AS cotizacion
        LEFT JOIN cotizacion.pagos AS pagos
        LEFT JOIN cotizacion.acservicios AS servicios
        LEFT JOIN cotizacion.cliente AS cliente
        LEFT JOIN cliente.razonesSociales AS razonSocial
        LEFT JOIN servicios.astilleroserviciobasico AS servicioBasico
        LEFT JOIN servicios.producto AS servicioProducto
        LEFT JOIN servicios.servicio AS servicioRegular
        WHERE cotizacion.validanovo = 2
        AND pagos.id IS NOT NULL
        AND pagos.factura IS NULL
        AND razonSocial.id IS NULL
        ';

        $atQuery = $em->createQuery($dql)->getResult();

        $dql = '
        SELECT 
        cotizacion,
        pagos,
        servicio,
        movimiento
        FROM AppBundle:MarinaHumedaCotizacion AS cotizacion
        LEFT JOIN cotizacion.pagos AS pagos
        LEFT JOIN cotizacion.mhcservicios AS servicio
        LEFT JOIN cotizacion.slipmovimiento AS movimiento
        LEFT JOIN cotizacion.cliente AS cliente
        LEFT JOIN cliente.razonesSociales AS razonSocial
        WHERE cotizacion.validanovo = 2
        AND pagos.id IS NOT NULL
        AND pagos.factura IS NULL
        AND razonSocial.id IS NULL
        ';

        $mhQuery = $em->createQuery($dql)->getResult();


        return array_merge($mhQuery, $atQuery);
    }

    public function getPagosByFolioCotizacion($folio, $folioRecotizado = null)
    {
        $em = $this->getEntityManager();
        $dql = '
        SELECT 
        pagos, mhc, atc, movimiento
        FROM AppBundle:Pago AS pagos
        LEFT JOIN pagos.mhcotizacion AS mhc
        LEFT JOIN pagos.acotizacion AS atc
        LEFT JOIN mhc.slipmovimiento AS movimiento
        WHERE mhc.folio = :folio OR atc.folio = :folio
        ';

        if ($folioRecotizado) {
            $dql .= 'AND mhc.foliorecotiza = :foliorecotizado OR atc.foliorecotiza = :foliorecotizado';
            $query = $em->createQuery($dql)
                ->setParameter(':folio', $folio)
                ->setParameter(':foliorecotizado', $folioRecotizado);
        } else {
            $query = $em->createQuery($dql)
                ->setParameter(':folio', $folio);
        }


        return $query->getResult();
    }

    private function getMarinaCotizacionesByFolio($folio, $folioRecotizado = null)
    {
        $em = $this->getEntityManager();
        // Consume mas recursos ??
        $dql = '
        SELECT 
        cotizacion,
        pagos,
        servicio,
        movimiento
        FROM AppBundle:MarinaHumedaCotizacion AS cotizacion
        LEFT JOIN cotizacion.pagos AS pagos
        LEFT JOIN cotizacion.mhcservicios AS servicio
        LEFT JOIN cotizacion.slipmovimiento AS movimiento
        WHERE cotizacion.validanovo = 2
        AND pagos.id IS NOT NULL
        AND pagos.factura IS NULL
        AND cotizacion.folio LIKE :folio
        ';

        if ($folioRecotizado) {
            $dql .= 'AND cotizacion.foliorecotiza LIKE :foliorecotiza';
            $query = $em->createQuery($dql)
                ->setParameter('folio', "%{$folio}%")
                ->setParameter('foliorecotiza', "%{$folioRecotizado}%");
        } else {
            $query = $em->createQuery($dql)
                ->setParameter('folio', "%{$folio}%");
        }

        return $query->getResult();
    }

    private function getAstilleroCotizacionesByFolio($folio, $folioRecotizado = null)
    {
        $em = $this->getEntityManager();
        $dql = '
        SELECT
        cotizacion,
        pagos,
        servicios,
        servicioBasico,
        servicioProducto,
        servicioRegular
        FROM AppBundle:AstilleroCotizacion AS cotizacion
        LEFT JOIN cotizacion.pagos AS pagos
        LEFT JOIN cotizacion.acservicios AS servicios
        LEFT JOIN servicios.astilleroserviciobasico AS servicioBasico
        LEFT JOIN servicios.producto AS servicioProducto
        LEFT JOIN servicios.servicio AS servicioRegular
        WHERE cotizacion.validanovo = 2
        AND pagos.id IS NOT NULL
        AND pagos.factura IS NULL
        AND cotizacion.folio LIKE :folio
        ';

        if ($folioRecotizado) {
            $dql .= 'AND cotizacion.foliorecotiza LIKE :foliorecotiza';
            $query = $em->createQuery($dql)
                ->setParameter('folio', "%{$folio}%")
                ->setParameter('foliorecotiza', "%{$folioRecotizado}%");
        } else {
            $query = $em->createQuery($dql)
                ->setParameter('folio', "%{$folio}%");
        }

        return $query->getResult();
    }
}
