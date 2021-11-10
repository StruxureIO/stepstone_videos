<?php

use humhub\modules\stepstone_videos\Module;
use humhub\modules\stepstone_videos\Events;
use humhub\modules\admin\widgets\AdminMenu;
use humhub\widgets\TopMenu;
use humhub\modules\space\widgets\Menu;
use humhub\modules\dashboard\widgets\Sidebar;
use humhub\modules\search\engine\Search;

return [
	'id' => 'stepstone_videos',
	'class' => Module::class,
	'namespace' => 'humhub\modules\stepstone_videos',
	'events' => [
    ['class' => Search::class, 'event' => Search::EVENT_ON_REBUILD, 'callback' => [Events::class, 'onSearchRebuild']],
		['class' => TopMenu::class, 'event' => TopMenu::EVENT_INIT, 'callback' => [Events::class, 'onTopMenuInit'],],
		['class' => AdminMenu::class, 'event' => AdminMenu::EVENT_INIT, 'callback' => [Events::class, 'onAdminMenuInit']],
    ['class' => Sidebar::class, 'event' => Sidebar::EVENT_INIT, 'callback' => [Events::class, 'addVideoDashboard']],
    ['class' => Menu::class, 'event' => Menu::EVENT_INIT, 'callback' => [Events::class, 'onSpaceMenuInit']],
    //['class' => Search::class, 'event' => Search::EVENT_SEARCH_ATTRIBUTES, 'callback' => [Events::class, 'onSearchAttributes']]      
],
];
