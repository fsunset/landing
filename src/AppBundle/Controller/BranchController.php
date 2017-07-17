<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Branch;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Branch controller.
 *
 * @Route("branch")
 */
class BranchController extends Controller
{
    /**
     * Lists all branch entities.
     *
     * @Route("/", name="branch_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $branches = $em->getRepository('AppBundle:Branch')->findAll();

        return $this->render('branch/index.html.twig', array(
            'branches' => $branches,
        ));
    }

    /**
     * Creates a new branch entity.
     *
     * @Route("/new", name="branch_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $branch = new Branch();
        $form = $this->createForm('AppBundle\Form\BranchType', $branch);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($branch);
            $em->flush();

            return $this->redirectToRoute('branch_show', array('id' => $branch->getId()));
        }

        return $this->render('branch/new.html.twig', array(
            'branch' => $branch,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a branch entity.
     *
     * @Route("/{id}", name="branch_show")
     * @Method("GET")
     */
    public function showAction(Branch $branch)
    {
        $deleteForm = $this->createDeleteForm($branch);

        return $this->render('branch/show.html.twig', array(
            'branch' => $branch,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing branch entity.
     *
     * @Route("/{id}/edit", name="branch_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Branch $branch)
    {
        $deleteForm = $this->createDeleteForm($branch);
        $editForm = $this->createForm('AppBundle\Form\BranchType', $branch);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('branch_edit', array('id' => $branch->getId()));
        }

        return $this->render('branch/edit.html.twig', array(
            'branch' => $branch,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a branch entity.
     *
     * @Route("/{id}", name="branch_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Branch $branch)
    {
        $form = $this->createDeleteForm($branch);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($branch);
            $em->flush();
        }

        return $this->redirectToRoute('branch_index');
    }

    /**
     * Creates a form to delete a branch entity.
     *
     * @param Branch $branch The branch entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Branch $branch)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('branch_delete', array('id' => $branch->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
