<?php

namespace AmaTeam\Vagranted\Tests\Suite\E2E\Installer\Git;

use AmaTeam\Vagranted\Filesystem\Helper;
use E2ETester;

$root = Helper::getInstallationRoot();
$executable = $root->resolve('bin/vagranted');
$set = 'https+zip://github.com/ama-team/vagranted-php-box/archive/0.1.0.zip';

$I = new E2ETester($scenario);
$I->runShellCommand("php $executable sets:install $set --log-level debug");
$I->runShellCommand("php $executable sets:list");
$I->seeInShellOutput($set);
