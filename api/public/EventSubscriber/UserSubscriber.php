<?php

namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\User;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Event\CheckPassportEvent;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\BadgeInterface;

class UserSubscriber implements EventSubscriberInterface
{
    private $passwordEncoder;

    public function __construct(UserPasswordHasherInterface  $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function onCheckPassport(CheckPassportEvent $event)
    {
        $passport = $event->getPassport();
        if (!$passport instanceof BadgeInterface) {
            throw new \Exception('Unexpected passport type');
        }
        $user = $passport->getUser();
dd($user); die();        
        //$user->password = $user->plainPassword;
        $user->eraseCredentials();
    }
    public static function getSubscribedEvents()
    {
        return [
            CheckPassportEvent::class => ['onCheckPassport', -10],
        ];
    }




}
