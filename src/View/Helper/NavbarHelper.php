<?php
declare(strict_types=1);

namespace App\View\Helper;

use Cake\View\Helper;
use Cake\View\View;

class NavbarHelper extends Helper
{
    protected array $helpers = ['Html', 'Identity'];

    public function render(): string
    {
        $user = $this->Identity->get();
        $links = [];

        if (!$user) {
            return ''; // No mostrar nada si no hay sesión
        }

        // Aquí decides qué mostrar según el rol
        $isDeveloper = in_array($user->role, ['developer', 'ti']);

        // Menú básico para todos los usuarios autenticados
        $links[] = ['label' => 'Inicio', 'url' => ['controller' => 'Pages', 'action' => 'home']];
        $links[] = ['label' => 'Perfil', 'url' => ['controller' => 'Users', 'action' => 'profile']];
        
        // Solo para desarrolladores/TI
        if ($isDeveloper) {
            $links[] = ['label' => 'Documentation', 'url' => 'https://book.cakephp.org/5/', 'external' => true];
            $links[] = ['label' => 'API', 'url' => 'https://api.cakephp.org/', 'external' => true];
        }

        $output = '';

        foreach ($links as $link) {
            if (!empty($link['external'])) {
                $output .= $this->Html->link(
                    $link['label'],
                    $link['url'],
                    ['target' => '_blank', 'rel' => 'noopener']
                );
            } else {
                $output .= $this->Html->link(
                    $link['label'],
                    $link['url']
                );
            }
        }

        return $output;
    }
}