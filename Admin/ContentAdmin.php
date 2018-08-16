<?php

namespace Sherlockode\SonataAdvancedContentBundle\Admin;

use Sherlockode\AdvancedContentBundle\Manager\FormBuilderManager;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;

class ContentAdmin extends AbstractAdmin
{
    /**
     * @var FormBuilderManager
     */
    private $formBuilderManager;

    /**
     * @var string
     */
    private $contentTypeClass;

    protected $baseRouteName = 'admin_afb_content';

    public function setFormBuilderManager(FormBuilderManager $manager)
    {
        $this->formBuilderManager = $manager;
    }

    public function setContentTypeClass($class)
    {
        $this->contentTypeClass = $class;
    }

    public function configureFormFields(FormMapper $form)
    {
        $form
            ->add('fieldValues')
        ;

        if (!$this->getSubject()->getId()) {
            $contentTypeId = $this->getRequest()->get('content_type_id');
            $contentType = $this->getModelManager()
                ->getEntityManager($this->contentTypeClass)
                ->getRepository($this->contentTypeClass)
                ->find($contentTypeId);
            $this->getSubject()->setContentType($contentType);
        }

        $this->formBuilderManager->buildContentForm($form->getFormBuilder(), $this->getSubject());
    }

    public function configureListFields(ListMapper $list)
    {
        $list
            ->add('id', null, ['label' => 'content.id'])
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
}
