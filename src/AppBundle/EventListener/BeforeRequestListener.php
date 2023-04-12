<?php

namespace AppBundle\EventListener;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

class BeforeRequestListener
{
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        # Enable filter from config/config.yml
        $filter =  $this->em->getFilters()
            ->enable('fortune_cookies_discontinued');
        $filter->setParameter('discontinued', false);
    }
}