<?php

namespace AppBundle\Controller\Astillero;

use AppBundle\Entity\Astillero\GrupoProducto;
use AppBundle\Entity\Astillero\Servicio;
use AppBundle\Repository\Astillero\GrupoProductoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpKernel\Exception\HttpException;
/**
 * Servicio controller.
 *
 * @Route("astillero/servicio")
 */
class ServicioController extends Controller
{
    /**
     * Lists all servicio entities.
     *
     * @Route("/", name="astillero_servicio_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            try {
                $datatables = $this->get('datatables');
                $results = $datatables->handle($request, 'AstilleroServicio');
                return $this->json($results);
            } catch (HttpException $e) {
                return $this->json($e->getMessage(), $e->getStatusCode());
            }
        }
        return $this->render('astillero/servicio/index.html.twig', array(
            'title' => 'Astillero Servicios'
        ));
    }

    /**
     * Creates a new servicio entity.
     *
     * @Route("/nuevo", name="astillero_servicio_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $servicio = new Servicio();
        $form = $this->createForm('AppBundle\Form\Astillero\ServicioType', $servicio);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($servicio);
            $em->flush();
            return $this->redirectToRoute('astillero_servicio_show',['id'=>$servicio->getId()]);
        }
        return $this->render('astillero/servicio/new.html.twig', array(
            'servicio' => $servicio,
            'form' => $form->createView(),
            'title' => 'Astillero Nuevo Servicio'
        ));
    }

    /**
     * @Route("/buscarservicio/{id}.{_format}", name="ajax_astillero_busca_servicio", defaults={"_format"="json"})
     *
     */
    public function buscarAction(Request $request, $id)
    {
        $grupoProductoRepository = $this->getDoctrine()->getRepository(GrupoProducto::class);
        return new JsonResponse(
          [
              'gruposProductos' => $grupoProductoRepository->getGrupoFromServicio($id)
          ],
          JsonResponse::HTTP_OK
        );
    }

    /**
     * Finds and displays a servicio entity.
     *
     * @Route("/{id}", name="astillero_servicio_show")
     * @Method("GET")
     */
    public function showAction(Servicio $servicio)
    {
        return $this->render('astillero/servicio/show.html.twig', [
            'servicio' => $servicio,
            'title' => 'Astillero Detalle Servicio'
        ]);
    }

    /**
     * Displays a form to edit an existing servicio entity.
     *
     * @Route("/{id}/editar", name="astillero_servicio_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Servicio $servicio)
    {
        $em = $this->getDoctrine()->getManager();
        $grupoProductoOriginal = new ArrayCollection();
        foreach ($servicio->getGruposProductos() as $grupo){
            $grupoProductoOriginal->add($grupo);
        }
        $deleteForm = $this->createDeleteForm($servicio);
        $editForm = $this->createForm('AppBundle\Form\Astillero\ServicioType', $servicio);
        $editForm->handleRequest($request);
        if ($editForm->isSubmitted() && $editForm->isValid()) {
            foreach ($grupoProductoOriginal as $grupo){
                if(false === $servicio->getGruposProductos()->contains($grupo)){
                    $servicio->removeGruposProducto($grupo);
                    $em->persist($grupo);
                    $em->remove($grupo);
                }
            }
            $em->persist($servicio);
            $em->flush();
            return $this->redirectToRoute('astillero_servicio_show',['id' => $servicio->getId()]);
        }
        return $this->render('astillero/servicio/edit.html.twig', array(
            'servicio' => $servicio,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'title' => 'Astillero Editar Servicio'
        ));
    }

    /**
     * Deletes a servicio entity.
     *
     * @Route("/{id}", name="astillero_servicio_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Servicio $servicio)
    {
        $form = $this->createDeleteForm($servicio);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $em = $this->getDoctrine()->getManager();
                $em->remove($servicio);
                $em->flush();
                return $this->redirectToRoute('astillero_servicio_index');
            }catch (ForeignKeyConstraintViolationException $e){
                $this->addFlash('error','Error!, No se puede borrar este servicio, esta siendo utilizado en las cotizaciones');
            }
        }

        return $this->redirectToRoute('astillero_servicio_edit',['id'=>$servicio->getId()]);
    }

    /**
     * Creates a form to delete a servicio entity.
     *
     * @param Servicio $servicio The servicio entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Servicio $servicio)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('astillero_servicio_delete', array('id' => $servicio->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
