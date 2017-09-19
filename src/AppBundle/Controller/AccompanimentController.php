<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Accompaniment;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\Query;


/**
 * Accompaniment controller.
 *
 * @Route("admin/accompaniment")
 */
class AccompanimentController extends Controller
{
    /**
     * Lists all accompaniment entities.
     *
     * @Route("/", name="accompaniment_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $accompaniments = $em->getRepository('AppBundle:Accompaniment')->findAll();

        return $this->render('accompaniment/index.html.twig', array(
            'accompaniments' => $accompaniments,
        ));
    }

    /**
     * Creates a new accompaniment entity.
     *
     * @Route("/new", name="accompaniment_new")
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

        $accompaniment = new Accompaniment();
        $form = $this->createForm('AppBundle\Form\AccompanimentType', $accompaniment, array('items' => $items));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($accompaniment);
            $em->flush();

            return $this->redirectToRoute('accompaniment_show', array('id' => $accompaniment->getId()));
        }

        return $this->render('accompaniment/new.html.twig', array(
            'accompaniment' => $accompaniment,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a accompaniment entity.
     *
     * @Route("/{id}", name="accompaniment_show")
     * @Method("GET")
     */
    public function showAction(Accompaniment $accompaniment)
    {
        $deleteForm = $this->createDeleteForm($accompaniment);

        return $this->render('accompaniment/show.html.twig', array(
            'accompaniment' => $accompaniment,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing accompaniment entity.
     *
     * @Route("/{id}/edit", name="accompaniment_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Accompaniment $accompaniment)
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

        $deleteForm = $this->createDeleteForm($accompaniment);
        $editForm = $this->createForm('AppBundle\Form\AccompanimentType', $accompaniment, array('items' => $items));
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('accompaniment_show', array('id' => $accompaniment->getId()));
        }

        return $this->render('accompaniment/edit.html.twig', array(
            'accompaniment' => $accompaniment,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a accompaniment entity.
     *
     * @Route("/{id}", name="accompaniment_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Accompaniment $accompaniment)
    {
        $form = $this->createDeleteForm($accompaniment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($accompaniment);
            $em->flush();
        }

        return $this->redirectToRoute('accompaniment_index');
    }

    /**
     * Creates a form to delete a accompaniment entity.
     *
     * @param Accompaniment $accompaniment The accompaniment entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Accompaniment $accompaniment)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('accompaniment_delete', array('id' => $accompaniment->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
