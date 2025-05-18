<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 * @var \App\View\AppView $this
 */

$cakeDescription = 'CakePHP: the rapid development php framework';
?>
<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>
        <?= $cakeDescription ?>:
        <?= $this->fetch('title') ?>
    </title>
    <?= $this->Html->meta('icon') ?>

    <?= $this->Html->css(['normalize.min', 'milligram.min', 'fonts', 'cake', 'idiem']) ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>

</head>
<body>
    <div class="custom-banner">
        <div class="curve-bg">
            <div class="container idiem-logo-container">
                <div style="display: flex; align-items: center; justify-content: space-between; position: relative;">
                    <a href="/AuthLayer/" class="idiem-logo" style="flex: 1;">
                        <span class="idiem-rest">Id<span class="idiem-i">i</span>em</span><br>
                        <span class="idiem-subtitle">UN SIGLO DE CONFIANZA Y RESPALDO</span>
                    </a>
                    <?php if ($this->Identity->isLoggedIn()): ?>
                        <?php $user = $this->Identity->get(); ?>
                        <div class="banner-user-info" style="flex: 1; text-align: right; position: absolute; bottom: 0; right: 0;">
                            <span class="welcome-msg">Bienvenido, <?= h($user->name ?? $user->username ?? 'Usuario') ?></span>
                            <a href="<?= $this->Url->build(['controller' => 'Users', 'action' => 'logout']) ?>" class="logout-link">Cerrar sesión</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
       
    </div>
    
    <nav class="top-nav">
        <div class="top-nav-title">
            <a href="<?= $this->Url->build('/') ?>"><span>Intranet </span>Azul</a>
        </div>
        <div class="top-nav-links">
            <?= $this->Navbar->render() ?>
        </div>
    </nav>

    <main class="main">
        <div class="container">
            <?= $this->Flash->render() ?>
            <?= $this->fetch('content') ?>
        </div>
    </main>
    <footer>
    </footer>
</body>
</html>
