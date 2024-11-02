<?php

namespace App\Form\Field\Configurator;

use EasyCorp\Bundle\EasyAdminBundle\Field\AvatarField;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldConfiguratorInterface;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\FieldDto;
use App\Form\Field\PrefixedAvatarField;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class AvatarFieldConfigurator implements FieldConfiguratorInterface
{
	public function __construct(
		#[Autowire('%app.public_img_dir%')]
		private readonly string $publicImgDir,
	) {}
	
	public function supports(FieldDto $field, EntityDto $entityDto): bool {
		return AvatarField::class === $field->getFieldFqcn();
	}

    public function configure(FieldDto $field, EntityDto $entityDto, AdminContext $context): void {
		$field->setCustomOption('public_avatar_prefix', $this->publicImgDir);
	}
}