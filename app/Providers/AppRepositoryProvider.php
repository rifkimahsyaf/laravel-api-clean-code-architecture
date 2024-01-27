<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppRepositoryProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $models = ['User', 'Workspace', 'UserIntroduction', 'RefreshToken', 'WorkspaceIcon', 'WorkspaceHasMember', 'Project', 'ProjectHasMember', 'RecentlyVisitedProjects', 'Device'];
        foreach ($models as $model) {
            $this->app->bind("App\Http\Repositories\Interface\\{$model}RepositoryInterface", "App\Http\Repositories\\{$model}Repository");
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
