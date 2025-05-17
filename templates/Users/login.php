<!DOCTYPE html>
<html>
<head>
    <title>Idiem</title>

    <style>
        

    </style>
</head>
<body>
    <div id="loginnuevo">
        <h2>Iniciar sesión</h2>

        <?= $this->Form->create() ?>
        <table>
            <tr>
                <td><?= $this->Form->control('email', ['label' => 'Usuario', 'required' => true]) ?></td>
            </tr>
            <tr>
                <td><?= $this->Form->control('password', ['label' => 'Contraseña', 'required' => true]) ?></td>
            </tr>
            <tr>
                <td><?= $this->Form->button('Entrar', ['class' => 'btn']) ?></td>
            </tr>
        </table>
        <?= $this->Form->end() ?>
    </div>
</body>
</html>
