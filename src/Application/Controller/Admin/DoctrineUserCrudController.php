<?php

namespace App\Application\Controller\Admin;

use App\Domain\User\Action\CreateUserInput;
use App\Domain\User\Action\DeleteUserInput;
use App\Domain\User\Action\EditUserInput;
use App\Domain\User\Model\Enum\UserRole;
use App\Infrastructure\Admin\Field\EnumField;
use App\Infrastructure\Doctrine\Mapper\User\DoctrineUserMapper;
use App\Infrastructure\Doctrine\Model\User\DoctrineUser;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Messenger\MessageBusInterface;

class DoctrineUserCrudController extends AbstractCrudController
{
    public function __construct(
        private readonly MessageBusInterface $messageBus,
        private readonly DoctrineUserMapper $doctrineUserMapper,
    ) {}

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
            ->setFormOptions(
                ['validation_groups' => [Action::NEW]],
                ['validation_groups' => [Action::EDIT]],
            )
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


    /** @param DoctrineUser $entityInstance */
    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $createUserInput = $this->doctrineUserMapper->toDomainDto($entityInstance, CreateUserInput::class);
        $this->messageBus->dispatch($createUserInput);
    }

    /** @param DoctrineUser $entityInstance */
    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $editUserInput = $this->doctrineUserMapper->toDomainDto($entityInstance, EditUserInput::class);
        $this->messageBus->dispatch($editUserInput);
    }

    /** @param DoctrineUser $entityInstance */
    public function deleteEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $deleteUserInput = $this->doctrineUserMapper->toDomainDto($entityInstance, DeleteUserInput::class);
        $this->messageBus->dispatch($deleteUserInput);
    }
}
