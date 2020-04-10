<?php

namespace Sherlockode\SonataAdvancedContentBundle\Admin;

use Sherlockode\AdvancedContentBundle\Form\Type\PageTypeType;
use Sherlockode\AdvancedContentBundle\Model\PageTypeInterface;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\FormBuilderInterface;

class PageTypeAdmin extends AbstractAdmin
{
    public function configureFormFields(FormMapper $form)
    {
        $form
            ->add('name')
        ;
    }

    private function getPageTypeFormBuilder()
    {
        return $this->getFormContractor()->getFormFactory()
            ->createNamedBuilder($this->getUniqid(), PageTypeType::class);
    }

    public function getFormBuilder()
    {
        $formBuilder = $this->getPageTypeFormBuilder();
        $this->defineFormBuilder($formBuilder);

        return $formBuilder;
    }

    public function defineFormBuilder(FormBuilderInterface $formBuilder)
    {
        $formBuilder = $this->getPageTypeFormBuilder();
        parent::defineFormBuilder($formBuilder);
    }

    public function configureListFields(ListMapper $list)
    {
        $list
            ->add('name', null, ['label' => 'page_type.form.name'])
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
        if ($object instanceof PageTypeInterface && $object->getName()) {
            return $object->getName();
        }

        return parent::toString($object);
    }
}
