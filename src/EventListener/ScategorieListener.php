<?php

namespace App\EventListener;

use App\Entity\Scategorie;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\EntityManagerInterface;
use RuntimeException;

class ScategorieListener
{
    public function prePersist(Scategorie $scategorie, LifecycleEventArgs $event): void
    {
        $entityManager = $event->getObjectManager();

        // `EntityManager` olup olmadığını kontrol edelim
        if (!$entityManager instanceof EntityManagerInterface) {
            throw new RuntimeException('Entity Manager bulunamadı.');
        }

        // Doublon kontrolü için sorguyu burada yaz
        $count = $entityManager->createQueryBuilder()
            ->select('COUNT(s.id)')
            ->from(Scategorie::class, 's')
            ->where('s.numero = :numero')
            ->andWhere('s.categorie = :categorie')
            ->setParameter('numero', $scategorie->getNumero())
            ->setParameter('categorie', $scategorie->getCategorie())
            ->getQuery()
            ->getSingleScalarResult();

        if ($count > 0) {
            throw new RuntimeException('Le numéro est déjà utilisé.');
        }
    }
}
