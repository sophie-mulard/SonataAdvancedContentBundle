<?php

namespace Sherlockode\SonataAdvancedContentBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class PageTypeAdmin extends AbstractAdmin
{
    public function configureFormFields(FormMapper $form)
    {
        $form
            ->add('name', TextType::class, ['label' => 'page_type.form.name'])
        ;
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
}
