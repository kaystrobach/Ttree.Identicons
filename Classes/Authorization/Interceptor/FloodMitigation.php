<?php
namespace Ttree\Identicons\Authorization\Interceptor;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "Ttree.Identicons".      *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU General Public License, either version 3 of the   *
 * License, or (at your option) any later version.                        *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

use Ttree\Identicons\Service\FloodMitigationService;
use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Core\Bootstrap;
use TYPO3\Flow\Security\Authorization\InterceptorInterface;
use TYPO3\Flow\Security\Exception\AccessDeniedException;

/**
 * Flood Mitigation
 *
 * @Flow\Scope("singleton")
 */
class FloodMitigation implements InterceptorInterface
{
    /**
     * @var FloodMitigationService
     * @Flow\Inject
     */
    protected $floodMitigationService;

    /**
     * @var Bootstrap
     * @Flow\Inject
     */
    protected $bootstrap;

    /**
     * Invokes nothing, always throws an AccessDenied Exception.
     *
     * @return boolean Always returns FALSE
     * @throws AccessDeniedException
     */
    public function invoke()
    {
        /** @var \TYPO3\Flow\Http\Request $requestHandler */
        $requestHandler = $this->bootstrap->getActiveRequestHandler()->getHttpRequest();
        if (!$this->floodMitigationService->validateAccessByClientIpAddress($requestHandler->getClientIpAddress())) {
            throw new AccessDeniedException('Your IP address is currently blocked by our rate limiting system', 1376924106);
        }

        return true;
    }
}
