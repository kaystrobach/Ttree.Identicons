<?php
namespace Ttree\Identicons\Domain\Repository;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "Ttree.Identicons".      *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU General Public License, either version 3 of the   *
 * License, or (at your option) any later version.                        *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

use Doctrine\ORM\Mapping as ORM;
use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Persistence\Repository;

/**
 * Identicon Repository
 *
 * @Flow\Scope("singleton")
 */
class IdenticonRepository extends Repository
{
    /**
     * {@inheritdoc}
     */
    public function findByIdentifier($identifier)
    {
        return $this->persistenceManager->getObjectByIdentifier((string)$identifier, $this->entityClassName);
    }
}
