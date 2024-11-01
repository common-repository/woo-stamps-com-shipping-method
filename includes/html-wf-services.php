<tr valign="top" id="service_options" class="rates_tab_field">
	<td class="forminp" colspan="2" style="padding-left:0px">
	<strong><?php _e( 'Services', 'wf-usps-stamps-woocommerce' ); ?></strong><br/>
		<table class="usps_services widefat">
			<thead>
				<th class="sort">&nbsp;</th>
				<th><?php _e( 'Name', 'wf-usps-stamps-woocommerce' ); ?></th>
				<th><?php _e( 'PackageType(s)', 'wf-usps-stamps-woocommerce' ); ?></th>
				<th><?php echo sprintf( __( 'Price Adjustment (%s) <span class="wf-super">Premium</span>', 'wf-usps-stamps-woocommerce' ), get_woocommerce_currency_symbol() ); ?></th>
				<th><?php _e( 'Price Adjustment (%) <span class="wf-super">Premium</span> ', 'wf-usps-stamps-woocommerce' ); ?></th>
			</thead>
			<tbody>
				<?php
					$sort = 0;
					$this->ordered_services = array();
					if(empty($this->custom_services)){
						$this->custom_services = array();
					}
					
					foreach ( $this->services as $code => $values ) {

						if ( isset( $this->custom_services[ $code ]['order'] ) ) {
							$sort = $this->custom_services[ $code ]['order'];
						}

						while ( isset( $this->ordered_services[ $sort ] ) )
							$sort++;

						$this->ordered_services[ $sort ] = array( $code, $values );

						$sort++;
					}

					ksort( $this->ordered_services );

					foreach ( $this->ordered_services as $value ) {
						$code   = $value[0];
						$values = $value[1];
						if ( ! isset( $this->custom_services[ $code ] ) )
							$this->custom_services[ $code ] = array();
						?>
						<tr>
							<td class="sort">
								<input type="hidden" class="order" name="usps_service[<?php echo $code; ?>][order]" value="<?php echo isset( $this->custom_services[ $code ]['order'] ) ? $this->custom_services[ $code ]['order'] : ''; ?>" />
							</td>
							<td>
								<input type="text" name="usps_service[<?php echo $code; ?>][name]" placeholder="<?php echo $values['name']; ?> (<?php echo $this->title; ?>)" value="<?php echo isset( $this->custom_services[ $code ]['name'] ) ? $this->custom_services[ $code ]['name'] : ''; ?>" size="35" />
							</td>
							<td>
								<ul class="sub_services" style="font-size: 0.92em; color: #555">
									<?php foreach ( $values['package_types'] as $key => $name ) : ?>
									<li style="line-height: 23px;">
										<label>
										    <?php $checked = (! empty( $this->custom_services[ $code ][ $key ]['enabled'] ) )? true : false;
											  if(! isset( $this->custom_services[ $code ][ $key ]['enabled'] ) && $key  == 'Package') $checked = true;
										    ?>
											<input type="checkbox" name="usps_service[<?php echo $code; ?>][<?php echo $key; ?>][enabled]" <?php checked($checked , true ); ?> />
											<?php 
											$display_name = current(explode('.', $name));
											echo $display_name; ?>
										</label>
									</li>
									<?php endforeach; ?>
								</ul>
							</td>
							<td>
								<ul class="sub_services" style="font-size: 0.92em; color: #555">
									<?php foreach ( $values['package_types'] as $key => $name ) : ?>
									<li>
										<?php echo get_woocommerce_currency_symbol(); ?><input type="text" name="usps_service[<?php echo $code; ?>][<?php echo $key; ?>][adjustment]" placeholder="N/A" size="4" disabled/>
									</li>
									<?php endforeach; ?>
								</ul>
							</td>
							<td>
								<ul class="sub_services" style="font-size: 0.92em; color: #555">
									<?php foreach ( $values['package_types'] as $key => $name ) : ?>
									<li>
										<input type="text" name="usps_service[<?php echo $code; ?>][<?php echo $key; ?>][adjustment_percent]" placeholder="N/A" size="4" disabled />%
									</li>
									<?php endforeach; ?>
								</ul>
							</td>
						</tr>
						<?php
					}
				?>
			</tbody>
		</table>
	</td>
</tr>