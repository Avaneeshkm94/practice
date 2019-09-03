<?php
/**
* @file
* Contains \Drupal\resume\Form\ResumeForm.
*/
namespace Drupal\practice\Form;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Element;


 class SubscriberForm extends FormBase {
/**
* {@inheritdoc}
*/
  public function getFormId() {
   return 'resume_form';
 }

  public function buildForm(array $form, FormStateInterface $form_state) {
   $form['candidate_name'] = array(
   '#type' => 'textfield',
   '#title' => t('Candidate Name:'),
   '#required' => TRUE,
 );
  $form['candidate_mail'] = array(
  '#type' => 'textfield',
  '#title' => t('Email ID:'),
  '#required' => TRUE,
 );
  $form['address'] = array (
  '#type' => 'textarea',
  '#title' => ('Your Address'),
  '#required' => TRUE,
);
  $form['candidate_number'] = array (
  '#type' => 'textfield',
  '#title' => t('Mobile no'),
  '#required' => TRUE,
 );
  $form['candidate_dob'] = array (
  '#type' => 'date',
  '#title' => $this->t('DOB'),
  '#required' => TRUE,
 );
  $form['candidate_gender'] = array (
  '#type' => 'select',
  '#title' => ('Gender'),
  '#options' => array(
  'Female' => t('Female'),
  'male' => t('Male'),
 ),
  '#required' => TRUE,
 );

  $form['actions']['#type'] = 'actions';
  $form['actions']['submit'] = array(
  '#type' => 'submit',
  '#value' => $this->t('Save'),
  '#button_type' => 'primary',
 );
  return $form;
 }

  public function validateForm(array &$form, FormStateInterface $form_state) {
   $email = $form_state->getValue('candidate_mail');
   $phone = $form_state->getValue('candidate_number');
   $dob = $form_state->getValue('candidate_dob');
   $name = $form_state->getValue('candidate_name');

  // if(!preg_match('/^[A-Z][a-z-]+[a-z]+([\s][A-Z][a-z-]+[a-z])?$/', $name))
  if(!preg_match('/^[a-zA-Z-][a-z-]+[a-z]+([\s][a-zA-Z-][a-z-]+[a-zA-Z-])?$/', $name))
 {
  $form_state->setErrorByName('candidate_name', $this->t('your name must in characters without space'));
 }
  if (!preg_match("/^[a-zA-Z0-9._-]+@[a-zA-Z0-9-]+\.[a-zA-Z.]{2,5}$/", $email)) {

  $form_state->setErrorByName('candidate_mail', $this->t('Email is not valid.'));
 }
  if (!preg_match("/^[0-9]{10}$/", $phone)) {
  $form_state->setErrorByName('candidate_number', $this->t('Mobile number must be numeric of 10 digits. '));
 }
  if (!preg_match("/^[0-9]{4}+-[0-9]{2}+-[0-9]{2}$/", $dob)) {
  $form_state->setErrorByName('candidate_dob', $this->t('DOB must be in the "2018-04-06/Y-M-D" format. '));
 }
}


/**
* {@inheritdoc}
*/
  public function submitForm(array &$form, FormStateInterface $form_state) {
  $address = $form_state->getValue('address');
  $phone = $form_state->getValue('candidate_number');
  $dob = $form_state->getValue('candidate_dob');
  $name = $form_state->getValue('candidate_name');
  $gender = $form_state->getValue('candidate_gender');

//insert to subscriber table
  \Drupal::database()->insert('subscribers')
     ->fields(['name','address','subscription_date','gender','phone'])
     ->values(array($name,$address,$dob,$gender,$phone,))
     ->execute();
}
}
