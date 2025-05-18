<?php
declare(strict_types=1);

namespace App\View\Helper;

use Cake\View\Helper;
use Authentication\IdentityInterface;

class IdentityHelper extends Helper
{
    /**
     * Devuelve el usuario autenticado actual.
     *
     * @return \Authentication\IdentityInterface|null
     */
    public function get(): ?IdentityInterface
    {
        return $this->getView()->getRequest()->getAttribute('identity');
    }

    /**
     * Verifica si hay un usuario autenticado.
     *
     * @return bool
     */
    public function isLoggedIn(): bool
    {
        return $this->get() !== null;
    }
}