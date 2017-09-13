<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Legal;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Legal controller.
 *
 * @Route("admin/legal")
 */
class LegalController extends Controller
{
    /**
     * Lists all legal entities.
     *
     * @Route("/", name="legal_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $legals = $em->getRepository('AppBundle:Legal')->findAll();

        return $this->render('legal/index.html.twig', array(
            'legals' => $legals,
        ));
    }

    /**
     * Creates a new legal entity.
     *
     * @Route("/new", name="legal_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $legal = new Legal();
        $form = $this->createForm('AppBundle\Form\LegalType', $legal);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($legal);
            $em->flush();

            return $this->redirectToRoute('legal_show', array('id' => $legal->getId()));
        }

        return $this->render('legal/new.html.twig', array(
            'legal' => $legal,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a legal entity.
     *
     * @Route("/{id}", name="legal_show")
     * @Method("GET")
     */
    public function showAction(Legal $legal)
    {
        $deleteForm = $this->createDeleteForm($legal);

        return $this->render('legal/show.html.twig', array(
            'legal' => $legal,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing legal entity.
     *
     * @Route("/{id}/edit", name="legal_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Legal $legal)
    {
        $deleteForm = $this->createDeleteForm($legal);
        $editForm = $this->createForm('AppBundle\Form\LegalType', $legal);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('legal_edit', array('id' => $legal->getId()));
        }

        return $this->render('legal/edit.html.twig', array(
            'legal' => $legal,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a legal entity.
     *
     * @Route("/{id}", name="legal_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Legal $legal)
    {
        $form = $this->createDeleteForm($legal);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($legal);
            $em->flush();
        }

        return $this->redirectToRoute('legal_index');
    }

    /**
     * Creates a form to delete a legal entity.
     *
     * @param Legal $legal The legal entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Legal $legal)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('legal_delete', array('id' => $legal->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
