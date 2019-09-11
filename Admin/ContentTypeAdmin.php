<?php

namespace Sherlockode\SonataAdvancedContentBundle\Admin;

use Sherlockode\AdvancedContentBundle\Form\Type\ContentTypeFormType;
use Sherlockode\AdvancedContentBundle\Manager\ConfigurationManager;
use Sherlockode\AdvancedContentBundle\Manager\ContentTypeManager;
use Sherlockode\AdvancedContentBundle\Model\ContentType;
use Sherlockode\AdvancedContentBundle\Model\ContentTypeInterface;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class ContentTypeAdmin extends AbstractAdmin
{
    /**
     * @var ContentTypeManager
     */
    private $contentTypeManager;

    /**
     * @var array
     */
    private $fieldTypes = [];

    /**
     * @var ConfigurationManager
     */
    private $configurationManager;

    /**
     * @param ContentTypeManager $manager
     */
    public function setContentTypeManager(ContentTypeManager $manager)
    {
        $this->contentTypeManager = $manager;
    }

    /**
     * @param ConfigurationManager $configurationManager
     */
    public function setConfigurationManager(ConfigurationManager $configurationManager)
    {
        $this->configurationManager = $configurationManager;
    }

    public function getFormTheme()
    {
        return array_merge(
            parent::getFormTheme(),
            ['@SherlockodeAdvancedContent/Form/content_type.html.twig']
        );
    }

    public function configureFormFields(FormMapper $form)
    {
        // We need to declare each form field through the form mapper to ensure they are displayed correctly
        $form
            ->add('name', TextType::class, ['label' => 'content_type.form.name'])
            ->add('linkType', ChoiceType::class, [
                'label' => 'content_type.form.link_types.label',
                'choices' => [
                    'content_type.form.link_types.no_link' => ContentTypeInterface::LINK_TYPE_NO_LINK,
                    'content_type.form.link_types.page_type' => ContentTypeInterface::LINK_TYPE_PAGE_TYPE,
                    'content_type.form.link_types.page' => ContentTypeInterface::LINK_TYPE_PAGE
                ],
                'translation_domain' => 'AdvancedContentBundle',
                'mapped' => false,
                'attr' => ['class' => 'acb-contenttype-link-type'],
            ])
            ->add('pageType', EntityType::class, [
                'label' => 'content_type.form.page_type',
                'class' => $this->configurationManager->getEntityClass('page_type'),
                'choice_label' => 'name',
                'required' => false,
                'attr' => ['class' => 'acb-contenttype-page-type'],
            ])
            ->add('page', EntityType::class, [
                'label' => 'content_type.form.page',
                'class' => $this->configurationManager->getEntityClass('page'),
                'choice_label' => 'title',
                'required' => false,
                'attr' => ['class' => 'acb-contenttype-page'],
            ])
            ->add('allowSeveralContents', CheckboxType::class, [
                'label' => 'content_type.form.allow_several_contents',
                'required' => false,
                'attr' => ['class' => 'acb-contenttype-allow-several-contents'],
            ])
        ;
        if ($this->getSubject()->getId()) {
            // We need to declare each form field through the form mapper to ensure they are displayed correctly
            $form
                ->add('fields', FormType::class, ['label' => 'content_type.fields'])
            ;

            $fieldTypes = [];
            foreach ($this->getSubject()->getFields() as $field) {
                $fieldTypes[$field->getId()] = $field->getType();
            }
            $this->fieldTypes = $fieldTypes;
        }
    }

    /**
     * Create ContentTypeFormType form
     *
     * @return mixed
     */
    private function getContentTypeFormBuilder()
    {
        $this->formOptions['contentType'] = $this->getSubject();

        return $this->getFormContractor()->getFormFactory()
            ->createNamedBuilder($this->getUniqid(), ContentTypeFormType::class, null, $this->formOptions);
    }

    public function getFormBuilder()
    {
        $this->formOptions['data_class'] = $this->getClass();

        $formBuilder = $this->getContentTypeFormBuilder();
        $this->defineFormBuilder($formBuilder);

        return $formBuilder;
    }

    public function defineFormBuilder(FormBuilderInterface $formBuilder)
    {
        $formBuilder = $this->getContentTypeFormBuilder();
        parent::defineFormBuilder($formBuilder);
    }

    public function configureListFields(ListMapper $list)
    {
        $list
            ->add('name', null, ['label' => 'content_type.form.name'])
            ->add('_action', 'actions', [
                'actions' => [
                    'edit'   => [],
                    'delete' => [],
                ],
            ])
        ;
    }

    public function preUpdate($object)
    {
        $this->contentTypeManager->processFieldsChangeType($object, $this->fieldTypes);
    }

    public function toString($object)
    {
        if ($object instanceof ContentType && $object->getName()) {
            return $object->getName();
        }

        return parent::toString($object);
    }
}
