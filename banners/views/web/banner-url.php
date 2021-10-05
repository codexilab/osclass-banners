<?php
$param 	= banners_route_param();
$url 	= osc_base_url();

$banner = Banners::newInstance()->getByName(Params::getParam($param));
if (osc_get_preference('show_url_banner', BANNERS_PREF))
	$banner = Banners::newInstance()->getByURL(Params::getParam($param));


if ($banner) {
	if (!osc_is_admin_user_logged_in()) {
		Banners::newInstance()->addClick([
			'fk_i_banner_id' 	=> $banner['pk_i_id'],
			's_ip' 				=> Params::getServerParam('REMOTE_ADDR'),
			'dt_date' 			=> todaydate()
		]);
	}

	$url = $banner['s_url'];
}

ob_get_clean();
osc_redirect_to($url);
?>