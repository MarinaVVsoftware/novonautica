<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Barco;
use AppBundle\Entity\Cliente;
use AppBundle\Entity\Motor;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Cliente controller.
 *
 * @Route("cliente")
 */
class ClienteController extends Controller
{
    /**
     * Lists all cliente entities.
     *
     * @Route("/", name="cliente_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $clientes = $em->getRepository('AppBundle:Cliente')->findAll();

        return $this->render('cliente/index.html.twig', array(
            'clientes' => $clientes,
            'clientelistado' => 1
        ));
    }

    /**
     * Creates a new cliente entity.
     *
     * @Route("/nuevo", name="cliente_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $cliente = new Cliente();
        $barco = new Barco();
        $motor = new Motor();
        $cliente->addBarco($barco);
        $barco->addMotore($motor);
        $form = $this->createForm('AppBundle\Form\ClienteType', $cliente);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($cliente);
            $em->flush();

            return $this->redirectToRoute('cliente_show', array('id' => $cliente->getId()));

        }

        return $this->render('cliente/new.html.twig', array(
            'cliente' => $cliente,
            'form' => $form->createView(),
            'clienteagregar' => 1
        ));
    }

    /**
     * Finds and displays a cliente entity.
     *
     * @Route("/{id}", name="cliente_show")
     * @Method("GET")
     */
    public function showAction(Cliente $cliente)
    {
        $deleteForm = $this->createDeleteForm($cliente);
        //$barcos = $cliente->getBarcos();
        dump($cliente);
        return $this->render('cliente/show.html.twig', array(
            'cliente' => $cliente,
            'delete_form' => $deleteForm->createView(),
            'clientelistado' => 1,
        ));
    }

    /**
     * Displays a form to edit an existing cliente entity.
     *
     * @Route("/{id}/editar", name="cliente_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Cliente $cliente)
    {
        $deleteForm = $this->createDeleteForm($cliente);
        $editForm = $this->createForm('AppBundle\Form\ClienteType', $cliente);
        $editForm->handleRequest($request);
        $barcos = $cliente->getBarcos();
        dump($cliente);
        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('cliente_edit', array('id' => $cliente->getId()));
        }

        return $this->render('cliente/edit.html.twig', array(
            'cliente' => $cliente,
            'barcos' => $barcos,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'clientelistado' => 1,
        ));
    }

    /**
     * Deletes a cliente entity.
     *
     * @Route("/{id}", name="cliente_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Cliente $cliente)
    {
        $form = $this->createDeleteForm($cliente);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($cliente);
            $em->flush();
        }

        return $this->redirectToRoute('cliente_index');
    }

    /**
     * Creates a form to delete a cliente entity.
     *
     * @param Cliente $cliente The cliente entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Cliente $cliente)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('cliente_delete', array('id' => $cliente->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
