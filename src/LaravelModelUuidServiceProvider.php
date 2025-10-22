<?php

namespace Dyrynda\Database\Support;

use Dyrynda\Database\Support\Exceptions\UnknownGrammarClass;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\ColumnDefinition;
use Illuminate\Database\Schema\Grammars\Grammar;
use Illuminate\Database\Schema\Grammars\MySqlGrammar;
use Illuminate\Database\Schema\Grammars\PostgresGrammar;
use Illuminate\Database\Schema\Grammars\SQLiteGrammar;
use Illuminate\Support\Fluent;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravelModelUuidServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('model-uuid')
            ->hasConfigFile()
            ->hasInstallCommand(function (InstallCommand $command) {
                $command->publishConfigFile();
            });
    }

    public function packageRegistered()
    {
        Grammar::macro('typeEfficientUuid', function (Fluent $column) {
            if ($this instanceof MySqlGrammar) {
                return sprintf('binary(%d)', $column->length ?? 16);
            }
            if ($this instanceof PostgresGrammar) {
                return 'bytea';
            }
            if ($this instanceof SQLiteGrammar) {
                return 'blob(256)';
            }

            throw new UnknownGrammarClass;
        });

        Blueprint::macro('efficientUuid', function ($column): ColumnDefinition {
            /** @var \Illuminate\Database\Schema\Blueprint $this */
            return $this->addColumn('efficientUuid', $column);
        });
    }
}
