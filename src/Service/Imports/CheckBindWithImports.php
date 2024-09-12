<?php

namespace App\Service\Imports;

class CheckBindWithImports
{
    public function __construct(
        // in services.yaml
        private $faker,
        // imports services.yaml
        //private $importedParameter,
    ) {
        \dump(
            $faker->name(),
        );
    }
}
