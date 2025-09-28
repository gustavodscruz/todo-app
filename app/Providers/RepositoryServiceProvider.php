<?php

namespace App\Providers;

use App\Repositories\TodoList\Concretes\TodoListRepository;
use App\Repositories\TodoList\Contracts\TodoListRepositoryInterface;
use App\Repositories\User\Concretes\UserRepository;
use App\Repositories\User\Contracts\UserRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Register repository bindings here
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(TodoListRepositoryInterface::class, TodoListRepository::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
