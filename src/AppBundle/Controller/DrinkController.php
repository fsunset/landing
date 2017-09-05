<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Drink;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\Query;

/**
 * Drink controller.
 *
 * @Route("drink")
 */
class DrinkController extends Controller
{
    /**
     * Lists all drink entities.
     *
     * @Route("/", name="drink_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $drinks = $em->getRepository('AppBundle:Drink')->findAll();

        return $this->render('drink/index.html.twig', array(
            'drinks' => $drinks,
        ));
    }

    /**
     * Creates a new drink entity.
     *
     * @Route("/new", name="drink_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $query = $this->getDoctrine()
            ->getRepository('AppBundle:Item')
            ->createQueryBuilder('i')
            ->where('i.isActive = true')
            ->andWhere('i.isFeatured = false')
            ->orderBy('i.name', 'ASC')
            ->getQuery();
        $result = $query->getResult(Query::HYDRATE_ARRAY);

        $items = array();
        foreach ($result as $key => $item) {
            $items[trim(strtoupper($item['name']))] = $item['id'];
        }

        $drink = new Drink();
        $form = $this->createForm('AppBundle\Form\DrinkType', $drink, array('items' => $items));
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($drink);
            $em->flush();

            return $this->redirectToRoute('drink_show', array('id' => $drink->getId()));
        }

        return $this->render('drink/new.html.twig', array(
            'drink' => $drink,
            'form' => $form->createView()
        ));
    }

    /**
     * Finds and displays a drink entity.
     *
     * @Route("/{id}", name="drink_show")
     * @Method("GET")
     */
    public function showAction(Drink $drink)
    {
        $deleteForm = $this->createDeleteForm($drink);

        return $this->render('drink/show.html.twig', array(
            'drink' => $drink,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing drink entity.
     *
     * @Route("/{id}/edit", name="drink_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Drink $drink)
    {
        $query = $this->getDoctrine()
            ->getRepository('AppBundle:Item')
            ->createQueryBuilder('i')
            ->where('i.isActive = true')
            ->andWhere('i.isFeatured = false')
            ->orderBy('i.name', 'ASC')
            ->getQuery();
        $result = $query->getResult(Query::HYDRATE_ARRAY);

        $items = array();
        foreach ($result as $key => $item) {
            $items[trim(strtoupper($item['name']))] = $item['id'];
        }

        $deleteForm = $this->createDeleteForm($drink);
        $editForm = $this->createForm('AppBundle\Form\DrinkType', $drink, array('items' => $items));
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('drink_show', array('id' => $drink->getId()));
        }

        return $this->render('drink/edit.html.twig', array(
            'drink' => $drink,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a drink entity.
     *
     * @Route("/{id}", name="drink_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Drink $drink)
    {
        $form = $this->createDeleteForm($drink);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($drink);
            $em->flush();
        }

        return $this->redirectToRoute('drink_index');
    }

    /**
     * Creates a form to delete a drink entity.
     *
     * @param Drink $drink The drink entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Drink $drink)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('drink_delete', array('id' => $drink->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
