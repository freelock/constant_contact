<?php

namespace Drupal\constant_contact\Form;

use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Ctct\ConstantContact;
use CtCt\Components\Account\AccountInfo;
use Ctct\Components\Contacts\ContactList;
use Drupal\constant_contact\AccountInterface;
use Drupal\Core\Url;
use Drupal\constant_contact\ConstantContactManager;

/**
 * Form for deleting an image effect.
 */
class ContactDeleteForm extends ConfirmFormBase {

  /**
   *
   *
   * @var \Drupal\constant_contact\AccountInterface
   */
  protected $account;


  protected $contact;

  /**
   * {@inheritdoc}
   */
  public function getQuestion() {
    return $this->t('Are you sure you want to delete @user from the %account account?', [
      '@user' => $this->contact->email_addresses[0]->email_address,
      '%account' => $this->account->label()
    ]);
  }

  /**
   * {@inheritdoc}
   */
  public function getConfirmText() {
    return $this->t('Delete');
  }

  /**
   * {@inheritdoc}
   */
  public function getCancelUrl() {
    return new Url('constant_contact.contacts.collection', ['constant_contact_account' => $this->account->id()]);
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'contact_delete_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, AccountInterface $constant_contact_account = NULL, $id = NULL) {
    $this->account = $constant_contact_account;
    $this->contact = \Drupal::service('constant_contact.manager')->getContact($constant_contact_account, $id);

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    \Drupal::service('constant_contact.manager')->deleteContact($this->account, $this->contact->id);

    \Drupal::cache(ConstantContactManager::CC_CACHE_BIN)->delete('constant_contact:contacts:' . $this->account->id());

    $this->logger('constant_contact')->info('Contact list: %label deleted by %user', [
      '%label' => $this->contact->id,
      '%user' => \Drupal::currentUser()->getAccountName(),
    ]);
    drupal_set_message($this->t('The Contact list %name has been deleted.', array('%name' => $this->contact->id)));

    $form_state->setRedirect('constant_contact.contact_list.collection', ['constant_contact_account' => $this->account->id()]);
  }

}
