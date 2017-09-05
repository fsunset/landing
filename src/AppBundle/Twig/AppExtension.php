<?php

namespace AppBundle\Twig;

use Symfony\Bridge\Doctrine\RegistryInterface;

class AppExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('menuItem', array($this, 'menuItemFilter')),
            new \Twig_SimpleFilter('price', array($this, 'priceFilter')),
            new \Twig_SimpleFilter('getItemById', array($this, 'getItemByIdFilter')),
        );
    }

    protected $doctrine;
    public function __construct(RegistryInterface $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function menuItemFilter($sectionId, $counter = false)
    {
        $em = $this->doctrine->getManager();
        $items = $em->getRepository('AppBundle:Item')->findBy(array('section' => $sectionId, 'isFeatured' => false));

        if (!$counter) {
            return $items;
        }
        return count($items);
    }

    public function getItemByIdFilter($id)
    {
        $em = $this->doctrine->getManager();
        $item = $em->getRepository('AppBundle:Item')->findOneById($id);

        return $item->getName();
    }

    public function priceFilter($number, $decimals = 0, $decPoint = ',', $thousandsSep = '.')
    {
        $price = number_format($number, $decimals, $decPoint, $thousandsSep);
        $price = '$'.$price;

        return $price;
    }
}
