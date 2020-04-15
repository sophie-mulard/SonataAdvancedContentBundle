<?php

namespace Sherlockode\SonataAdvancedContentBundle\Admin;

use Sherlockode\AdvancedContentBundle\Form\Type\ContentType;
use Sherlockode\AdvancedContentBundle\Manager\ContentTypeManager;
use Sherlockode\AdvancedContentBundle\Model\ContentInterface;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\FormBuilderInterface;

class ContentAdmin extends AbstractAdmin
{
    /**
     * @var string
     */
    private $contentTypeClass;

    /**
     * @var ContentTypeManager
     */
    private $contentTypeManager;

    protected $baseRouteName = 'admin_afb_content';

    public function getFormTheme()
    {
        return array_merge(
            parent::getFormTheme(),
            ['@SherlockodeAdvancedContent/Form/content.html.twig']
        );
    }

    public function setContentTypeClass($class)
    {
        $this->contentTypeClass = $class;
    }

    public function configureFormFields(FormMapper $form)
    {
        $form->with('default'); // init the groups data for this admin class
        $groups = $this->getFormGroups();
        $groups['default']['fields'] = [
            'name' => 'name',
            'slug' => 'slug',
            'fieldValues' => 'fieldValues',
        ];
        $this->setFormGroups($groups);
    }

    /**
     * Create ContentType form
     *
     * @return FormBuilderInterface
     *
     * @throws \Exception
     */
    private function getCustomFormBuilder()
    {
        if ($this->hasParentFieldDescription() && $this->getParentFieldDescription()->getOption('content_type')) {
            $contentType = $this->getParentFieldDescription()->getOption('content_type');
        } elseif ($this->getSubject() && $this->getSubject()->getContentType()) {
            $contentType = $this->getSubject()->getContentType();
        } elseif ($this->getRequest()->get('content_type_id')) {
            $contentTypeId = $this->getRequest()->get('content_type_id');
            $contentType = $this->getModelManager()
                ->getEntityManager($this->contentTypeClass)
                ->getRepository($this->contentTypeClass)
                ->find($contentTypeId);
        } else {
            throw new \Exception('Unable to guess the ContentType to use for this object');
        }

        $this->getSubject()->setContentType($contentType);
        $this->formOptions['contentType'] = $contentType;

        return $this->getFormContractor()->getFormFactory()
            ->createNamedBuilder($this->getUniqid(), ContentType::class, null, $this->formOptions);
    }

    public function getFormBuilder()
    {
        $this->formOptions['data_class'] = $this->getClass();

        $formBuilder = $this->getCustomFormBuilder();
        $this->defineFormBuilder($formBuilder);

        return $formBuilder;
    }

    public function configureListFields(ListMapper $list)
    {
        $list
            ->add('id', null, ['label' => 'content.id'])
            ->add('name', null, ['label' => 'content.form.name'])
            ->add('contentType', null, ['associated_property' => 'name', 'label' => 'content.content_type'])
            ->add('_action', 'actions', [
                'actions' => [
                    'edit'   => [],
                    'delete' => [],
                ],
            ])
        ;
    }

    public function createQuery($context = 'list')
    {
        $query = parent::createQuery();
        $request = $this->getRequest();

        $query->getQueryBuilder()
            ->join('o.contentType', 'content_type')
            ->where('content_type.id = :type')
            ->setParameter('type', $request->get('content_type_id'));

        return $query;
    }

    public function getPersistentParameters()
    {
        $parameters = parent::getPersistentParameters();

        return array_merge($parameters, [
            'content_type_id' => $this->getRequest()->get('content_type_id'),
        ]);
    }

    /**
     * @param ContentTypeManager $contentTypeManager
     */
    public function setContentTypeManager(ContentTypeManager $contentTypeManager)
    {
        $this->contentTypeManager = $contentTypeManager;
    }

    /**
     * @param string      $action
     * @param null|object $object
     *
     * @return array
     */
    public function configureActionButtons($action, $object = null)
    {
        $actions = parent::configureActionButtons($action, $object);
        if (!$this->contentTypeManager->canCreateContentByContentTypeId($this->getRequest()->get('content_type_id'))) {
            unset($actions['create']);
        }

        return $actions;
    }

    /**
     * @param $object
     *
     * @return string
     */
    public function toString($object)
    {
        if ($object instanceof ContentInterface && $object->getName()) {
            return $object->getName();
        }

        return parent::toString($object);
    }
}
