<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Addition;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\Query;


/**
 * Addition controller.
 *
 * @Route("admin/addition")
 */
class AdditionController extends Controller
{
    /**
     * Lists all addition entities.
     *
     * @Route("/", name="addition_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $additions = $em->getRepository('AppBundle:Addition')->findAll();

        return $this->render('addition/index.html.twig', array(
            'additions' => $additions,
        ));
    }

    /**
     * Creates a new addition entity.
     *
     * @Route("/new", name="addition_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $query = $this->getDoctrine()
            ->getRepository('AppBundle:Item')
            ->createQueryBuilder('i')
            ->where('i.isActive = true')
            // ->andWhere('i.isFeatured = false')
            ->orderBy('i.name', 'ASC')
            ->getQuery();
        $result = $query->getResult(Query::HYDRATE_ARRAY);

        $items = array();
        foreach ($result as $key => $item) {
            $items[trim(strtoupper($item['name']))] = $item['id'];
        }

        $addition = new Addition();
        $form = $this->createForm('AppBundle\Form\AdditionType', $addition, array('items' => $items));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($addition);
            $em->flush();

            return $this->redirectToRoute('addition_show', array('id' => $addition->getId()));
        }

        return $this->render('addition/new.html.twig', array(
            'addition' => $addition,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a addition entity.
     *
     * @Route("/{id}", name="addition_show")
     * @Method("GET")
     */
    public function showAction(Addition $addition)
    {
        $deleteForm = $this->createDeleteForm($addition);

        return $this->render('addition/show.html.twig', array(
            'addition' => $addition,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing addition entity.
     *
     * @Route("/{id}/edit", name="addition_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Addition $addition)
    {
        $query = $this->getDoctrine()
            ->getRepository('AppBundle:Item')
            ->createQueryBuilder('i')
            ->where('i.isActive = true')
            // ->andWhere('i.isFeatured = false')
            ->orderBy('i.name', 'ASC')
            ->getQuery();
        $result = $query->getResult(Query::HYDRATE_ARRAY);

        $items = array();
        foreach ($result as $key => $item) {
            $items[trim(strtoupper($item['name']))] = $item['id'];
        }

        $deleteForm = $this->createDeleteForm($addition);
        $editForm = $this->createForm('AppBundle\Form\AdditionType', $addition, array('items' => $items));
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('addition_show', array('id' => $addition->getId()));
        }

        return $this->render('addition/edit.html.twig', array(
            'addition' => $addition,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a addition entity.
     *
     * @Route("/{id}", name="addition_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Addition $addition)
    {
        $form = $this->createDeleteForm($addition);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($addition);
            $em->flush();
        }

        return $this->redirectToRoute('addition_index');
    }

    /**
     * Creates a form to delete a addition entity.
     *
     * @param Addition $addition The addition entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Addition $addition)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('addition_delete', array('id' => $addition->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
