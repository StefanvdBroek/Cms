<?php

namespace Opifer\CmsBundle\Security;

use Opifer\CmsBundle\Entity\User;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;

class ContentVoter extends Voter
{
    private $security;
    private $container;
    private $roles;

    public function __construct(Security $security, ContainerInterface $container, $roles)
    {
        $this->security = $security;
        $this->container= $container;
        $this->roles = $roles;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            // the user must be logged in; if not, deny access
            return false;
        }

        foreach($user->getRoles() as $role) {
            if (in_array($attribute, $attribute['content_roles'])) {
                return true;
            }
        }

        return false;
    }

    protected function supports($attribute, $subject)
    {
        return (isset($attribute['content_roles']) && is_array($attribute['content_roles']));
    }
}
