<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class UpdatePermission extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permission:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
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
     * @return int
     */
    public function handle()
    {
        $users = User::all();
        foreach ($users as $user) {
            $hasPermissions = [];
            foreach ($user->roles as $role) {
                $hasPermissions = array_unique(array_merge($hasPermissions, $role->permissions->pluck('id')->toArray()));
            }
            $user->permissions()->sync($hasPermissions);
        }
        return 0;
    }
}
