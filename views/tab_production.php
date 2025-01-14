<div id="production_deploy" v-show="currentTab == 'production_deploy'">

<section class="wp2static-content wp2static-flex">
  <div class="content" style="max-width:30%">
    <h2><?php echo __( 'Where will you host the optimized version of your site?', 'static-html-output-plugin' ); ?></h2>
  </div>

  <div class="content">
    <select id="selected_deployment_method_production" v-model="currentDeploymentMethodProduction" name="currentDeploymentMethodProduction">
        <?php generateDeploymentMethodOptionsProduction(); ?>
    </select>
   </div>
</section>

<?php
  // load up each deployment settings block
  $deployment_option_templates = array(
      __DIR__ . '/folder_settings_block_production.php',
      __DIR__ . '/zip_settings_block_production.php',
  );

  $deployment_option_templates = apply_filters(
      'wp2static_load_deploy_option_template_production',
      $deployment_option_templates
  );

  foreach ( $deployment_option_templates as $deployment_option_template ) {
      require_once $deployment_option_template;
  }

    ?>
</div> <!-- end export_your_site -->
