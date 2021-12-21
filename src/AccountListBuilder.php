<?php

/**
 * @file
 * Contains \Drupal\profile\ProfileListController.
 */

namespace Drupal\constant_contact;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Config\Entity\ConfigEntityListBuilder;
use Drupal\Core\Url;
use Drupal\Core\Config\Entity\DraggableListBuilder;
use Drupal\Core\Form\FormStateInterface;

/**
 * List controller for profiles.
 *
 * @see \Drupal\profile\Entity\Profile
 */
class AccountListBuilder extends ConfigEntityListBuilder {

  /**
   * {@inheritdoc}
   */
  protected $entitiesKey = 'constant_contact_accounts';

  /**
   * Returns a unique string identifying the form.
   *
   * @return string
   *   The unique string identifying the form.
   */
  public function getFormId() {
    return 'constant_contact_accounts_overview';
  }

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['api_key'] = $this->t('API key');
    $header['label'] = $this->t('Application');
    $header['secret'] = $this->t('Secret');
    $header['access_token'] = $this->t('Access token  ');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    $row['api_key'] = $entity->getApiKey();
    // TODO: Drupal Rector Notice: Please delete the following comment after you've made any necessary changes.
    // Please confirm that `$entity` is an instance of `\Drupal\Core\Entity\EntityInterface`. Only the method name and not the class name was checked for this replacement, so this may be a false positive.
    $row['label'] = $entity->toLink()->toString();
    $row['secret'] = $entity->getSecret();
    $row['access_token'] = $entity->getAccessToken();

    return $row + parent::buildRow($entity);
  }

  /**
   * {@inheritdoc}
   */
  public function getOperations(EntityInterface $entity) {
    $operations = parent::getOperations($entity);
    // Place the edit operation after the operations added by field_ui.module
    // which have the weights 15, 20, 25.
    if (isset($operations['edit'])) {
      $operations['edit'] = [
        'title' => t('Edit'),
        'weight' => 30,
        'url' => $entity->toUrl('edit-form'),
      ];
    }
    if (isset($operations['delete'])) {
      $operations['delete'] = [
        'title' => t('Delete'),
        'weight' => 35,
        'url' => $entity->toUrl('delete-form'),
      ];
    }
    // Sort the operations to normalize link order.
    uasort($operations, [
      'Drupal\Component\Utility\SortArray',
      'sortByWeightElement',
    ]);

    return $operations;
  }

  /**
   * {@inheritdoc}
   */
  public function render() {
    $entities = $this->load();
    // If there are not multiple account, disable dragging by unsetting the
    // weight key.
    if (count($entities) <= 1) {
      unset($this->weightKey);
    }
    $build = parent::render();
    $build['table']['#empty'] = $this->t('No constant contact accounts. <a href=":link">Add account</a>.', [
      ':link' => Url::fromRoute('entity.constant_contact_account.add_form')->toString()
    ]);
    return $build;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    $this->messenger()->addStatus($this->t('The Constant Contact account ordering has been saved.'));
  }
}
