<?php

namespace Drupal\practice\Controller;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Database;
use Drupal\Core\Url;


/**
* Class DisplayTableController.
*
* @package Drupal\mydata\Controller
*/
class DisplayTableController extends ControllerBase {

  /**
  * Display.
  *
  * @return string
  *   Return Hello string.
  */
  public function display() {
    //create table header
    $header_table = array(
      'id'=>    t('SrNo'),
      'name' => t('Name'),
      'mobilenumber' => t('MobileNumber'),
      'gender' => t('Gender'),
      'Address' => t('Addredd'),
      'Date' => t('Date'),
      'opt' => t('Delete'),
      'opt1' => t('Edit'),
    );
    //select records from table
    $query = \Drupal::database()->select('subscribers', 's');
    $query->fields('s', ['id','name','address','subscription_date','gender','phone']);
    $results = $query->execute()->fetchAll();
    $rows=array();
    foreach($results as $data){
      $delete = Url::fromUserInput('/subscriber/delete/'.$data->id);
      $edit   = Url::fromUserInput('/subscriber2/edit?num='.$data->id);
      //print the data from table
      $rows[] = array(
        'id' =>$data->id, // User id.
        'name' => $data->name,
        'mobilenumber' => $data->phone,
        'gender' => $data->gender,
        'Address' => $data->address,
        'Date' => $data->subscription_date,
        \Drupal::l('Delete', $delete),
        \Drupal::l('Edit', $edit),
      );
    }
    //display data in site
    $form['table'] = [
      '#type' => 'table',
      '#header' => $header_table,
      '#rows' => $rows,
      '#empty' => t('No users found'),
    ];
    return $form;
  }
}
