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

        // Drinks
        $drinks = $em->getRepository('AppBundle:Drink')->findby(array('isActive' => 1), array('name' => 'ASC'));

        $itemDrinks = null;
        foreach ($drinks as $drink) {
            if (is_int(array_search($id, $drink->getItems()))) {
                $itemDrinks .= '<option value="' . $drink->getId() . '">' . ucwords($drink->getName()) . ' + ' . $drink->getUnitaryPrice() . '</option>';
            }
        }

        // Accompaniments
        $accompaniments = $em->getRepository('AppBundle:Accompaniment')->findby(array('isActive' => 1), array('name' => 'ASC'));

        $itemAccompaniments = null;
        foreach ($accompaniments as $accompaniment) {
            if (is_int(array_search($id, $accompaniment->getItems()))) {
                $itemAccompaniments .= '<option value="' . $accompaniment->getId() . '">' . ucwords($accompaniment->getName()) . ' + ' . $accompaniment->getUnitaryPrice() . '</option>';
            }
        }

        // Additions
        $additions = $em->getRepository('AppBundle:Addition')->findby(array('isActive' => 1), array('name' => 'ASC'));

        $itemAdditions = null;
        foreach ($additions as $addition) {
            if (is_int(array_search($id, $addition->getItems()))) {
                $itemAdditions .= '<span><input type="checkbox" id="additionsItem_' . $addition->getId() . '" name="additionsItem" class="additionsItem" data-text="' . trim($addition->getName()) . '" value="' . $addition->getId() . '"> ' . trim($addition->getName()) . ' <small>+ ' . $addition->getUnitaryPrice() . '</small></span>';
            }
        }

        return new JsonResponse(
            array(
                'description' => $item->getDescription(),
                'unitaryPrice' => $item->getUnitaryPrice(),
                'comboPrice' => $item->getComboPrice(),
                'itemDrinks' => $itemDrinks,
                'firstDrinkPrice' => !is_null($itemDrinks) ? $drinks[0]->getUnitaryPrice() : 0,
                'itemAccompaniments' => $itemAccompaniments,
                'firstAccompanimentPrice' => !is_null($itemAccompaniments) ? $accompaniments[0]->getUnitaryPrice() : 0,
                'itemAdditions' => $itemAdditions
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

    /**
     * @Route("/terminos-y-condiciones", name="terms")
     */
    public function terminosAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $terms = $em->getRepository('AppBundle:Legal')->findById(1);

        return $this->render('default/terminos.html.twig', array(
            'terms' => $terms
        ));
    }

    /**
     * @Route("/politica-privacidad", name="policy")
     */
    public function politicaAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $policy = $em->getRepository('AppBundle:Legal')->findById(2);

        return $this->render('default/politica.html.twig', array(
            'policy' => $policy
        ));
    }

    /**
     * @Route("/preguntas-frecuentes", name="faq")
     */
    public function faqAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $faq = $em->getRepository('AppBundle:Legal')->findById(3);

        return $this->render('default/faq.html.twig', array(
            'faq' => $faq
        ));
    }


    /**
     * @Route("/emailOrder", name="email_order")
     */
    public function emailOrderAction(Request $request)
    {
        $info = $request->request->all();
        $delRodeoEmail = 'delrodeotest@gmail.com';
        $name = $info['name'];
        $email = $info['email'];
        $address = $info['address'];
        $addressTwo = $info['addressTwo'];
        $phone = $info['phone'];
        $companyBill = 'No';
        if (isset($info['companyBill'])) {
            $companyBill = 'Si';
        }
        $messageContent = $info['messageContent'];
        $paymentMethodValue = $info['paymentMethodValue'];
        $orderInformation = $info['orderInformation'];

        $message = (new \Swift_Message('Nuevo Pedido Email'))
            ->setSubject('Hamburguesas Del Rodeo | Nuevo pedido confirmado')
            ->setFrom($email)
            ->setTo($delRodeoEmail)
            ->setBody(
                $this->renderView(
                    'Emails/order.html.twig', array(
                        'name' => $name,
                        'email' => $email,
                        'address' => $address,
                        'addressTwo' => $addressTwo,
                        'phone' => $phone,
                        'companyBill' => $companyBill,
                        'messageContent' => $messageContent,
                        'paymentMethodValue' => $paymentMethodValue,
                        'orderInformation' => $orderInformation
                        )
                ),
                'text/html'
            )
        ;

        $messageCustomer = (new \Swift_Message('Nuevo Pedido Email'))
            ->setSubject('Hamburguesas Del Rodeo | Tu pedido ha sido confirmado')
            ->setFrom($email)
            ->setTo($delRodeoEmail)
            ->setBody(
                $this->renderView(
                    'Emails/orderCustomer.html.twig', array(
                        'name' => $name,
                        'address' => $address,
                        'addressTwo' => $addressTwo,
                        'messageContent' => $messageContent,
                        'paymentMethodValue' => $paymentMethodValue,
                        'orderInformation' => $orderInformation
                        )
                ),
                'text/html'
            )
        ;

        if ($this->get('mailer')->send($message) && $this->get('mailer')->send($messageCustomer)) {
            $this->addFlash("success", "¡Tu pedido ha sido generado!", 4000);
        } else {
            $this->addFlash("error", "Tu pedido no ha podido ser generado, vuelve a intentarlo en unos momentos por favor");
        }

        return $this->redirectToRoute('homepage');
    }

    /**
     * @Route("/emailContact", name="email_contact")
     */
    public function emailAction(Request $request)
    {
        $delRodeoEmail = 'delrodeotest@gmail.com';
        $name = $request->request->get('name');
        $phone = $request->request->get('phone');
        $email = $request->request->get('email');
        $subject = $request->request->get('subject');
        $messageContent = $request->request->get('messageContent');

        $message = (new \Swift_Message('Nuevo Mensaje Email'))
            ->setSubject('Del Rodeo Web | Nuevo Mensaje - ' . $subject)
            ->setFrom($email)
            ->setTo($delRodeoEmail)
            ->setBody(
                $this->renderView(
                    'Emails/contact.html.twig', array(
                        'name' => $name,
                        'phone' => $phone,
                        'messageContent' => $messageContent,
                        'email' => $email
                        )
                ),
                'text/html'
            )
        ;

        if ($this->get('mailer')->send($message)) {
            return new JsonResponse(array('success' => true));
        } else {
            return new JsonResponse(array('success' => false));
        }
    }
}
