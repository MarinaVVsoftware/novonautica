<?php
/**
 * Created by PhpStorm.
 * User: Luiz
 * Date: 27/06/2018
 * Time: 04:44 PM
 */

namespace AppBundle\Security;


use AppBundle\Entity\Combustible;
use AppBundle\Entity\Usuario;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class CombustibleVoter extends Voter
{
    const CREATE = 'COMBUSTIBLE_COTIZACION_CREATE';
    const DELETE = 'COMBUSTIBLE_COTIZACION_DELETE';
    const VALIDATE = 'COMBUSTIBLE_COTIZACION_VALIDATE';
    const REQUOTE = 'COMBUSTIBLE_COTIZACION_REQUOTE';

    private $decisionManager;

    public function __construct(AccessDecisionManagerInterface $decisionManager)
    {
        $this->decisionManager = $decisionManager;
    }

    /**
     * Determines if the attribute and subject are supported by this voter.
     *
     * @param string $attribute An attribute
     * @param mixed $subject The subject to secure, e.g. an object the user wants to access or any other PHP type
     *
     * @return bool True if the attribute and subject are supported, false otherwise
     */
    protected function supports($attribute, $subject)
    {
        if (!in_array($attribute, [self::CREATE, self::VALIDATE, self::REQUOTE, self::DELETE])) {
            return false;
        }
        if (!$subject instanceof Combustible) {
            return false;
        }
        return true;
    }

    /**
     * Perform a single access check operation on a given attribute, subject and token.
     * It is safe to assume that $attribute and $subject already passed the "supports()" method check.
     *
     * @param string $attribute
     * @param mixed $subject
     * @param TokenInterface $token
     *
     * @return bool
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof UserInterface) {
            return false;
        }

        if ($this->decisionManager->decide($token, ['ROLE_ADMIN'])) {
            return true;
        }

        /** @var Combustible $combustible */
        $combustible = $subject;

        switch ($attribute) {
            case self::CREATE:
                return $this->canCreate($user);
                break;
            case self::DELETE:
                return $this->canDelete($user);
                break;
            case self::VALIDATE:
                return $this->canValidate($user);
                break;
            case self::REQUOTE:
                return $this->canRequote($user);
                break;
        }

        throw new \LogicException('This code should not be reached!');
    }

    private function canCreate(Usuario $usuario)
    {
        if (!in_array(self::CREATE, $usuario->getRoles())) {
            return false;
        }

        return true;
    }

    private function canDelete(Usuario $usuario)
    {
        if (!in_array(self::DELETE, $usuario->getRoles())) {
            return false;
        }

        return true;
    }

    private function canValidate(Usuario $usuario)
    {
        if (!in_array(self::VALIDATE, $usuario->getRoles())) {
            return false;
        }

        return true;
    }

    private function canRequote(Usuario $usuario)
    {
        if (!in_array(self::REQUOTE, $usuario->getRoles())) {
            return false;
        }

        return true;
    }
}