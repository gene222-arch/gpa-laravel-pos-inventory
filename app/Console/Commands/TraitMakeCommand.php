<?php

namespace App\Console\Commands;

use Illuminate\Console\GeneratorCommand;

class TraitMakeCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:trait {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create new trait';

    /**
     * type of classes being generated
     */
    protected $type = 'Trait';

    /**
     * Get the stub file
     *
     */
    public function getStub()
    {
        return __DIR__ . '/stubs/traits.stub';
    }

    /**
     * Get the default namespace
     *
     * @param string $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\Traits';
    }
}
