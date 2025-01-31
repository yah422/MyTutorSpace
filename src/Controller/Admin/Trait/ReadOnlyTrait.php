<?php

namespace App\Controller\Admin\Trait;

use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;

trait ReadOnlyTrait {
    public function configureAction(): Actions {

        $actions = parent::configureAction();
        $actions
            ->disable(Action::NEW, Action::EDIT, Action::DELETE)
            -> add(Crud::PAGE_INDEX, Action::DETAIL);
        return $actions;
    }
}