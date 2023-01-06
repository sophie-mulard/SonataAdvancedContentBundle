<?php

namespace Sherlockode\SonataAdvancedContentBundle\EventListener;

use Sonata\AdminBundle\Event\ConfigureMenuEvent;

class MenuBuilderListener
{
    /**
     * @param ConfigureMenuEvent $event
     */
    public function addMenuItems(ConfigureMenuEvent $event)
    {
        $menu = $event->getMenu()->getChild('content.label');

        $menu->addChild('acb_tools', [
            'label' => 'tools_menu_label',
            'extras' => ['label_catalogue' => 'AdvancedContentBundle'],
            'route' => 'sherlockode_acb_tools_index',
        ]);
    }
}
