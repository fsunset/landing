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

        return $this->render('default/index.html.twig', array(
            'featuredItems' => $featuredItems,
            'sections' => $sections
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

        $drinks = $em->getRepository('AppBundle:Drink')->findby(array('isActive' => 1), array('name' => 'ASC'));

        $itemDrinks = null;
        foreach ($drinks as $drink) {
            if (is_int(array_search($id, $drink->getItems()))) {
                $itemDrinks .= '<option value="' . $drink->getId() . '">' . ucwords($drink->getName()) . '</option>';
            }
        }

        $accompaniments = $em->getRepository('AppBundle:Accompaniment')->findby(array('isActive' => 1), array('name' => 'ASC'));

        $itemAccompaniments = null;
        foreach ($accompaniments as $accompaniment) {
            if (is_int(array_search($id, $accompaniment->getItems()))) {
                $itemAccompaniments .= '<option value="' . $accompaniment->getId() . '">' . ucwords($accompaniment->getName()) . '</option>';
            }
        }

        return new JsonResponse(
            array(
                'description' => $item->getDescription(),
                'unitaryPrice' => $item->getUnitaryPrice(),
                'comboPrice' => $item->getComboPrice(),
                'itemDrinks' => $itemDrinks,
                'itemAccompaniments' => $itemAccompaniments,
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
