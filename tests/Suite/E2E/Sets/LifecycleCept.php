<?php

namespace AmaTeam\Vagranted\Tests\Suite\E2E\Sets;

use AmaTeam\Vagranted\Filesystem\Helper;
use E2ETester;

$root = Helper::getInstallationRoot();
$executable = $root->resolve('bin/vagranted');
$uri = 'git+https://github.com/ama-team/vagranted-php-box.git';

$I = new E2ETester($scenario);
$I->runShellCommand("php $executable sets:install $uri");
$I->seeInShellOutput($uri);
$I->runShellCommand("php $executable sets:list");
$I->seeInShellOutput($uri);
$I->runShellCommand("php $executable sets:inspect $uri");
$I->runShellCommand("php $executable sets:evict PT0S");
$I->seeInShellOutput($uri);
$I->runShellCommand("php $executable sets:list", false);
$I->dontSeeInShellOutput($uri);
