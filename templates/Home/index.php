<h1>Bienvenido a tu aplicación CakePHP 🎉</h1>
<p>Has iniciado sesión correctamente y tu 2FA está verificado.</p>
<?php if (!empty($databases)): ?>
    <h2>Bases de datos disponibles</h2>
    <div class="container">
        <div class="row">
            <?php foreach ($databases as $i => $database): ?>
                <div class="column">
                    <div class="panel">
                        <div class="panel-body">
                            <?= h($database) ?>
                        </div>
                    </div>
                </div>
                <?php if (($i + 1) % 4 == 0): ?>
                    </div><div class="row">
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
<?php else: ?>
    <p>No se encontraron bases de datos.</p>
<?php endif; ?>

<?php if (!empty($tables)): ?>
    <h2>Tablas en la base de datos</h2>
    <div class="container">
        <div class="row">
            <?php foreach ($tables as $i => $table): ?>
                <div class="column">
                    <div class="panel">
                        <div class="panel-body">
                            <?= h($table) ?>
                        </div>
                    </div>
                </div>
                <?php if (($i + 1) % 4 == 0): ?>
                    </div><div class="row">
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
<?php else: ?>
    <p>No se encontraron tablas en la base de datos.</p>
<?php endif; ?>


