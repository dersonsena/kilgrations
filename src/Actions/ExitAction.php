<?php

namespace Dersonsena\Migrations\Actions;

class ExitAction implements ActionInterface
{
    public function execute()
    {
        echo 'See ya! ;)' . PHP_EOL;
        exit(0);
    }
}
