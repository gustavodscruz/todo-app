<?php

namespace App\Providers;

use App\Repositories\Todo\Concretes\TodoRepository;
use App\Repositories\Todo\Contracts\TodoRepositoryInterface;
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
        $this->app->bind(TodoRepositoryInterface::class, TodoRepository::class);
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
