<?php

namespace App\Application\Controller\Admin;

use App\Domain\User\Model\Enum\UserRole;
use App\Infrastructure\Admin\Field\EnumField;
use App\Infrastructure\Doctrine\Model\DoctrineUser;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

//TODO : add tests
class DoctrineUserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return DoctrineUser::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return parent::configureCrud($crud)
            ->setEntityLabelInSingular('User')
            ->setEntityLabelInPlural('Users')
            ->setSearchFields(['id', 'name', 'email'])
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('name'),
            TextField::new('email'),
            EnumField::new('role')->setEnumClass(UserRole::class),
            TextField::new('plainPassword')->onlyOnForms(),
        ];
    }
}
