<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\HttpKernel\EventListener;

use Psr\Container\ContainerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\UriSigner;
use Symfony\Component\HttpKernel\Attribute\IsSignatureValid;
use Symfony\Component\HttpKernel\Event\ControllerArgumentsEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Handles the IsSignatureValid attribute.
 *
 * @author Santiago San Martin <sanmartindev@gmail.com>
 */
class IsSignatureValidAttributeListener implements EventSubscriberInterface
{
    public function __construct(
        private readonly UriSigner $uriSigner,
        private readonly ContainerInterface $container,
    ) {
    }

    public function onKernelControllerArguments(ControllerArgumentsEvent $event): void
    {
        if (!$attributes = $event->getAttributes(IsSignatureValid::class)) {
            return;
        }

        $request = $event->getRequest();
        foreach ($attributes as $attribute) {
            $methods = array_map('strtoupper', $attribute->methods);
            if ($methods && !\in_array($request->getMethod(), $methods, true)) {
                continue;
            }

            if (null === $attribute->signer) {
                $this->uriSigner->verify($request);
                continue;
            }

            $signer = $this->container->get($attribute->signer);
            if (!$signer instanceof UriSigner) {
                throw new \LogicException(\sprintf('The service "%s" is not an instance of "%s".', $attribute->signer, UriSigner::class));
            }

            $signer->verify($request);
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [KernelEvents::CONTROLLER_ARGUMENTS => ['onKernelControllerArguments', 30]];
    }
}
