<?php

namespace Demon\AdminLaravel\console;

use Demon\AdminLaravel\access\model\UserModel;
use Demon\AdminLaravel\example\Service;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Composer;
use Illuminate\Support\Str;

class ResetCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'admin:reset';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:reset {--uid= : account uid} {--username= : account username} {--password=demon : The default is demon}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset the password for the specified account';

    /**
     * Reset the password for the specified account command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $uid = $this->option('uid');
        $username = $this->option('username');
        $password = $this->option('password');
        if ($uid || $username) {
            $field = $uid ? 'uid' : 'username';
            $account = $field == 'uid' ? $uid : $username;
            if ($field == 'uid' && !is_numeric($account))
                return $this->error('UID must be a number');
            $user = UserModel::where($field, $account)->first();
            if (!$user || $user->{$field} != $account)
                return $this->error('The account does not exist.');
            UserModel::reset($user->uid, $password);

            return $this->info('Password reset successfully!');
        }
        else {
            $field = $this->choice('What is the account type?', ['uid', 'username'], 'uid');
            $account = $this->ask('What is the account?');
            if (!$account)
                return $this->error("Please enter the account");
            if ($field == 'uid' && !is_numeric($account))
                return $this->error('UID must be a number');
            $password = $this->secret('What is the password?');
            if (!$password)
                return $this->error('Please enter password.');
            $user = UserModel::where($field, $account)->first();
            if (!$user)
                return $this->error('The account does not exist.');
            if ($this->confirm("Do you confirm to reset [{$user->uid}]{$user->username}({$user->nickname})'s password?", true)) {
                UserModel::reset($user->uid, $password);

                return $this->info('Password reset successfully!');
            }
            else
                return $this->warn('To abandon this reset.');
        }
    }
}
