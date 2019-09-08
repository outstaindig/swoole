//球队
CREATE TABLE `live_team`(
  `id` tinyint(11) unsigned not null auto_increment,
  `name` VARCHAR(20) not null DEFAULT '',
  `image` VARCHAR(20) not null DEFAULT '',
  `type` tinyint(1) unsigned not null DEFAULT 0 comment '球队分区',
  `create_time` int(10) unsigned not NULL DEFAULT 0,
  `update_time` INT(10) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
)engine=innodb auto_increment=1 DEFAULT charset=utf8;

//直播表
CREATE TABLE `live_game`(
  `id` int(10) unsigned not null auto_increment,
  `a_id` tinyint(1) unsigned not null DEFAULT 0 comment 'a球队',
  `b_id` tinyint(1) unsigned not null DEFAULT 0 comment 'b球队',
  `a_score` int(10) unsigned not null DEFAULT 0 comment '分数',
  `b_score` int(10) unsigned not null DEFAULT 0 comment '分数',
  `narrator` VARCHAR(20) not null DEFAULT '' comment '旁白',
  `image` VARCHAR(20) not null DEFAULT '',
  `start_time` int(10) unsigned not NULL DEFAULT 0,
  `status` tinyint(1) unsigned not null DEFAULT 0,
  `create_time` int(10) unsigned not NULL DEFAULT 0,
  `update_time` INT(10) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
)engine=innodb auto_increment=1 DEFAULT charset=utf8;

//球员表
CREATE TABLE `live_player`(
  `id` int(10) unsigned not null auto_increment,
  `name` VARCHAR(20) not null DEFAULT '',
  `image` VARCHAR(20) not null DEFAULT '',
  `age` tinyint(1) unsigned not null DEFAULT 0,
  `position` tinyint(1) unsigned not null DEFAULT 0,
  `status` tinyint(1) unsigned not null DEFAULT 0,
  `create_time` int(10) unsigned not NULL DEFAULT 0,
  `update_time` INT(10) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
)engine=innodb auto_increment=1 DEFAULT charset=utf8;

//赛事的赛况表
CREATE TABLE `live_outs`(
  `id` int(10) unsigned not null auto_increment,
  `game_id` int(10) unsigned not null,
  `team_id` tinyint(1) unsigned not null DEFAULT 0,
  `content` VARCHAR(200) not null DEFAULT '',
  `image` VARCHAR(20) not null DEFAULT '',
  `type` tinyint(1) unsigned not null DEFAULT 0 comment '第几节',
  `status` tinyint(1) unsigned not null DEFAULT 0,
  `create_time` int(10) unsigned not NULL DEFAULT 0,
  `update_time` INT(10) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
)engine=innodb auto_increment=1 DEFAULT charset=utf8;

//聊天室表
CREATE TABLE `live_chart`(
  `id` int(10) unsigned not null auto_increment,
  `game_id` int(10) unsigned not null,
  `user_id` tinyint(1) unsigned not null DEFAULT 0,
  `content` VARCHAR(200) not null DEFAULT '',
  `status` tinyint(1) unsigned not null DEFAULT 0,
  `create_time` int(10) unsigned not NULL DEFAULT 0,
  `update_time` INT(10) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
)engine=innodb auto_increment=1 DEFAULT charset=utf8;








