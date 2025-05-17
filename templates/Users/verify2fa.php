<h1>Verificar 2FA</h1>
<?php
echo $this->Flash->render();
?>

<?= $this->Form->create() ?>

<?= $this->Form->control('otp', [
    'label' => 'Código 2FA',
    'required' => true,
    'autocomplete' => 'one-time-code',
    'placeholder' => 'Ingresa el código que ves en tu app'
]) ?>

<?= $this->Form->button('Verificar') ?>

<?= $this->Form->end() ?>