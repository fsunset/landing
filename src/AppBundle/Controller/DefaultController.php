<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Section;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $featuredItems = $em->getRepository('AppBundle:Item')->findBy(array('isFeatured' => true));
        $sections = $em->getRepository('AppBundle:Section')->findAll(array(), array('order' => 'ASC'));
        $complements = $em->getRepository('AppBundle:Item')->findBy(array('section' => 11, 'isActive' => 1), array('name' => 'ASC'));
        $drinks = $em->getRepository('AppBundle:Item')->findBy(array('section' => 12, 'isActive' => 1), array('name' => 'ASC'));
        $additions = $em->getRepository('AppBundle:Item')->findBy(array('section' => 13, 'isActive' => 1), array('name' => 'ASC'));

        return $this->render('default/index.html.twig', array(
            'featuredItems' => $featuredItems,
            'sections' => $sections,
            'complements' => $complements,
            'drinks' => $drinks,
            'additions' => $additions
        ));
    }

    /**
     * @Route("/producto", name="itemInfo")
     */
    public function itemInfoAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $id = $request->request->get('id');

        $item = $em->getRepository('AppBundle:Item')->findOneBy(array('id' => $id));

        return new JsonResponse(
            array(
                'description' => $item->getDescription(),
                'unitaryPrice' => $item->getUnitaryPrice(),
                'comboPrice' => $item->getComboPrice()
            )
        );
    }


    /**
     * @Route("/puntos-de-venta", name="puntos_venta")
     */
    public function puntosVentaAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $branches = $em->getRepository('AppBundle:Branch')->findAll();

        return $this->render('default/puntosVenta.html.twig', array(
            'branches' => $branches
        ));
    }

    /**
     * @Route("/eventos", name="eventos")
     */
    public function eventosAction(Request $request)
    {
        return $this->render('default/eventos.html.twig');
    }

    /**
     * @Route("/contacto", name="contacto")
     */
    public function contactoAction(Request $request)
    {
        return $this->render('default/contacto.html.twig');
    }
}
