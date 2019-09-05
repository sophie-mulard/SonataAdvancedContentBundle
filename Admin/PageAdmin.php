<?php

namespace Sherlockode\SonataAdvancedContentBundle\Admin;

use Sherlockode\AdvancedContentBundle\Form\Type\ContentType;
use Sherlockode\AdvancedContentBundle\Manager\ConfigurationManager;
use Sherlockode\AdvancedContentBundle\Model\ContentInterface;
use Sherlockode\AdvancedContentBundle\Model\PageInterface;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class PageAdmin extends AbstractAdmin
{
    /**
     * @var ConfigurationManager
     */
    private $configurationManager;

    public function getFormTheme()
    {
        return array_merge(
            parent::getFormTheme(),
            ['@SherlockodeAdvancedContent/Form/content.html.twig']
        );
    }

    public function configureFormFields(FormMapper $form)
    {
        $form
            ->tab('page.form.tabs.label')
                ->with('page.form.tabs.general')
                    ->add('title', TextType::class, [
                        'label' => 'page.form.title',
                        'attr' => ['class' => 'acb-page-title']
                    ])
                    ->add('slug', TextType::class, [
                        'label' => 'page.form.slug',
                        'attr' => ['class' => !$this->getSubject()->getId() ? 'acb-page-slug' : ''],
                    ])
                    ->add('pageType', ModelType::class, [
                        'label' => 'page.form.page_type',
                        'class' => $this->configurationManager->getEntityClass('page_type'),
                        'property' => 'name',
                        'required' => false,
                        'attr' => ['class' => 'acb-page-page-type'],
                    ], [
                        'admin_code' => 'sherlockode_advanced_content.admin.page_type'
                    ])
        ;
        if ($this->getSubject()->getId()) {
            $form
                ->add('metaDescription', TextareaType::class, [
                    'label' => 'page.form.meta_description',
                    'required' => false,
                ])
                ->add('status', ChoiceType::class, [
                    'label' => 'page.form.status',
                    'choices' => [
                        'page.form.statuses.draft' => PageInterface::STATUS_DRAFT,
                        'page.form.statuses.published' => PageInterface::STATUS_PUBLISHED,
                        'page.form.statuses.trash' => PageInterface::STATUS_TRASH,
                    ],
                    'translation_domain' => 'AdvancedContentBundle',
                ])
            ;
        }
        $form->end();

        if ($this->getSubject()->getId() && $this->getSubject()->getContent() instanceof ContentInterface) {
            $form
                ->with('page.form.tabs.content')
                    ->add('content', ContentType::class, [
                        'label' => 'page.form.content',
                        'contentType' => $this->getSubject()->getContent()->getContentType()
                    ], [
                        'admin_code' => 'sherlockode_advanced_content.admin.content',
                    ])
                ->end()
            ;
        }
        $form->end();
    }

    public function configureListFields(ListMapper $list)
    {
        $list
            ->add('title', null, ['label' => 'page.form.title'])
            ->add('slug', null, ['label' => 'page.form.slug'])
            ->add('status', null, [
                'label' => 'page.form.status',
                'template' => '@SherlockodeSonataAdvancedContent/Page/status.html.twig'
            ])
            ->add('_action', 'actions', [
                'actions' => [
                    'edit'   => [],
                    'delete' => [],
                ],
            ])
        ;
    }

    /**
     * @param ConfigurationManager $configurationManager
     */
    public function setConfigurationManager(ConfigurationManager $configurationManager)
    {
        $this->configurationManager = $configurationManager;
    }
}
