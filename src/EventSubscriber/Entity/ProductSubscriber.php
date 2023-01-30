<?php

namespace App\EventSubscriber\Entity;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\EventSubscriber\EventSubscriberInterface;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\String\Slugger\SluggerInterface;

class ProductSubscriber implements EventSubscriberInterface
{
    public function __construct(private SluggerInterface $slugger)
    {}

    public function prePersist (LifecycleEventArgs $event): void
    {
        if ($event->getObject() instanceof Product) {
            $this->handleSlug($event);

        }
    }

    public function preUpdate(LifecycleEventArgs $event): void
    {
        if ($event->getObject() instanceof Product) {
            $this->handleSlug($event);
        }
    }

    public function getSubscribedEvents(): array
    {
        return [
            Events::prePersist,
            Events::preUpdate,
        ];
    }

    public function handleSlug($event)
    {
        $product = $event->getObject();
        $product->setSlug($this->slugger->slug($product->getName())->lower());
    }

}
