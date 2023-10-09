<?php

namespace App\Form;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Doctrine\ORM\EntityManagerInterface;

class EditProductType extends AbstractType
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
        
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $productId = $options['product_id'];
        $currentProduct = $this->entityManager->find(Product::class, $productId);
        $currentCategory = $currentProduct->getCategory();

        $conn = $this->entityManager->getConnection();
        $sql = "SELECT DISTINCT(category) FROM product ORDER BY category ASC";
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery()->fetchAllAssociative();

        $categories = [];
        foreach ($resultSet as $key => $value) {
            $categories[] = $value['category'];
        }

        $choices = [$currentCategory => $currentCategory];

        foreach ($categories as $key => $value) {
            if ($value !== $currentCategory) {
                $choices += [$value => $value];
            }
        }
        
        $builder
            ->add('name')
            ->add('category', ChoiceType::class, [
                'choices'  => $choices,
            ])
            ->add('price')
            ->add('unit')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);

        $resolver->setRequired([
            'product_id',
        ]);
    }
}
