<?php
namespace Drupal\practice\Form;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;
use Symfony\Component\HttpFoundation\RedirectResponse;
/**
 * Class MydataForm.
 *
 * @package Drupal\mydata\Form
 */
class subscriberData2 extends FormBase {
/**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'subscriber2_edit_form';
  }
  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $conn = Database::getConnection();
     $record = array();
    if (isset($_GET['num'])) {
        $query = $conn->select('subscribers', 's')
            ->condition('id', $_GET['num'])
            ->fields('s');
        $record = $query->execute()->fetchAssoc();
    }
  $form['candidate_name'] = array(
      '#type' => 'textfield',
      '#title' => t('Candidate Name:'),
      '#required' => TRUE,
       //'#default_values' => array(array('id')),
      '#default_value' => (isset($record['name']) && $_GET['num']) ? $record['name']:'',
      );

    $form['candidate_number'] = array(
      '#type' => 'textfield',
      '#title' => t('Mobile Number:'),
      '#default_value' => (isset($record['phone']) && $_GET['num']) ? $record['phone']:'',
      );
    $form['address'] = array(
      '#type' => 'textfield',
      '#title' => t('Address'),
      '#required' => TRUE,
      '#default_value' => (isset($record['address']) && $_GET['num']) ? $record['address']:'',
      );
     $dob = explode(" ",$record['subscription_date']);
     $form['candidate_dob'] = array (
      '#type' => 'textfield',
      '#title' => t('DOB'),
      '#required' => TRUE,
      '#default_value' => (isset($record['subscription_date']) && $_GET['num']) ? $dob[0]:'',

    );

    $form['candidate_gender'] = array (
      '#type' => 'select',
      '#title' => ('Gender'),
      '#options' => array(
        'Female' => t('Female'),
        'male' => t('Male'),
        '#default_value' => (isset($record['gender']) && $_GET['num']) ? $record['gender']:'',
        ),
      );
    $form['submit'] = [
        '#type' => 'submit',
        '#value' => 'save',
        //'#value' => t('Submit'),
    ];
    return $form;
  }
  /**
    * {@inheritdoc}
    */
  public function validateForm(array &$form, FormStateInterface $form_state) {
          $name = $form_state->getValue('candidate_name');
          if(!preg_replace ("/[^a-zA-Z0-9 ]/", "", $name)) {
             $form_state->setErrorByName('candidate_name', $this->t('your name must in characters without space'));
          }


      $phone = $form_state->getValue('candidate_number');
      $dob = $form_state->getValue('candidate_dob');

    if (!preg_match("/^[0-9]{10}$/", $phone)) {
    $form_state->setErrorByName('candidate_number', $this->t('Mobile number must be numeric of 10 digits. '));
    }
     if (!preg_match("/^[0-9]{4}+-[0-9]{2}+-[0-9]{2}$/", $dob)) {
    $form_state->setErrorByName('candidate_dob', $this->t('DOB must be in the "2018-04-06/Y-M-D" format. '));
    }
    parent::validateForm($form, $form_state);
  }
  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $field=$form_state->getValues();
    $name=$field['candidate_name'];
    //echo "$name";
    $number=$field['mobile_number'];
    $address=$field['address'];
    $dob=$field['candidate_dob'];
    $gender=$field['candidate_gender'];
    if (isset($_GET['num'])) {
          $field  = array(
              'name'   => $name,
              //'mobilenumber' =>  $number,
              'address' =>  $address,
              'gender' => $gender,
              'subscription_date' => $dob,
          );
          $query = \Drupal::database();
          $query->update('subscribers')
              ->fields($field)
              ->condition('id', $_GET['num'])
              ->execute();
          drupal_set_message("succesfully updated");
          $form_state->setRedirect('practice.data_display');
      }

     }
}
