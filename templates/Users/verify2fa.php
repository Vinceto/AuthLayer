<h1>Verificar 2FA</h1>
<?= $this->Form->create() ?>
<?= $this->Form->control('otp', ['label' => 'Código 2FA']) ?>
<?= $this->Form->button('Verificar') ?>
<?= $this->Form->end() ?>