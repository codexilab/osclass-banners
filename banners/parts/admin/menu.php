<div class="drop">
	<ul class="drop_menu">
		<li><a href="<?php echo osc_route_admin_url("banners-admin-positions"); ?>">1 - <?php _e("Positions", BANNERS_PREF); ?> &raquo;</a>
			<ul>
				<li><a href="#" onclick="set_position();return false;"><?php _e("Add new position", BANNERS_PREF); ?></a></li>
				<li><a href="<?php echo osc_route_admin_url("banners-admin-positions"); ?>"><?php _e("View all positions", BANNERS_PREF); ?> (<?php echo (banners_positions_total() > 9999) ? '+9999' : banners_positions_total(); ?>)</a></li>
			</ul>
		</li>
		<li><a href="<?php echo osc_route_admin_url("banners-admin-advertisers"); ?>">2 - <?php _e("Advertisers", BANNERS_PREF); ?> &raquo;</a>
			<ul>
				<li><a href="#" onclick="set_advertiser();return false;"><?php _e("Add new advertiser", BANNERS_PREF); ?></a></li>
				<li><a href="<?php echo osc_route_admin_url("banners-admin-advertisers"); ?>"><?php _e("View all advertisers", BANNERS_PREF); ?> (<?php echo (banners_advertisers_total() > 9999) ? '+9999' : banners_advertisers_total(); ?>)</a></li>
			</ul>
		</li>
		<li><a href="<?php echo osc_route_admin_url("banners-admin"); ?>">3 - <?php _e("Banners", BANNERS_PREF); ?> &raquo;</a>
			<ul>
				<li><a href="<?php echo osc_route_admin_url("banners-admin-new"); ?>"><?php _e("Add new banner", BANNERS_PREF); ?></a></li>
				<li><a href="<?php echo osc_route_admin_url("banners-admin"); ?>"><?php _e("View all banners", BANNERS_PREF); ?> (<?php echo (banners_count_total() > 9999) ? '+9999' : banners_count_total(); ?>)</a></li>
			</ul>
		</li>
		<li><a href="<?php echo osc_route_admin_url("banners-admin-settings"); ?>"><?php _e("Settings", BANNERS_PREF); ?></a></li>
	</ul>
</div>

<div class="clear"><br /></div>