<?php
namespace Ttree\Identicons\Factory;

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
use Imagine\Image\ImageInterface;
use Ttree\Identicons\Domain\Model\IdenticonConfiguration;
use Ttree\Identicons\Generator\GeneratorInterface;
use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Cache\Frontend\VariableFrontend;

/**
 * Identicon Factory
 *
 * @Flow\Scope("singleton")
 */
class IdenticonFactory
{
    /**
     * @var GeneratorInterface
     * @Flow\Inject
     */
    protected $identiconGenerator;

    /**
     * @var boolean
     * @Flow\InjectConfiguration(path="persist")
     */
    protected $persistenceEnabled;

    /**
     * @var VariableFrontend
     * @Flow\Inject
     */
    protected $cache;

    /**
     * @param IdenticonConfiguration $hash
     * @return string
     */
    public function create(IdenticonConfiguration $hash)
    {
        if ($this->cache->has((string)$hash) && $this->persistenceEnabled) {
            return $this->cache->get((string)$hash);
        }
        $identicon = $this->identiconGenerator->generate($hash);
        $this->emitIdenticonCreated($identicon, $hash);

        $image = $identicon->get('png', [
            'png_compression_level' => 6,
            'png_compression_filter' => 5,
            'flatten' => true,
            'filter' => PNG_ALL_FILTERS
        ]);
        if ($this->persistenceEnabled) {
            $this->cache->set((string)$hash, $image);
        }
        return $image;
    }

    /**
     * Emits a signal when an Identicon is created
     *
     * @param ImageInterface $identicon
     * @param IdenticonConfiguration $hash
     * @return void
     * @Flow\Signal
     */
    protected function emitIdenticonCreated(ImageInterface $identicon, IdenticonConfiguration $hash) {

    }
}
