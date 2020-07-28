<?php

namespace Sherlockode\SonataAdvancedContentBundle\Admin;

use Sherlockode\AdvancedContentBundle\Form\Type\PageType;
use Sherlockode\AdvancedContentBundle\Locale\LocaleProviderInterface;
use Sherlockode\AdvancedContentBundle\Manager\PageManager;
use Sherlockode\AdvancedContentBundle\Model\ContentTypeInterface;
use Sherlockode\AdvancedContentBundle\Model\PageInterface;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\FormBuilderInterface;

class PageAdmin extends AbstractAdmin
{
    /**
     * @var PageManager
     */
    private $pageManager;

    /**
     * @var LocaleProviderInterface
     */
    private $localeProvider;

    public function getFormTheme()
    {
        return array_merge(
            parent::getFormTheme(),
            ['@SherlockodeAdvancedContent/Form/content.html.twig']
        );
    }

    public function configureFormFields(FormMapper $form)
    {
        $form->tab('page.form.tabs.label')
            ->with('page.form.tabs.general')->end(); // init the groups data for this admin class

        $fields = [
            'pageIdentifier' => 'pageIdentifier',
            'pageType' => 'pageType',
        ];
        if ($this->getSubject()->getId()) {
            $fields = array_merge($fields, [
                'status' => 'status',
            ]);
        }

        $groups = $this->getFormGroups();
        $groups['page.form.tabs.label.page.form.tabs.general']['fields'] = $fields;
        $this->setFormGroups($groups);

        $form->with('page.form.tabs.meta')->end();
        if ($this->localeProvider->isMultilangEnabled()) {
            $fields = ['pageMetas' => 'pageMetas'];
        } else {
            $fields = ['pageMeta' => 'pageMeta'];
        }
        $groups = $this->getFormGroups();
        $groups['page.form.tabs.label.page.form.tabs.meta']['fields'] = $fields;
        $this->setFormGroups($groups);

        if ($this->getSubject()->getId()) {
            $contentType = $this->pageManager->getPageContentType($this->getSubject());
            if ($contentType instanceof ContentTypeInterface) {
                $form->with('page.form.tabs.content')->end();
                if ($this->localeProvider->isMultilangEnabled()) {
                    $fields = ['contents' => 'contents'];
                } else {
                    $fields = ['content' => 'content'];
                }
                $groups = $this->getFormGroups();
                $groups['page.form.tabs.label.page.form.tabs.content']['fields'] = $fields;
                $this->setFormGroups($groups);
            }
        }
        $form->end();
    }

    public function getFormBuilder()
    {
        $this->formOptions['data_class'] = $this->getClass();

        $formBuilder = $this->getCustomFormBuilder();
        $this->defineFormBuilder($formBuilder);

        return $formBuilder;
    }

    /**
     * Create PageType form
     *
     * @return FormBuilderInterface
     *
     * @throws \Exception
     */
    private function getCustomFormBuilder()
    {
        return $this->getFormContractor()
            ->getFormFactory()
            ->createNamedBuilder($this->getUniqid(), PageType::class, null, $this->formOptions);
    }

    public function configureListFields(ListMapper $list)
    {
        $list
            ->add('pageIdentifier', null, ['label' => 'page.form.page_identifier'])
            ->add('title', null, [
                'label' => 'page.form.title',
                'template' => '@SherlockodeSonataAdvancedContent/Page/title.html.twig'
            ])
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
     * @param $object
     *
     * @return string
     */
    public function toString($object)
    {
        if ($object instanceof PageInterface && $object->getPageIdentifier()) {
            return $object->getPageIdentifier();
        }

        return parent::toString($object);
    }

    /**
     * @param PageManager $pageManager
     */
    public function setPageManager(PageManager $pageManager)
    {
        $this->pageManager = $pageManager;
    }

    /**
     * @param LocaleProviderInterface $localeProvider
     */
    public function setLocaleProvider(LocaleProviderInterface $localeProvider)
    {
        $this->localeProvider = $localeProvider;
    }
}
