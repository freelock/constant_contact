<?php

namespace Drupal\constant_contact\Form;

use Drupal\Core\Entity\EntityDeleteForm;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Entity\Query\QueryFactory;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a confirmation form for deleting an Account type entity.
 */
class AccountDeleteForm extends EntityDeleteForm {

  /**
   * The EntityTypeManager
   *
   * @var EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Constructs a new AccountDeleteForm object.
   *
   * @param \Drupal\Core\Entity\Query\QueryFactory $query_factory
   *    The entity query object.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager) {
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
//    $num_profiles = $this->entityTypeManager->getStorage('profile')->getQuery()
//      ->condition('type', $this->entity->id())
//      ->count()
//      ->execute();
//    if ($num_profiles) {
//      $caption = '<p>' . \Drupal::translation()
//          ->formatPlural($num_profiles, '%type is used by 1 profile on your site. You can not remove this profile type until you have removed all of the %type profiles.', '%type is used by @count profiles on your site. You may not remove %type until you have removed all of the %type profiles.', ['%type' => $this->entity->label()]) . '</p>';
//      $form['#title'] = $this->entity->label();
//      $form['description'] = ['#markup' => $caption];
//      return $form;
//    }

    return parent::buildForm($form, $form_state);
  }

}
