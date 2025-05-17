<!DOCTYPE html>
<html>
<head>
    <title>Idiem</title>
    <?= $this->Html->css([
        'http://intranet.idiem.cl/intranet/diseno/css/estilo.css',
        'http://intranet.idiem.cl/intranet/diseno/css/menu.css',
        'http://intranet.idiem.cl/intranet/diseno/css/print.css'
    ]) ?>
    <link rel="shortcut icon" href="http://intranet.idiem.cl/intranet/diseno/imagenes/favicon.ico" type="image/x-icon">
    <?= $this->Html->script('http://intranet.idiem.cl/intranet/diseno/js/javascript.js') ?>

    <style>
        #loginnuevo {
            margin: auto;
            width: 300px;
            border: 1px solid #ccc;
            box-shadow: 3px 3px 8px #aaa;
            padding: 20px;
            border-radius: 10px;
            margin-top: 100px;
            background: #fff;
        }

        table {
            margin-top: 10px;
            width: 100%;
        }

        table td {
            border: none;
            text-align: center;
            padding: 5px;
        }

        h1.idiem {
            background-image: url('http://intranet.idiem.cl/intranet/diseno/imagenes/baner3.png');
            height: 60px;
            background-repeat: no-repeat;
            background-size: cover;
            text-indent: -9999px; /* oculta el texto visualmente */
            margin: 0;
            padding: 0;
        }
    </style>
</head>
<body>
    <h1 class="idiem">Idiem</h1>
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
