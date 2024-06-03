<?php

namespace App\Application\Controller\Admin\Score;

use App\Domain\Score\Action\ScoreCategory\CreateScoreCategoryInput;
use App\Domain\Score\Action\ScoreCategory\DeleteScoreCategoryInput;
use App\Domain\Score\Action\ScoreCategory\UpdateScoreCategoryInput;
use App\Domain\Score\Model\Enum\ScoreCategoryType;
use App\Domain\User\Action\CreateUserInput;
use App\Domain\User\Action\DeleteUserInput;
use App\Domain\User\Action\EditUserInput;
use App\Domain\User\Model\Enum\UserRole;
use App\Infrastructure\Admin\Field\EnumField;
use App\Infrastructure\Doctrine\Mapper\Score\DoctrineScoreCategoryMappper;
use App\Infrastructure\Doctrine\Mapper\User\DoctrineUserMapper;
use App\Infrastructure\Doctrine\Model\Score\DoctrineScoreCategory;
use App\Infrastructure\Doctrine\Model\User\DoctrineUser;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Messenger\MessageBusInterface;

class DoctrineScoreCategoryCrudController extends AbstractCrudController
{
    public function __construct(
        private readonly MessageBusInterface          $messageBus,
        private readonly DoctrineScoreCategoryMappper $doctrineMapper,
    ) {}

    public static function getEntityFqcn(): string
    {
        return DoctrineScoreCategory::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return parent::configureCrud($crud)
            ->setEntityLabelInSingular('Category')
            ->setEntityLabelInPlural('Categories')
            ->setSearchFields(['id', 'name', 'type'])
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
            EnumField::new('type')->setEnumClass(ScoreCategoryType::class),
            TextField::new('description'),
        ];
    }


    /** @param DoctrineScoreCategory $entityInstance */
    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $createScoreCategoryInput = $this->doctrineMapper->toDomainDto($entityInstance, CreateScoreCategoryInput::class);
        $this->messageBus->dispatch($createScoreCategoryInput);
    }

    /** @param DoctrineScoreCategory $entityInstance */
    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $editScoreCategoryInput = $this->doctrineMapper->toDomainDto($entityInstance, UpdateScoreCategoryInput::class);
        $this->messageBus->dispatch($editScoreCategoryInput);
    }

    /** @param DoctrineScoreCategory $entityInstance */
    public function deleteEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $deleteScoreCategoryInput = $this->doctrineMapper->toDomainDto($entityInstance, DeleteScoreCategoryInput::class);
        $this->messageBus->dispatch($deleteScoreCategoryInput);
    }
}
