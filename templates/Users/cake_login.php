<?php
// ...existing code...
?>
<h1>Login</h1>
<?= $this->Form->create() ?>
<?= $this->Form->control('email') ?>
<?= $this->Form->control('password') ?>
<?= $this->Form->button(__('Login')) ?>
<?= $this->Form->end() ?>

<?= $this->Html->link(__('Register'), ['controller' => 'Users', 'action' => 'add'], ['class' => 'btn btn-secondary']) ?>
