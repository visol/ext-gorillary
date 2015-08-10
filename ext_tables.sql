#
# Table structure for table 'tt_content'
#
CREATE TABLE tt_content (
	tx_gorillary_imagesource blob NOT NULL,
	tx_gorillary_feedimports blob NOT NULL,
);



#
# Table structure for table 'tx_gorillary_collections'
#
CREATE TABLE tx_gorillary_collections (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	tstamp int(11) DEFAULT '0' NOT NULL,
	crdate int(11) DEFAULT '0' NOT NULL,
	cruser_id int(11) DEFAULT '0' NOT NULL,
	sys_language_uid int(11) DEFAULT '0' NOT NULL,
	l10n_parent int(11) DEFAULT '0' NOT NULL,
	l10n_diffsource mediumtext,
    sorting int(11) DEFAULT '0' NOT NULL,
	deleted tinyint(4) DEFAULT '0' NOT NULL,
	hidden tinyint(4) DEFAULT '0' NOT NULL,
	title tinytext,
	image text,
	images blob NOT NULL,
	image_records blob NOT NULL,
    parentid int(11) DEFAULT '0' NOT NULL,
	parenttable tinytext NOT NULL,
	PRIMARY KEY (uid),
	KEY parent (pid)
);

#
# Table structure for table 'tx_gorillary_feedimports'
#
CREATE TABLE tx_gorillary_feedimports (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	tstamp int(11) DEFAULT '0' NOT NULL,
	crdate int(11) DEFAULT '0' NOT NULL,
	cruser_id int(11) DEFAULT '0' NOT NULL,
	sys_language_uid int(11) DEFAULT '0' NOT NULL,
	l10n_parent int(11) DEFAULT '0' NOT NULL,
	l10n_diffsource mediumtext,
	deleted tinyint(4) DEFAULT '0' NOT NULL,
	hidden tinyint(4) DEFAULT '0' NOT NULL,
	title tinytext,
    image text,
    images blob NOT NULL,
	feed_url text,
	image_records blob NOT NULL,
    parentid int(11) DEFAULT '0' NOT NULL,
	parenttable tinytext NOT NULL,
	PRIMARY KEY (uid),
	KEY parent (pid)
);

#
# Table structure for table 'tx_gorillary_images'
#
CREATE TABLE tx_gorillary_images (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	tstamp int(11) DEFAULT '0' NOT NULL,
	crdate int(11) DEFAULT '0' NOT NULL,
	cruser_id int(11) DEFAULT '0' NOT NULL,
	sys_language_uid int(11) DEFAULT '0' NOT NULL,
	l10n_parent int(11) DEFAULT '0' NOT NULL,
	l10n_diffsource mediumtext,
	sorting int(10) DEFAULT '0' NOT NULL,
	deleted tinyint(4) DEFAULT '0' NOT NULL,
	hidden tinyint(4) DEFAULT '0' NOT NULL,
	title tinytext,
	description text,
    link tinytext,
	image text,
	additional_files blob NOT NULL,
	collection int(11) DEFAULT '0' NOT NULL,
    feedimport int(11) DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid),
	KEY parent (pid)
);
