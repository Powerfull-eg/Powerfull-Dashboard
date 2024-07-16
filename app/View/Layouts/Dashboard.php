<?php

namespace App\View\Layouts;

use App\Models\Admin;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\Component;
use Spatie\Permission\Models\Permission;

class Dashboard extends Component
{
    /**
     * The authenticated admin user.
     */
    public Admin $admin;

    /**
     * The items to be displayed in the sidebar.
     */
    public array $sidebar = [];

    /**
     * Current page title.
     */
    public string $title = '';

    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        if(!auth()->user())
            return redirect()->route("dashboard.login");
        $this->admin = auth()->user();  
        $this->sidebar = $this->getSidebarItems();
    }

    /**
     * Get the sidebar items.
     */
    protected function getSidebarItems(): array
    {
        $sidebar = [];

        foreach (config('sidebar') as $item) {
            $item = $this->parseSidebarItem($item);

            if ($item !== false) {
                $sidebar[] = $item;
            }
        }

        return $sidebar;
    }

    /**
     * Parses a sidebar item array and returns it as an object.
     */
    protected function parseSidebarItem(array $item): object|bool
    {
        $item = (object) $item;

        if (isset($item->route) && ! $this->hasPermissionTo($item->route)) {
            return false;
        }

        $locale = app()->getLocale();
        $item->title = $item->locales[$locale] ?? Arr::first($item->locales);

        $item->url = isset($item->route) ? route($item->route) : '#';
        $item->active = $item->url && $this->isActiveUrl($item->url);

        // Set the page title to the current item title if it's active.
        if ($item->active) {
            $this->title = $item->title;
        }

        if (isset($item->children)) {
            $item->children = collect($item->children)->map(fn ($child) => $this->parseSidebarItem($child))->filter();

            if ($item->children->isEmpty()) {
                return false;
            }

            $item->active = $item->children->contains('active', true);
        }

        return $item;
    }

    /**
     * Determines if the authenticated admin user has permission to access a given route.
     */
    protected function hasPermissionTo(string $route): bool
    {
        $key = 'permission.' . $this->admin->id . '.' . $route;

        return cache()->rememberForever($key, function () use ($route) {
            return Permission::whereName($route)->doesntExist() || Gate::allows($route);
        });
    }

    /**
     * Determines if a given URL is currently active.
     */
    protected function isActiveUrl(string $url): bool
    {
        if ($url === url('/dashboard')) {
            return request()->is('dashboard');
        }

        return str_starts_with(request()->url(), $url);
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {
        return view('layouts.dashboard.base');
    }
}
