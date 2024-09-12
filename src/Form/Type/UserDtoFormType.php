<?php

namespace App\Form\Type;

use function Symfony\component\string\u;

use App\Messenger\Command\Message\OnlyWeekendsOfThisMonth;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Translation\TranslatableMessage;
use Symfony\Component\Form\ChoiceList\Loader\CallbackChoiceLoader;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\ChoiceList\ChoiceList;
use App\Doctrine\DTO\UserDto;
use App\Repository\AvatarRepository;
use Symfony\Component\Validator\Constraints;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\Form\FormEvents;
use App\Entity\Media\Image;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type as FormType;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

class UserDtoFormType extends AbstractFormType
{
    public function __construct(
        PropertyAccessorInterface $pa,
        private readonly string $absPublicDir,
        private readonly AvatarRepository $avatarRepo,
        private $get,
    ) {
        parent::__construct(
            pa: $pa,
        );
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'id',
                options: [
                    //'empty_data' => [],
                    //'required' => true,
                    'translation_domain' => 'app.form',
                    'block_prefix' => 'user_dto_id',
                ],
            )
            ->add(
                'name',
                options: [
                    'translation_domain' => 'app.form',
                    //'empty_data' => [],
                    //'required' => true,
                ],
            )
            ->add(
                'age',
                options: [
                    'translation_domain' => 'app.form',
                    //'empty_data' => [],
                    //'required' => true,
                ],
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UserDto::class,
            'label_format' => '%name%',
            'translation_domain' => 'app.form',
            //'empty_data' => null,
            'block_prefix' => 'user_dto',
        ]);
    }
}
