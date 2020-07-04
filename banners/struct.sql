CREATE TABLE /*TABLE_PREFIX*/t_banners_positions (
	pk_i_id INT NOT NULL AUTO_INCREMENT,
	i_sort_id INT NULL,
	s_title VARCHAR(60) NULL,

	PRIMARY KEY (pk_i_id)
)	ENGINE=InnoDB DEFAULT CHARACTER SET 'UTF8' COLLATE 'UTF8_GENERAL_CI';

CREATE TABLE /*TABLE_PREFIX*/t_banners_advertisers (
	pk_i_id INT NOT NULL AUTO_INCREMENT,
	fk_i_user_id INT(10) UNSIGNED NULL,
	s_name VARCHAR(250) NULL,
	s_business_sector VARCHAR(250) NULL,
	dt_date DATETIME NOT NULL,
	dt_update DATETIME NULL,
	b_active BOOLEAN NOT NULL DEFAULT TRUE,

	PRIMARY KEY (pk_i_id)
)	ENGINE=InnoDB DEFAULT CHARACTER SET 'UTF8' COLLATE 'UTF8_GENERAL_CI';

CREATE TABLE /*TABLE_PREFIX*/t_banners (
	pk_i_id INT NOT NULL AUTO_INCREMENT,
	fk_i_advertiser_id INT NULL,
	fk_i_position_id INT NULL,
	s_category TEXT NOT NULL,
	s_url TEXT NULL,
	s_name VARCHAR(60) NULL,
	s_title VARCHAR(60) NULL,
	s_alt VARCHAR(60) NULL,
	s_css_class VARCHAR(250) NULL,
	s_content_type VARCHAR(40) NULL,
	s_extension VARCHAR(10) NULL,
	s_script TEXT NULL,
	s_color VARCHAR(50) NOT NULL,
	dt_since_date DATE NOT NULL,
	dt_until_date DATE NOT NULL,
	dt_date DATETIME NOT NULL,
	dt_update DATETIME NULL,
	b_image BOOLEAN NOT NULL DEFAULT TRUE,
	b_active BOOLEAN NOT NULL DEFAULT TRUE,

	PRIMARY KEY (pk_i_id),
	INDEX (fk_i_advertiser_id),
	INDEX (fk_i_position_id),
	FOREIGN KEY (fk_i_advertiser_id) REFERENCES /*TABLE_PREFIX*/t_banners_advertisers (pk_i_id),
	FOREIGN KEY (fk_i_position_id) REFERENCES /*TABLE_PREFIX*/t_banners_positions (pk_i_id)
)	ENGINE=InnoDB DEFAULT CHARACTER SET 'UTF8' COLLATE 'UTF8_GENERAL_CI';

CREATE TABLE /*TABLE_PREFIX*/t_banners_clicks (
	pk_i_id INT NOT NULL AUTO_INCREMENT,
	fk_i_banner_id INT NULL,
	s_ip VARCHAR(64) NOT NULL DEFAULT '',
	dt_date DATETIME NOT NULL,

	PRIMARY KEY (pk_i_id),
	INDEX (fk_i_banner_id),
	FOREIGN KEY (fk_i_banner_id) REFERENCES /*TABLE_PREFIX*/t_banners (pk_i_id)
)	ENGINE=InnoDB DEFAULT CHARACTER SET 'UTF8' COLLATE 'UTF8_GENERAL_CI';