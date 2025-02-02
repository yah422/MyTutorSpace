<?php

namespace App\Controller\Admin;

use App\Entity\Lecon;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class LeconCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Lecon::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('titre'),
            TextField::new('pdf_path'),
            TextEditorField::new('description'),
        ];
    }

}
