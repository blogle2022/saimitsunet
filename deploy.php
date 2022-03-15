<?php
namespace Deployer;

require 'recipe/common.php';

// Project name
set('application', 'saimitsu.net');

// Project repository
set('repository', 'git@github.com:blogle2022/saimitsunet.git');

// [Optional] Allocate tty for git clone. Default value is false.
set('git_tty', true);

// Shared files/dirs between deploys
set('shared_files', []);
set('shared_dirs', []);

// Writable dirs by web server
set('writable_dirs', []);


// Hosts

host('59.106.19.189')
    ->user('saimitsucom');


// Tasks

desc('Deploy your project');
task('deploy:dev', function () {
    cd('~/www/saimitsutest');
    run('git fetch origin develop');
    run('git reset --hard origin/develop');
    run('composer install --no-dev');
});
