<?php

/**
 * @file
 * Post-update hooks.
 */

/**
 * Remove "enforced" dependency on this module from installed config.
 */
function islandora_defaults_post_update_remove_enforced_dependency() {
  // XXX: Notably absent from this list is the migration defined by the
  // migrate_plus.migration.islandora_defaults_tags config entity; however,
  // given this migration depends on content that is explicitly part of this
  // module (the CSV from which it takes its data), it would leave it in an
  // inconsistent state if we were to leave the entity intact.
  $targets = [
    'context.context.binary',
    'context.context.collection',
    'context.context.collection_metadata',
    'context.context.repository_content',
    'core.entity_view_display.node.islandora_object.collection_search',
    'core.entity_form_display.node.islandora_object.default',
    'core.entity_view_display.media.file.open_seadragon',
    'core.entity_view_display.media.image.open_seadragon',
    'core.entity_view_display.node.islandora_object.binary',
    'core.entity_view_display.node.islandora_object.collection',
    'core.entity_view_display.node.islandora_object.default',
    'core.entity_view_display.node.islandora_object.open_seadragon',
    'core.entity_view_display.node.islandora_object.pdfjs',
    'core.entity_view_display.node.islandora_object.teaser',
    'core.entity_view_mode.media.open_seadragon',
    'core.entity_view_mode.node.binary',
    'core.entity_view_mode.node.collection',
    'core.entity_view_mode.node.open_seadragon',
    'field.field.node.islandora_object.field_access_terms',
    'field.field.node.islandora_object.field_description',
    'field.field.node.islandora_object.field_display_hints',
    'field.field.node.islandora_object.field_member_of',
    'field.field.node.islandora_object.field_model',
    'migrate_plus.migration.islandora_defaults_00_tags',
    'migrate_plus.migration.islandora_defaults_01_rights',
    'migrate_plus.migration.islandora_defaults_02_access',
    'migrate_plus.migration.islandora_defaults_03_languages',
    'migrate_plus.migration.islandora_defaults_04_physical_form',
    'migrate_plus.migration.islandora_defaults_05_genre',
    'migrate_plus.migration.islandora_defaults_06_continent',
    'migrate_plus.migration.islandora_defaults_07_country',
    'migrate_plus.migration.islandora_defaults_08_province',
    'migrate_plus.migration.islandora_defaults_09_region',
    'migrate_plus.migration.islandora_defaults_10_county',
    'migrate_plus.migration.islandora_defaults_11_city',
    'migrate_plus.migration.islandora_defaults_12_city_section',
    'migrate_plus.migration.islandora_defaults_13_corporate_body',
    'migrate_plus.migration.islandora_defaults_14_person',
    'migrate_plus.migration.islandora_defaults_15_subjects',
    'migrate_plus.migration.islandora_defaults_16_nrtee_wiaww_corp',
    'migrate_plus.migration.islandora_defaults_17_nrtee_wiaww_person',
    'migrate_plus.migration.islandora_defaults_18_act_corp',
    'migrate_plus.migration.islandora_defaults_19_act_person',
    'node.type.islandora_object',
    'rdf.mapping.node.islandora_object',
    'views.view.openseadragon_media_evas',
    'views.view.pdfjs_media_evas',
  ];

  $diff_set = ['islandora_defaults'];

  /** @var \Drupal\Core\Config\ConfigFactoryInterface $config_factory */
  $config_factory = \Drupal::service('config.factory');
  foreach ($targets as $target) {
    $editable_target = $config_factory->getEditable($target);
    if ($editable_target->isNew()) {
      // We do not want to _create_ any of the configs, just change them if they
      // exist, so if it turns out that we would be creating the config, skip
      // it.
      continue;
    }

    $current_set = $editable_target->get('dependencies.enforced.module');
    $post_diff = array_diff($current_set, $diff_set);
    if ($post_diff !== $current_set) {
      $editable_target->set('dependencies.enforced.module', $post_diff);
      $editable_target->save();
    }
  }
}
