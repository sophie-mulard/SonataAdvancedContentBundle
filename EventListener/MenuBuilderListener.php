<?php

namespace Sherlockode\SonataAdvancedContentBundle\EventListener;

use Doctrine\Common\Persistence\ObjectManager;
use Sherlockode\AdvancedContentBundle\Manager\ConfigurationManager;
use Sherlockode\AdvancedContentBundle\Model\ContentTypeInterface;
use Sherlockode\AdvancedContentBundle\Model\PageInterface;
use Sherlockode\AdvancedContentBundle\Model\PageTypeInterface;
use Sonata\AdminBundle\Event\ConfigureMenuEvent;

class MenuBuilderListener
{
    /**
     * @var ConfigurationManager
     */
    private $configurationManager;

    /**
     * @var ObjectManager
     */
    private $om;

    /**
     * @param ObjectManager        $om
     * @param ConfigurationManager $configurationManager
     */
    public function __construct(ObjectManager $om, ConfigurationManager $configurationManager)
    {
        $this->configurationManager = $configurationManager;
        $this->om = $om;
    }

    /**
     * @param ConfigureMenuEvent $event
     */
    public function addMenuItems(ConfigureMenuEvent $event)
    {
        $menu = $event->getMenu()->getChild('content.label');

        $contentTypeClass = $this->configurationManager->getEntityClass('content_type');
        $contentTypes = $this->om->getRepository($contentTypeClass)->findAll();
        /** @var ContentTypeInterface $contentType */
        foreach ($contentTypes as $contentType) {
            if ($contentType->getPage() instanceof PageInterface || $contentType->getPageType() instanceof PageTypeInterface) {
                continue;
            }
            $menu->addChild('content_type_' . $contentType->getId(), [
                'label' => $contentType->getName(),
                'extras' => [
                    'label_catalogue' => false,
                ],
                'route' => 'admin_afb_content_list',
                'routeParameters' => ['content_type_id' => $contentType->getId()],
            ]);
        }
    }
}
