<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Section;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

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

        return $this->render('default/index.html.twig', array(
            'featuredItems' => $featuredItems,
            'sections' => $sections
        ));
    }

    /**
     * @Route("/puntos-de-venta", name="puntos_venta")
     */
    public function puntosVentaAction(Request $request)
    {
        return $this->render('default/puntosVenta.html.twig');
    }

    /**
     * @Route("/eventos", name="eventos")
     */
    public function eventosAction(Request $request)
    {
        return $this->render('default/eventos.html.twig');
    }
}
