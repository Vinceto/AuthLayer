<!DOCTYPE html>
<html>
<head>
    <title>Idiem - Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
       
    </style>
</head>
<body>
    <div class="login-container">
        <h2>In<span class="idiem-i">i</span>c<span class="idiem-i">i</span>ar ses<span class="idiem-i">i</span>ón</h2>
        <?= $this->Form->create() ?>

            <?= $this->Form->control('email', [
                'label' => 'Usuario',
                'required' => true,
                'type' => 'email',
                'placeholder' => 'correo@idiem.cl'
            ]) ?>

            <?= $this->Form->control('password', [
                'label' => 'Contraseña',
                'required' => true,
                'placeholder' => '••••••••'
            ]) ?>

            <?= $this->Form->button('Entrar', ['class' => 'button-primary']) ?>

        <?= $this->Form->end() ?>
    </div>
</body>
</html>
