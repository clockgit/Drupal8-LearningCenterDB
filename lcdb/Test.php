<?php
/**
 * Perform alterations before an entity form is included in the IEF widget.
 *
 * @param $entity_form
 *   Nested array of form elements that comprise the entity form.
 * @param $form_state
 *   The form state of the parent form.
 */
function lcdb_inline_entity_form_entity_form_alter(&$entity_form,FormStateInterface &$form_state) {
    //adding kids_data asgt
  if ($entity_form['#entity_type'] == 'kids_data' && $entity_form['#bundle'] == 'asgt' && $entity_form['#op'] == 'add' ) {
    ksm($form_state->getTemporary());
    $student = $form_state->getTemporaryValue('student');
    $entityManager = Drupal::service('entity_type.manager');
    if(is_numeric($student) && $studentEntity = $entityManager->getStorage('kids_data')->load($student)) {
      // loop over all fields in asgt get value from default and set in asgt
      foreach ($entity_form as $asgt_field => $value) {
        if (!(strpos($asgt_field, '#') === 0) && !(strpos($asgt_field, 'group') === 0)) {
          if( $studentEntity->hasfield($asgt_field)){
            //is the value set in #defautl_value or [0]
            if (isset($entity_form[$asgt_field]['widget']['#default_value'])) {
              foreach ($studentEntity->get($asgt_field)->getValue() as $value) {
                $entity_form[$asgt_field]['widget']['#default_value'][] = $value['value'];
              }
            }
            else {
              $i = 0;
              foreach ($studentEntity->get($asgt_field)->getValue() as $value) {
                if ($entity_form[$asgt_field]['widget'][0]['value']['#type'] === 'datetime') {
                  $entity_form[$asgt_field]['widget'][$i++]['value']['#default_value'] = new DrupalDateTime($value['value']);
                }
                else {
                  $entity_form[$asgt_field]['widget'][$i++]['value']['#default_value'] = $value['value'];
                }
              }
            }
          }
        }
      }
    }
  }
}


/**
 * Perform alterations to the IEF field type settings.
 *
 * This allows modules to enable IEF to work on custom field types.
 *
 * @param $settings
 *   An array with the following keys:
 *   - entity_type - The entity_type being managed.
 *   - bundles - Bundles of entities that the user is allowed to create.
 *   - column - The name of the ref. field column that stores the entity id.
 * @param $field
 *   The field array of the reference field.
 * @param $instance
 *   The instance array of the reference field.
 */
#function hook_inline_entity_form_settings_alter(&$settings, $field, $instance) {}


/**
 * Perform alterations before the reference form is included in the IEF widget.
 *
 * The reference form is used to add existing entities through an autocomplete
 * field
 *
 * @param $reference_form
 *   Nested array of form elements that comprise the reference form.
 * @param $form_state
 *   The form state of the parent form.
 */
/*function lcdb_inline_entity_form_reference_form_alter(&$reference_form, &$form_state) {
  ksm(['ref_form'=>$reference_form, 'fs'=>$form_state]);

}*/


/*function lcdb_element_info_alter(array &$info) {
  if (isset($info['table'])) {
    $info['table']['#attached']['library'][] = 'lcdb/inline_fields';
  }
}*/


/*function computed_field_field_fte_minutes_compute($entity_type_manager, $entity, array $fields, $delta) {
  $return = (float)round(($fields['field_count_day_1_minutes'][$delta]['value'] + $fields['field_count_day_2_minutes'][$delta]['value'])/2,1);
  if($return >= .1) {
    return $return;
  }else {
    return NULL;
  }
}


function computed_field_field_count_fte_compute($entity_type_manager, $entity, $fields, $delta) {
  $return = (float)round(($fields['field_count_day_1_minutes'][$delta]['value'] + $fields['field_count_day_2_minutes'][$delta]['value'])/2/360,1);
  if($return >= .1) {
    return $return;
  }else {
    return NULL;
  }
}*/