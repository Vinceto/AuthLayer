<h1>Habilitar 2FA</h1>

<p>Escanea el siguiente código QR con tu aplicación de autenticación:</p>
<img src="<?= $qrImage ?>" alt="Escanea este código QR con tu app de autenticación">

<p>O ingresa este código manualmente en tu aplicación:</p>
<pre><?= h($secretKey) ?></pre>

<p>Luego, ingresa el código generado para activar 2FA:</p>

<?= $this->Form->create() ?>
<?= $this->Form->control('otp', ['label' => 'Código 2FA generado por la app']) ?>
<?= $this->Form->button('Confirmar y activar 2FA') ?>
<?= $this->Form->end() ?>