@servers(['web' => 'deployer@23.96.240.77'])

<?php $whatever = 'hola, whatever'; ?>

@task('deploy')
    echo {{ $whatever }}
@endtask
