<?php

namespace blizko\LibrenmsAPIPlugin\Hooks;

use App\Plugins\Hooks\MenuEntryHook;

class MenuHook extends MenuEntryHook
{
    public string $view = 'test-plugin::menu.main';
}
