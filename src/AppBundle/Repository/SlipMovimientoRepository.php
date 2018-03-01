<?php

namespace AppBundle\Repository;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;

/**
 * SlipMovimientoRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class SlipMovimientoRepository extends \Doctrine\ORM\EntityRepository
{
    public function ordenaFechasEstadia()
    {
        $qb = $this->createQueryBuilder('smov');

        return $qb
            ->select('smov')
            ->orderBy('smov.fechaLlegada', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function ordenaDescendente()
    {
        $qb = $this->createQueryBuilder('smov');
        return $qb
            ->select('smov')
            ->orderBy('smov.id', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function granTotalSlipsOcupados($fechaBuscar)
    {
        $qbbe = $this->createQueryBuilder('sm');
        $slipsOcupados = $qbbe
            ->select('sm', 'slip', 'marinahumedacotizacion', 'barco', 'cliente')
            ->join('sm.slip', 'slip')
            ->join('sm.marinahumedacotizacion', 'marinahumedacotizacion')
            ->join('marinahumedacotizacion.barco', 'barco')
            ->join('marinahumedacotizacion.cliente', 'cliente')
            ->where(':fecha_buscar BETWEEN sm.fechaLlegada AND sm.fechaSalida')
            ->getQuery()
            ->setParameter('fecha_buscar', $fechaBuscar->format('Y-m-d'))
            ->getArrayResult();
        return $slipsOcupados;
    }

    public function pintaSlipsMapa($slipsOcupados)
    {
        $dibujoSlip = [];
        $slipInicial = 1;
        $slipFinal = 176;
        for ($i = $slipInicial; $i <= $slipFinal; $i++) {
            $dibujoSlip[$i] = ['color' => 'rgb(29, 105, 19)', 'embarcacion' => '', 'cliente' => '', 'eslora' => '', 'fechaLlegada' => '', 'fechaSalida' => '', 'idcotizacion' => 0];
        }
        for ($i = $slipInicial; $i <= $slipFinal; $i++) {
            foreach ($slipsOcupados as $slip) {
                if ($i == $slip['slip']['id']) {
                    $dibujoSlip[$i]['color'] = '#FF6600';
                    $dibujoSlip[$i]['embarcacion'] = $slip['marinahumedacotizacion']['barco']['nombre'];
                    $dibujoSlip[$i]['cliente'] = $slip['marinahumedacotizacion']['cliente']['nombre'];
                    $dibujoSlip[$i]['eslora'] = $slip['marinahumedacotizacion']['barco']['eslora'];
                    $dibujoSlip[$i]['fechaLlegada'] = $slip['fechaLlegada'];
                    $dibujoSlip[$i]['fechaSalida'] = $slip['fechaSalida'];
                    $dibujoSlip[$i]['idcotizacion'] = $slip['marinahumedacotizacion']['id'];
                }
            }
        }
        return $dibujoSlip;
    }

    public function calculoOcupaciones($fechaBuscar)
    {
        $ocupacionTiposSlips = [];
        $tipoSlip = 46;
        $total = 109;
        $numTiposSlip = $this->ocupacionSlip($fechaBuscar, $tipoSlip);
        $porcentaje = ($numTiposSlip * 100) / $total;
        array_push($ocupacionTiposSlips, ['tipo' => $tipoSlip, 'num' => $numTiposSlip, 'total' => $total, 'porcentaje' => $porcentaje]);
        $tipoSlip = 61;
        $total = 46;
        $numTiposSlip = $this->ocupacionSlip($fechaBuscar, $tipoSlip);
        $porcentaje = ($numTiposSlip * 100) / $total;
        array_push($ocupacionTiposSlips, ['tipo' => $tipoSlip, 'num' => $numTiposSlip, 'total' => $total, 'porcentaje' => $porcentaje]);
        $tipoSlip = 72;
        $total = 13;
        $numTiposSlip = $this->ocupacionSlip($fechaBuscar, $tipoSlip);
        $porcentaje = ($numTiposSlip * 100) / $total;
        array_push($ocupacionTiposSlips, ['tipo' => $tipoSlip, 'num' => $numTiposSlip, 'total' => $total, 'porcentaje' => $porcentaje]);
        $tipoSlip = 120;
        $total = 8;
        $numTiposSlip = $this->ocupacionSlip($fechaBuscar, $tipoSlip);
        $porcentaje = ($numTiposSlip * 100) / $total;
        array_push($ocupacionTiposSlips, ['tipo' => $tipoSlip, 'num' => $numTiposSlip, 'total' => $total, 'porcentaje' => $porcentaje]);
        return $ocupacionTiposSlips;
    }
    // FIXME
    public function calculoOcupaciones2($fechaBuscar)
    {
        $ocupacionTiposSlips = [];
        $tipoSlip = 46;
        $total = 109;
        $numTiposSlip = $this->ocupacionSlip($fechaBuscar, $tipoSlip);
        $porcentaje = ($numTiposSlip * 100) / $total;
        array_push($ocupacionTiposSlips, ['pies' => $tipoSlip, 'amarres' => $numTiposSlip, 'ocupacion' => $total, 'porcentaje' => $porcentaje]);
        $tipoSlip = 61;
        $total = 46;
        $numTiposSlip = $this->ocupacionSlip($fechaBuscar, $tipoSlip);
        $porcentaje = ($numTiposSlip * 100) / $total;
        array_push($ocupacionTiposSlips, ['pies' => $tipoSlip, 'amarres' => $numTiposSlip, 'ocupacion' => $total, 'porcentaje' => $porcentaje]);
        $tipoSlip = 72;
        $total = 13;
        $numTiposSlip = $this->ocupacionSlip($fechaBuscar, $tipoSlip);
        $porcentaje = ($numTiposSlip * 100) / $total;
        array_push($ocupacionTiposSlips, ['pies' => $tipoSlip, 'amarres' => $numTiposSlip, 'ocupacion' => $total, 'porcentaje' => $porcentaje]);
        $tipoSlip = 120;
        $total = 8;
        $numTiposSlip = $this->ocupacionSlip($fechaBuscar, $tipoSlip);
        $porcentaje = ($numTiposSlip * 100) / $total;
        array_push($ocupacionTiposSlips, ['pies' => $tipoSlip, 'amarres' => $numTiposSlip, 'ocupacion' => $total, 'porcentaje' => $porcentaje]);
        return $ocupacionTiposSlips;
    }

    public function ocupacionSlip($fechaBuscar, $slipBuscado)
    {
        $qbbe = $this->createQueryBuilder('sm');
        $slipsOcupados = $qbbe
            ->select('sm', 'slip')
            ->join('sm.slip', 'slip')
            ->where(':fecha_buscar BETWEEN sm.fechaLlegada AND sm.fechaSalida')
            ->andWhere($qbbe->expr()->eq($slipBuscado, 'slip.pies'))
            ->getQuery()
            ->setParameter('fecha_buscar', $fechaBuscar->format('Y-m-d'))
            ->getArrayResult();
        $num = count($slipsOcupados);
        return $num;
    }

    public function isSlipOpen($slipId, $fechaLlegada, $fechaSalida)
    {
        $qb = $this->createQueryBuilder('sm');
        return $qb
            ->where('
                    sm.slip = :slip AND 
                    ((:fechaLlegada BETWEEN sm.fechaLlegada AND sm.fechaSalida) OR
                     (:fechaSalida BETWEEN sm.fechaLlegada AND sm.fechaSalida))
                ')
            ->setParameters([
                'slip' => $slipId,
                'fechaLlegada' => $fechaLlegada,
                'fechaSalida' => $fechaSalida,
            ])
            ->getQuery()
            ->getResult();
    }

    public function getCurrentOcupation($slip = null)
    {
        $qb = $this->createQueryBuilder('sm');

        $qb
            ->select('sm', 'slip', 'cotizacion', 'cliente', 'barco')
            ->join('sm.slip', 'slip')
            ->join('sm.marinahumedacotizacion', 'cotizacion')
            ->join('cotizacion.cliente', 'cliente')
            ->join('cotizacion.barco', 'barco')
            ->where('CURRENT_DATE() BETWEEN sm.fechaLlegada AND sm.fechaSalida');

        if (null !== $slip) {
            $qb->andWhere('slip.id = :slip')
                ->setParameter('slip', $slip);
        }

        return $qb
            ->getQuery()
            ->getResult();
    }

    public function getCurrentOcupationStats()
    {
        $qb = $this->createQueryBuilder('sm')
            ->leftJoin('sm.slip', 's');

        return $qb
            ->select('s.pies', 'COALESCE(COUNT(sm.id), 0) AS ocupados')
            ->where('CURRENT_DATE() BETWEEN sm.fechaLlegada AND sm.fechaSalida')
            ->groupBy('s.pies')
            ->getQuery()
            ->getResult();

    }
}
