<?php

namespace AppBundle\Entity\Tienda;

use AppBundle\Entity\Contabilidad\Facturacion\Concepto\ClaveProdServ;
use AppBundle\Entity\Contabilidad\Facturacion\Concepto\ClaveUnidad;
use AppBundle\Entity\Tienda\Producto\Categoria;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Entity\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;


/**
 * Producto
 *
 * @ORM\Table(name="tienda_producto")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Tienda\ProductoRepository")
 * @Vich\Uploadable
 */
class Producto implements \JsonSerializable
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @Groups({"facturacion"})
     *
     * @ORM\Column(name="nombre", type="string", length=100)
     */
    private $nombre;

    /**
     * @var integer
     *
     * @Groups({"facturacion"})
     *
     * @ORM\Column(name="precio", type="bigint")
     */
    private $precio;

    /**
     * @var integer
     *
     * @Groups({"facturacion"})
     *
     * @ORM\Column(name="preciocolaborador", type="bigint")
     */
    private $preciocolaborador;

    /**
     * @var string
     *
     * @ORM\Column(name="codigo_barras", type="string", length=30)
     */
    private $codigoBarras;

    /**
     * @var int
     *
     * @ORM\Column(name="iesps", type="integer")
     */
    private $IESPS;

    /**
     * @var int
     *
     * @ORM\Column(name="iva", type="integer")
     */
    private $IVA;

    /**
     * @var File
     *
     * @Assert\Image
     *
     * @Vich\UploadableField(
     *     mapping="tienda_producto_imagen",
     *     fileNameProperty="imagen"
     * )
     */
    private $imagenFile;

    /**
     * @var string
     *
     * @ORM\Column(name="imagen", type="string", nullable=true)
     */
    private $imagen;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_active", type="boolean")
     */
    private $isActive;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="update_at", type="datetime")
     */
    private $updateAt;

    /**
     * @var float
     *
     * @ORM\Column(name="existencia", type="float", nullable=true)
     */
    private $existencia;

    /**
     * @var ClaveProdServ
     *
     * @Groups({"facturacion"})
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Contabilidad\Facturacion\Concepto\ClaveProdServ")
     */
    private $claveProdServ;

    /**
     * @var ClaveUnidad
     *
     * @Groups({"facturacion"})
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Contabilidad\Facturacion\Concepto\ClaveUnidad")
     */
    private $claveUnidad;

    /**
     * @var Categoria
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Tienda\Producto\Categoria")
     */
    private $categoria;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Tienda\Peticion", mappedBy="producto")
     */
    private $nombreproducto;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->isActive = true;
        $this->updateAt = new \DateTime();
        $this->nombreproducto = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->nombre;
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return Producto
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set precio
     *
     * @param integer $precio
     *
     * @return Producto
     */
    public function setPrecio($precio)
    {
        $this->precio = $precio;

        return $this;
    }

    /**
     * Get precio
     *
     * @return integer
     */
    public function getPrecio()
    {
        return $this->precio;
    }

    /**
     * Set preciocolaborador
     *
     * @param integer $preciocolaborador
     *
     * @return Producto
     */
    public function setPreciocolaborador($preciocolaborador)
    {
        $this->preciocolaborador = $preciocolaborador;

        return $this;
    }

    /**
     * Get preciocolaborador
     *
     * @return integer
     */
    public function getPreciocolaborador()
    {
        return $this->preciocolaborador;
    }

    /**
     * @return string
     */
    public function getCodigoBarras()
    {
        return $this->codigoBarras;
    }

    /**
     * @param string $codigoBarras
     */
    public function setCodigoBarras($codigoBarras)
    {
        $this->codigoBarras = $codigoBarras;
    }

    /**
     * @return int
     */
    public function getIESPS()
    {
        return $this->IESPS;
    }

    /**
     * @param int $IESPS
     */
    public function setIESPS($IESPS)
    {
        $this->IESPS = $IESPS;
    }

    /**
     * @return int
     */
    public function getIVA()
    {
        return $this->IVA;
    }

    /**
     * @param int $IVA
     */
    public function setIVA($IVA)
    {
        $this->IVA = $IVA;
    }

    public function setImagenFile($image = null)
    {
        $this->imagenFile = $image;

        if (null !== $image) {
            $this->updateAt = new \DateTime();
        }
    }

    public function getImagenFile()
    {
        return $this->imagenFile;
    }

    public function setImagen($imagen)
    {
        $this->imagen = $imagen;
    }

    public function getImagen()
    {
        return $this->imagen;
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        return $this->isActive;
    }

    /**
     * @param bool $isActive
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
    }

    /**
     * Set claveProdServ.
     *
     * @param ClaveProdServ|null $claveProdServ
     *
     * @return Producto
     */
    public function setClaveProdServ(ClaveProdServ $claveProdServ = null)
    {
        $this->claveProdServ = $claveProdServ;

        return $this;
    }

    /**
     * Get claveProdServ.
     *
     * @return ClaveProdServ|null
     */
    public function getClaveProdServ()
    {
        return $this->claveProdServ;
    }

    /**
     * Set claveUnidad.
     *
     * @param ClaveUnidad|null $claveUnidad
     *
     * @return Producto
     */
    public function setClaveUnidad(ClaveUnidad $claveUnidad = null)
    {
        $this->claveUnidad = $claveUnidad;

        return $this;
    }

    /**
     * Get claveUnidad.
     *
     * @return ClaveUnidad|null
     */
    public function getClaveUnidad()
    {
        return $this->claveUnidad;
    }

    /**
     * @return Categoria
     */
    public function getCategoria()
    {
        return $this->categoria;
    }

    /**
     * @param Categoria $categoria
     */
    public function setCategoria(Categoria $categoria = null)
    {
        $this->categoria = $categoria;
    }

    /**
     * Get nombreproducto
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getNombreproducto()
    {
        return $this->nombreproducto;
    }

    /**
     * Add nombreproducto
     *
     * @param Peticion $nombreproducto
     *
     * @return Producto
     */
    public function addNombreproducto(Peticion $nombreproducto)
    {
        $this->nombreproducto[] = $nombreproducto;

        return $this;
    }

    /**
     * Remove nombreproducto
     *
     * @param Peticion $nombreproducto
     */
    public function removeNombreproducto(Peticion $nombreproducto)
    {
        $this->nombreproducto->removeElement($nombreproducto);
    }

    /**
     * Get isActive.
     *
     * @return bool
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * Set updateAt.
     *
     * @param \DateTime $updateAt
     *
     * @return Producto
     */
    public function setUpdateAt($updateAt)
    {
        $this->updateAt = $updateAt;

        return $this;
    }

    /**
     * Get updateAt.
     *
     * @return \DateTime
     */
    public function getUpdateAt()
    {
        return $this->updateAt;
    }

    /**
     * Set existencia.
     *
     * @param float|null $existencia
     *
     * @return Producto
     */
    public function setExistencia($existencia = null)
    {
        $this->existencia = $existencia;

        return $this;
    }

    /**
     * Get existencia.
     *
     * @return float|null
     */
    public function getExistencia()
    {
        return $this->existencia;
    }

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'nombre' => $this->nombre,
            'precio' => $this->precio,
            'precioColaborador' => $this->preciocolaborador,
            'codigoBarras' => $this->codigoBarras,
            'existencia' => $this->existencia,
        ];
    }
}
