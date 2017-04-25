<?php


 ?>

 <div class="wrap">
   <h1>Settings</h1>
   <form method="post" action="options.php">


     <?php settings_fields( 'dm-zoho-group' ); ?>
     <?php do_settings_sections( 'dm-zoho-group' ); ?>



     <h2>General Settings</h2>

     <table class="form-table dm-settings-form">
             <tr valign="top">
               <th scope="row">Zoho Api Key</th>
               <td><input type="text" name="dm_zoho_api_key" value="<?php echo esc_attr( get_option( 'dm_zoho_api_key' ) ); ?>" /></td>
             </tr>


         </table>

     <h2>Customer Module Settings</h2>


     <table class="form-table dm-settings-form">
			        <tr valign="top">
  			        <th scope="row">Lead Source</th>
  			        <td><input type="text" name="dm_customer_lead_source" value="<?php echo esc_attr( get_option( 'dm_customer_lead_source' ) ); ?>" /></td>
			        </tr>

              <tr valign="top">
                <th scope="row">Lead Lead Wait Time (minutes)<br />
                  <small>How much to wait before creating a lead in Zoho</small>
                </th>
                <td><input type="text" name="dm_customer_lead_wait_time" value="<?php echo esc_attr( get_option( 'dm_customer_lead_wait_time' ) ); ?>" /></td>
              </tr>

              <tr valign="top">
                <th scope="row">Second Step Page ID<br />
                </th>
                <?php
                $dm_customer_second_step_id = esc_attr( get_option( 'dm_customer_second_step_id' ) );
                ?>
                <td>
                  <input type="text" name="dm_customer_second_step_id" value="<?php echo $dm_customer_second_step_id; ?>" />
                  <?php
                  if ($dm_customer_second_step_id != '') {
                    echo '<small>See it here: <a href="' . get_permalink( $dm_customer_second_step_id ) . '" target="_blank">' . get_the_title( $dm_customer_second_step_id ) . '</a></small>';
                  }
                  ?>
                </td>
              </tr>

              <tr valign="top">
                <th scope="row">Third Step Page ID<br />
                  <!-- <small>Ho much to wait before creating a lead in Zoho</small> -->
                </th>
                <?php
                $dm_customer_third_step_id = esc_attr( get_option( 'dm_customer_third_step_id' ) );
                ?>
                <td>
                  <input type="text" name="dm_customer_third_step_id" value="<?php echo $dm_customer_third_step_id; ?>" />
                  <?php
                  if ($dm_customer_third_step_id != '') {
                    echo '<small>See it here: <a href="' . get_permalink( $dm_customer_third_step_id ) . '" target="_blank">' . get_the_title( $dm_customer_third_step_id ) . '</a></small>';
                  }
                  ?>
                </td>
              </tr>

              <tr valign="top">
                <th scope="row">Quote Generated Page ID<br />
                  <!-- <small>Ho much to wait before creating a lead in Zoho</small> -->
                </th>
                <?php
                $dm_customer_quote_generated_id = esc_attr( get_option( 'dm_customer_quote_generated_id' ) );
                ?>
                <td>
                  <input type="text" name="dm_customer_quote_generated_id" value="<?php echo $dm_customer_quote_generated_id; ?>" />
                  <?php
                  if ($dm_customer_quote_generated_id != '') {
                    echo '<small>See it here: <a href="' . get_permalink( $dm_customer_quote_generated_id ) . '" target="_blank">' . get_the_title( $dm_customer_quote_generated_id ) . '</a></small>';
                  }
                  ?>
                </td>
              </tr>

              <tr valign="top">
                <th scope="row">Quote Terms and Conditions</th>
                <td>
                  <textarea type="text" name="dm_customer_quote_terms" /><?php echo esc_attr( get_option( 'dm_customer_quote_terms' ) ); ?></textarea>
                </td>
              </tr>


			    </table>



          	<?php submit_button(); ?>


<style>
.dm-settings-form input {
  width: 100%;
}
.dm-settings-form textarea {
  width: 100%;
  min-height: 250px;
}

</style>

   </form>
</div>
