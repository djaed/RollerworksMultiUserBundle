<?php

/*
 * This file is part of the RollerworksMultiUserBundle package.
 *
 * (c) Sebastiaan Stok <s.stok@rollerscapes.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rollerworks\Bundle\MultiUserBundle\EventListener;

use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\FOSUserEvents;
use Rollerworks\Bundle\MultiUserBundle\Model\UserDiscriminatorInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ProfileEditListener implements EventSubscriberInterface
{
    private $router;
    private $userDiscriminator;

    public function __construct(UrlGeneratorInterface $router, UserDiscriminatorInterface $userDiscriminator)
    {
        $this->router = $router;
        $this->userDiscriminator = $userDiscriminator;
    }

    public static function getSubscribedEvents()
    {
        return array(
            FOSUserEvents::PROFILE_EDIT_SUCCESS => array('onProfileEditSuccess', 1),
        );
    }

    public function onProfileEditSuccess(FormEvent $event)
    {
        if (null === $event->getResponse()) {
            $url = $this->router->generate($this->userDiscriminator->getCurrentUserConfig()->getRoutePrefix() . '_profile_show');
            $event->setResponse(new RedirectResponse($url));
        }
    }
}
