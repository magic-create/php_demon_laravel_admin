<?php

namespace Demon\AdminLaravel\console;

use Demon\AdminLaravel\example\Service;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Composer;
use Illuminate\Support\Str;

class TableCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'admin:table';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a migration for the admin database table';

    /**
     * The filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;

    /**
     * @var \Illuminate\Support\Composer
     */
    protected $composer;

    /**
     * Create a new admin table command instance.
     *
     * @param \Illuminate\Filesystem\Filesystem $files
     * @param \Illuminate\Support\Composer      $composer
     *
     * @return void
     */
    public function __construct(Filesystem $files, Composer $composer)
    {
        parent::__construct();

        $this->files = $files;
        $this->composer = $composer;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $example_master = Service::MasterModel;
        $this->replaceMigration($this->createBaseMigration($example_master), $example_master, Str::studly($example_master), $example_master);

        $example_setting = Service::SettingModel;
        $this->replaceMigration($this->createBaseMigration($example_setting), $example_setting, Str::studly($example_setting), $example_setting);

        $example_slave = Service::SlaveModel;
        $this->replaceMigration($this->createBaseMigration($example_slave), $example_slave, Str::studly($example_slave), $example_slave);

        $session = config('admin.session.table');
        $this->replaceMigration($this->createBaseMigration($session), $session, Str::studly($session), 'admin_session');

        $allot = 'admin_allot';
        $this->replaceMigration($this->createBaseMigration($allot), $allot, Str::studly($allot), $allot);

        $log = 'admin_log';
        $this->replaceMigration($this->createBaseMigration($log), $log, Str::studly($log), $log);

        $menu = 'admin_menu';
        $this->replaceMigration($this->createBaseMigration($menu), $menu, Str::studly($menu), $menu);

        $role = 'admin_role';
        $this->replaceMigration($this->createBaseMigration($role), $role, Str::studly($role), $role);

        $user = 'admin_user';
        $this->replaceMigration($this->createBaseMigration($user), $user, Str::studly($user), $user);

        $this->info('Migration created successfully!');

        $this->composer->dumpAutoloads();
    }

    /**
     * Create a base migration file for the table.
     *
     * @param string $table
     *
     * @return string
     */
    protected function createBaseMigration($table)
    {
        return $this->laravel['migration.creator']->create('create_' . $table . '_table', $this->laravel->databasePath() . '/migrations');
    }

    /**
     * Replace the generated migration with the admin table stub.
     *
     * @param string $path
     * @param string $table
     * @param string $tableClassName
     * @param string $file
     *
     * @return void
     */
    protected function replaceMigration($path, $table, $tableClassName, $file)
    {
        $this->files->put($path, str_replace(['{{table}}', '{{tableClassName}}', '{{connection}}'], [$table, $tableClassName, config('admin.connection')], $this->files->get(__DIR__ . "/stubs/{$file}.stub")));
    }
}
