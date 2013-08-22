-- --------------------------------------------------
--
-- Tipask! SQL file for installation
-- $Id: tipask.sql
--
-- --------------------------------------------------

DROP TABLE IF EXISTS ask_message;
CREATE TABLE ask_message (
  id int(10) unsigned NOT NULL auto_increment,
  `from` varchar(15) NOT NULL default '',
  fromuid mediumint(8) unsigned NOT NULL default '0',
  touid mediumint(8) unsigned NOT NULL default '0',
  new tinyint(1) NOT NULL default '1',
  subject varchar(75) NOT NULL default '',
  time int(10) unsigned NOT NULL default '0',
  content text NOT NULL,
  status tinyint(2) NOT NULL DEFAULT '0',
  PRIMARY KEY  (id),
  KEY touid (touid,time),
  KEY fromuid (fromuid,time)
) ENGINE=MyISAM;

DROP TABLE IF EXISTS ask_answer;
CREATE TABLE ask_answer (
  id int(10) unsigned NOT NULL auto_increment,
  qid int(10) unsigned NOT NULL default '0',
  title char(50) NOT NULL,
  author varchar(15) NOT NULL default '',
  authorid mediumint(8) unsigned NOT NULL default '0',
  time int(10) unsigned NOT NULL default '0',
  adopttime int(10) unsigned NOT NULL default '0',
  content mediumtext NOT NULL,
  comment tinytext NOT NULL default '',
  status tinyint(1) unsigned NOT NULL default '1',
  `ip` VARCHAR( 20 ) NULL,
  `tag` TEXT NOT NULL default '',
  `support` INT( 10 ) NOT NULL DEFAULT '0',
  `against` INT( 10 ) NOT NULL DEFAULT '0',
  PRIMARY KEY  (id),
  KEY qid (qid),
  KEY authorid (authorid),
  KEY time (time)
) ENGINE=MyISAM;


DROP TABLE IF EXISTS ask_category;
CREATE TABLE ask_category (
  id smallint(5) unsigned NOT NULL auto_increment,
  `name` char(30) NOT NULL,
  `dir` char(30) NOT NULL,
  pid smallint(5) unsigned NOT NULL default '0',
  grade tinyint(1) unsigned NOT NULL default '0',
  displayorder tinyint(3) NOT NULL default '0',
  questions int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (id)
) ENGINE=MyISAM;
INSERT INTO ask_category(`name` ,`dir` , `pid` , `grade` , `displayorder`,`questions`) VALUES ('默认分类','default', 0,1,0,0);

DROP TABLE IF EXISTS ask_credit;
CREATE TABLE ask_credit (
  uid int(10) unsigned NOT NULL,
  time int(10) unsigned NOT NULL default '0',
  operation varchar(100) NOT NULL default '',
  credit1 smallint(6) NOT NULL default '0',
  credit2 smallint(6) NOT NULL default '0',
  credit3 smallint( 6 ) NOT NULL DEFAULT '0',
  KEY uid (uid,time)
) ENGINE=MyISAM;


DROP TABLE IF EXISTS ask_note;
CREATE TABLE ask_note (
  id smallint(5) unsigned NOT NULL auto_increment,
  author char(18) NOT NULL,
  title varchar(100) NOT NULL,
  content text NOT NULL,
  time int(10) unsigned NOT NULL default '0',
  url varchar(250) NOT NULL,
  PRIMARY KEY  (id)
)ENGINE=MyISAM;


DROP TABLE IF EXISTS ask_question;
CREATE TABLE ask_question (
  id int(10) unsigned NOT NULL auto_increment,
  cid smallint(5) unsigned NOT NULL default '0',
  cid1 smallint(5) unsigned NOT NULL default '0',
  cid2 smallint(5) unsigned NOT NULL default '0',
  cid3 smallint(5) unsigned NOT NULL default '0',
  price smallint(6) unsigned NOT NULL default '0',
  author char(15) NOT NULL default '',
  authorid mediumint(8) unsigned NOT NULL default '0',
  title char(50) NOT NULL,
  description text NOT NULL default '',
  supply text NOT NULL default '',
  `url` varchar(255) NOT NULL default '1',
  `time` int(10) unsigned NOT NULL default '0',
  endtime int(10) unsigned NOT NULL default '0',
  hidden tinyint(1) unsigned NOT NULL default '0',
  answers smallint(5) unsigned NOT NULL default '0',
  `views` int(10) unsigned NOT NULL DEFAULT '0',
  goods mediumint(8) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL default '1',
  `ip` VARCHAR( 20 ) NULL,
  search_words varchar(500) null,
  PRIMARY KEY  (id),
  KEY cid1 (cid1),
  KEY cid2 (cid2),
  KEY cid3 (cid3),
  KEY time (time),
  KEY price (price),
  KEY answers (answers),
  KEY authorid (authorid)
) ENGINE=MyISAM;
alter table ask_question add fulltext(search_words);


DROP TABLE IF EXISTS ask_session;
CREATE TABLE ask_session (
  sid char(6) NOT NULL default '',
  uid mediumint(8) unsigned NOT NULL default '0',
  `code` char(4) NOT NULL default '',
  islogin tinyint(1) NOT NULL default '0',
  `ip` VARCHAR( 20 ) NULL,
  time int(10) unsigned NOT NULL default '0',
  UNIQUE KEY sid (sid)
) ENGINE=MyISAM;


DROP TABLE IF EXISTS ask_setting;
CREATE TABLE ask_setting (
  k varchar(32) NOT NULL default '',
  v text NOT NULL,
  PRIMARY KEY  (k)
) ENGINE=MyISAM;

INSERT INTO ask_setting VALUES ('site_name', 'tipask问答网');
INSERT INTO ask_setting VALUES ('meta_description', 'tipask最强问答程序');
INSERT INTO ask_setting VALUES ('meta_keywords', 'php问答系统,百度知道程序');
INSERT INTO ask_setting VALUES ('cookie_domain', '');
INSERT INTO ask_setting VALUES ('cookie_pre', 'tp_');
INSERT INTO ask_setting VALUES ('seo_prefix', '?');
INSERT INTO ask_setting VALUES ('seo_suffix', '.html');
INSERT INTO ask_setting VALUES ('date_format', 'Y/m/d');
INSERT INTO ask_setting VALUES ('time_format', 'H:i');
INSERT INTO ask_setting VALUES ('time_offset', '8');
INSERT INTO ask_setting VALUES ('time_diff', '0');
INSERT INTO ask_setting VALUES ('site_icp', '');
INSERT INTO ask_setting VALUES ('site_statcode', '');
INSERT INTO ask_setting VALUES ('allow_register', '1');
INSERT INTO ask_setting VALUES ('access_email', '');
INSERT INTO ask_setting VALUES ('censor_email', '');
INSERT INTO ask_setting VALUES ('censor_username', '');
INSERT INTO ask_setting VALUES ('maildefault', 'tipask@domain.com');
INSERT INTO ask_setting VALUES ('mailsend', '1');
INSERT INTO ask_setting VALUES ('mailserver', 'smtp.domain.com');
INSERT INTO ask_setting VALUES ('mailport', '25');
INSERT INTO ask_setting VALUES ('mailauth', '0');
INSERT INTO ask_setting VALUES ('mailfrom', 'tpask <tipask@domain.com>');
INSERT INTO ask_setting VALUES ('mailauth_username', 'tipask@domain.com');
INSERT INTO ask_setting VALUES ('mailauth_password', '');
INSERT INTO ask_setting VALUES ('maildelimiter', '0');
INSERT INTO ask_setting VALUES ('mailusername', '1');
INSERT INTO ask_setting VALUES ('mailsilent', '0');
INSERT INTO ask_setting VALUES ('credit1_register', '20');
INSERT INTO ask_setting VALUES ('credit2_register', '20');
INSERT INTO ask_setting VALUES ('credit1_login', '2');
INSERT INTO ask_setting VALUES ('credit2_login', '0');
INSERT INTO ask_setting VALUES ('credit1_ask', '5');
INSERT INTO ask_setting VALUES ('credit2_ask', '0');
INSERT INTO ask_setting VALUES ('credit1_answer', '2');
INSERT INTO ask_setting VALUES ('credit2_answer', '0');
INSERT INTO ask_setting VALUES ('credit1_message', '-1');
INSERT INTO ask_setting VALUES ('credit2_message', '0');
INSERT INTO ask_setting VALUES ('credit1_adopt', '5');
INSERT INTO ask_setting VALUES ('credit2_adopt', '2');
INSERT INTO ask_setting VALUES ('list_indexnosolve', '5');
INSERT INTO ask_setting VALUES ('list_indexcommend', '5');
INSERT INTO ask_setting VALUES ('list_indexreward', '5');
INSERT INTO ask_setting VALUES ('list_indexnote', '5');
INSERT INTO ask_setting VALUES ('list_indexallscore', '5');
INSERT INTO ask_setting VALUES ('list_indexweekscore', '5');
INSERT INTO ask_setting VALUES ('list_default', '20');
INSERT INTO ask_setting VALUES ('rss_ttl', '60');
INSERT INTO ask_setting VALUES ('code_register', '0');
INSERT INTO ask_setting VALUES ('code_login', '0');
INSERT INTO ask_setting VALUES ('code_ask', '0');
INSERT INTO ask_setting VALUES ('code_message', '0');
INSERT INTO ask_setting VALUES ('passport_type', '0');
INSERT INTO ask_setting VALUES ('passport_open', '0');
INSERT INTO ask_setting VALUES ('passport_key', '');
INSERT INTO ask_setting VALUES ('passport_client', '');
INSERT INTO ask_setting VALUES ('passport_server', '');
INSERT INTO ask_setting VALUES ('passport_login', 'login.php');
INSERT INTO ask_setting VALUES ('passport_logout', 'login.php?action=quit');
INSERT INTO ask_setting VALUES ('passport_register', 'register.php');
INSERT INTO ask_setting VALUES ('passport_expire', '3600');
INSERT INTO ask_setting VALUES ('passport_credit1', '0');
INSERT INTO ask_setting VALUES ('passport_credit2', '0');
INSERT INTO ask_setting VALUES ('overdue_days', '60');
INSERT INTO ask_setting VALUES ('ucenter_open', '0');
INSERT INTO ask_setting VALUES ('ucenter_url', '');
INSERT INTO ask_setting VALUES ('ucenter_ip', '');
INSERT INTO ask_setting VALUES ('ucenter_password', '');
INSERT INTO ask_setting VALUES ('ucenter_ask', '1');
INSERT INTO ask_setting VALUES ('ucenter_answer', '1');
INSERT INTO ask_setting VALUES ('seo_on', '0');
INSERT INTO ask_setting VALUES ('seo_title', '');
INSERT INTO ask_setting VALUES ('seo_keywords', '');
INSERT INTO ask_setting VALUES ('seo_description', '');
INSERT INTO ask_setting VALUES ('seo_headers', '');
INSERT INTO ask_setting VALUES ('notify_mail', '0');
INSERT INTO ask_setting VALUES ('notify_message', '1');

INSERT INTO ask_setting VALUES ('tpl_dir', 'default');
INSERT INTO ask_setting VALUES ('verify_question', '0');
INSERT INTO ask_setting VALUES ('index_life', '1');
INSERT INTO ask_setting VALUES ('msgtpl', 'a:4:{i:0;a:2:{s:5:"title";s:36:"您的问题{wtbt}有了新回答！";s:7:"content";s:51:"你在{wzmc}上的提出的问题有了新回答！";}i:1;a:2:{s:5:"title";s:54:"恭喜，您对问题{wtbt}的回答已经被采纳！";s:7:"content";s:42:"你在{wzmc}上的回答内容被采纳！";}i:2;a:2:{s:5:"title";s:78:"抱歉，您的问题{wtbt}由于长时间没有处理，现已过期关闭！";s:7:"content";s:69:"您的问题{wtbt}由于长时间没有处理，现已过期关闭！";}i:3;a:2:{s:5:"title";s:42:"您对{wtbt}的回答有了新的评分！";s:7:"content";s:36:"您的回答{hdnr}有了新评分！";}}');
INSERT INTO ask_setting VALUES ('allow_outer', '0');
INSERT INTO ask_setting VALUES ('stopcopy_on', '0');
INSERT INTO ask_setting VALUES ('stopcopy_allowagent', 'webkit\r\nopera\r\nmsie\r\ncompatible\r\nbaiduspider\r\ngoogle\r\nsoso\r\nsogou\r\ngecko\r\nmozilla');
INSERT INTO ask_setting VALUES ('stopcopy_stopagent', '');
INSERT INTO ask_setting VALUES ('stopcopy_maxnum', '60');
INSERT INTO ask_setting VALUES ('editor_wordcount', 'false');
INSERT INTO ask_setting VALUES ('editor_elementpath', 'false');
INSERT INTO ask_setting VALUES ('editor_toolbars', 'FullScreen,Source,Undo,Redo,RemoveFormat,|,Bold,Italic,FontSize,FontFamily,ForeColor,|,InsertImage,attachment,Emotion,Map,|,JustifyLeft,JustifyCenter,JustifyRight,|,HighlightCode');
INSERT INTO ask_setting VALUES ('gift_range', 'a:3:{i:0;s:2:"50";i:50;s:3:"100";i:100;s:3:"300";}');
INSERT INTO ask_setting VALUES ('usernamepre', 'tipask_');
INSERT INTO ask_setting VALUES ('usercount', '0');
INSERT INTO ask_setting VALUES ('sum_onlineuser_time', '30');
INSERT INTO ask_setting VALUES ('sum_category_time', '60');
INSERT INTO ask_setting VALUES ('del_tmp_crontab', '1440');
INSERT INTO ask_setting VALUES ('allow_credit3', '-10');
INSERT INTO ask_setting VALUES ('apend_question_num', '5');
INSERT INTO ask_setting VALUES ('time_friendly', '1');

DROP TABLE IF EXISTS ask_user;
CREATE TABLE ask_user (
  uid mediumint(8) unsigned NOT NULL auto_increment,
  username char(18) NOT NULL,
  `password` char(32) NOT NULL,
  email varchar(40) NOT NULL,
  avatar varchar(100) NOT NULL,
  groupid tinyint(3) unsigned NOT NULL default '7',
  credits int(10) NOT NULL default '0',
  credit1 int(10) NOT NULL default '0',
  credit2 int(10) NOT NULL default '0',
  credit3 INT( 10 ) NOT NULL DEFAULT '0',
  regip char(15) NOT NULL,
  regtime INT( 10 ) NOT NULL DEFAULT '0',
  lastlogin int(10) unsigned NOT NULL default '0',
  gender tinyint(1) unsigned NOT NULL default '0',
  bday date NOT NULL,
  phone varchar(30) NOT NULL,
  qq varchar(15) NOT NULL,
  msn varchar(40) NOT NULL,
  signature mediumtext NOT NULL,
  authstr varchar(20) NOT NULL default '',
  access_token VARCHAR( 50 )  NULL,
  questions int(10) unsigned NOT NULL default '0',
  answers int(10) unsigned NOT NULL default '0',
  adopts int(10) unsigned NOT NULL default '0',
  isnotify tinyint(1) unsigned NOT NULL default '7',
  `elect` int(10) NOT NULL DEFAULT '0',
  `expert` TINYINT( 2 ) NOT NULL DEFAULT '0',
  PRIMARY KEY  (uid),
  KEY username (username),
  KEY email (email)
) ENGINE=MyISAM;


DROP TABLE IF EXISTS ask_usergroup;
CREATE TABLE ask_usergroup (
  groupid smallint(6) unsigned NOT NULL auto_increment,
  grouptitle char(30) NOT NULL default '',
  grouptype tinyint(1) NOT NULL default '2',
  creditslower int(10) NOT NULL,
  creditshigher int(10) NOT NULL DEFAULT '0',
  questionlimits INT( 10 ) NOT NULL DEFAULT '0',
  answerlimits INT( 10 ) NOT NULL DEFAULT '0',
  credit3limits INT( 10 ) NOT NULL DEFAULT '0',
  regulars text NOT NULL,
  PRIMARY KEY  (groupid)
) ENGINE=MyISAM;

INSERT INTO ask_usergroup VALUES (1, '超级管理员', 1, 0, 1,0,0,0, '');
INSERT INTO ask_usergroup VALUES (2, '管理员', 1, 0, 1, 0,0,0,'index/tagquestion,question/answercomment,user/exchange,expert/default,index/taglist,user/famouslist,user/favorite,question/addfavorite,user/space_ask,user/space_answer,user/space_ask,user/space_answer,user/saveimg,user/editimg,category/recommend,user/register,index/default,category/view,category/list,question/view,note/list,note/view,rss/category,rss/list,rss/question,user/space,user/scorelist,question/search,,gift/default,gift/search,gift/add,user/register,user/default,user/score,user/ask,user/answer,user/profile,user/uppass,attach/upload,question/answer,question/adopt,question/govote,question/close,question/supply,question/add,question/addscore,question/editanswer,question/search,message/send,message/new,message/personal,message/system,message/outbox,message/view,message/remove,admin_main/default,admin_main/header,admin_main/menu,admin_main/stat,admin_main/login,admin_main/logout,admin_category/default,admin_category/add,admin_category/edit,admin_category/view,admin_category/remove,admin_category/reorder,admin_question/default,admin_question/searchquestion,admin_question/searchanswer,admin_question/removequestion,admin_question/removeanswer,admin_question/edit,admin_question/editanswer,admin_question/verifyanswer,admin_question/verify,admin_question/recommend,admin_question/inrecommend,admin_question/close,admin_question/delete,admin_question/renametitle,admin_question/editquescont,admin_question/movecategory,admin_question/nosolve,admin_question/editanswercont,admin_question/deleteanswer,admin_user/default,admin_user/search,admin_user/add,admin_user/remove,admin_user/edit,admin_usergroup/default,admin_usergroup/add,admin_usergroup/remove,admin_usergroup/edit,admin_note/default,admin_note/add,admin_note/edit,admin_note/remove');
INSERT INTO ask_usergroup VALUES (3, '分类员', 1, 0, 1,0,0,0, 'user/scorelist,index/tagquestion,question/answercomment,user/exchange,expert/default,index/taglist,gift/default,user/famouslist,user/favorite,question/addfavorite,user/space_ask,user/space_answer,user/saveimg,user/editimg,category/recommend,user/register,index/default,category/view,category/list,question/view,note/list,note/view,rss/category,rss/list,rss/question,user/space,user/scorelist,question/search,question/add,question/tagask,gift/default,gift/search,gift/add,user/register,user/default,user/score,user/ask,user/answer,user/profile,user/uppass,attach/upload,question/answer,question/adopt,question/govote,question/close,question/supply,question/add,question/addscore,question/editanswer,question/search,message/send,message/new,message/personal,message/system,message/outbox,message/view,message/remove,admin_main/default,admin_main/header,admin_main/menu,admin_main/stat,admin_main/login,admin_main/logout');
INSERT INTO ask_usergroup VALUES (6, '游客', 1, 0, 1,1,1,0, 'user/qqlogin,index/tagquestion,expert/default,index/taglist,user/famouslist,category/recommend,user/register,index/default,category/view,category/list,question/view,note/list,note/view,rss/category,rss/list,rss/question,question/search,user/editimg');
INSERT INTO ask_usergroup VALUES (7, '书童', 2, 0, 80,3,3,1, 'index/tagquestion,question/answercomment,user/exchange,expert/default,index/taglist,user/famouslist,user/favorite,question/addfavorite,user/space_ask,user/space_answer,user/saveimg,user/editimg,category/recommend,user/register,index/default,category/view,category/list,question/view,note/list,note/view,rss/category,rss/list,rss/question,user/space,user/scorelist,question/search,question/add,question/tagask,gift/default,gift/search,gift/add,user/register,user/default,user/score,user/ask,user/answer,user/profile,user/uppass,attach/upload,question/answer,question/adopt,question/govote,question/close,question/supply,question/add,question/addscore,question/editanswer,question/search,message/send,message/new,message/personal,message/system,message/outbox,message/view,message/remove');
INSERT INTO ask_usergroup VALUES (8, '书生', 2, 80, 400,5,3,3, 'index/tagquestion,question/answercomment,user/exchange,expert/default,index/taglist,user/famouslist,user/favorite,question/addfavorite,user/space_ask,user/space_answer,user/saveimg,user/editimg,category/recommend,user/register,index/default,category/view,category/list,question/view,note/list,note/view,rss/category,rss/list,rss/question,user/space,user/scorelist,question/search,question/add,question/tagask,gift/default,gift/search,gift/add,user/register,user/default,user/score,user/ask,user/answer,user/profile,user/uppass,attach/upload,question/answer,question/adopt,question/govote,question/close,question/supply,question/add,question/addscore,question/editanswer,question/search,message/send,message/new,message/personal,message/system,message/outbox,message/view,message/remove');
INSERT INTO ask_usergroup VALUES (9, '秀才', 2, 400, 800,5,5,5, 'index/tagquestion,question/answercomment,user/exchange,expert/default,index/taglist,user/famouslist,user/favorite,question/addfavorite,user/space_ask,user/space_answer,user/saveimg,user/editimg,category/recommend,user/register,index/default,category/view,category/list,question/view,note/list,note/view,rss/category,rss/list,rss/question,user/space,user/scorelist,question/search,question/add,question/tagask,gift/default,gift/search,gift/add,user/register,user/default,user/score,user/ask,user/answer,user/profile,user/uppass,attach/upload,question/answer,question/adopt,question/govote,question/close,question/supply,question/add,question/addscore,question/editanswer,question/search,message/send,message/new,message/personal,message/system,message/outbox,message/view,message/remove');
INSERT INTO ask_usergroup VALUES (10, '举人', 2, 800, 2000,6,6,6, 'index/tagquestion,question/answercomment,user/exchange,expert/default,index/taglist,user/famouslist,user/favorite,question/addfavorite,user/space_ask,user/space_answer,user/saveimg,user/editimg,category/recommend,user/register,index/default,category/view,category/list,question/view,note/list,note/view,rss/category,rss/list,rss/question,user/space,user/scorelist,question/search,question/add,question/tagask,gift/default,gift/search,gift/add,user/register,user/default,user/score,user/ask,user/answer,user/profile,user/uppass,attach/upload,question/answer,question/adopt,question/govote,question/close,question/supply,question/add,question/addscore,question/editanswer,question/search,message/send,message/new,message/personal,message/system,message/outbox,message/view,message/remove');
INSERT INTO ask_usergroup VALUES (11, '解元', 2, 2000, 4000,7,7,7, 'index/tagquestion,question/answercomment,user/exchange,expert/default,index/taglist,user/famouslist,user/favorite,question/addfavorite,user/space_ask,user/space_answer,user/saveimg,user/editimg,category/recommend,user/register,index/default,category/view,category/list,question/view,note/list,note/view,rss/category,rss/list,rss/question,user/space,user/scorelist,question/search,question/add,question/tagask,gift/default,gift/search,gift/add,user/register,user/default,user/score,user/ask,user/answer,user/profile,user/uppass,attach/upload,question/answer,question/adopt,question/govote,question/close,question/supply,question/add,question/addscore,question/editanswer,question/search,message/send,message/new,message/personal,message/system,message/outbox,message/view,message/remove');
INSERT INTO ask_usergroup VALUES (12, '贡士', 2, 4000, 7000,8,8,8, 'index/tagquestion,question/answercomment,user/exchange,expert/default,index/taglist,user/famouslist,user/favorite,question/addfavorite,user/space_ask,user/space_answer,user/saveimg,user/editimg,category/recommend,user/register,index/default,category/view,category/list,question/view,note/list,note/view,rss/category,rss/list,rss/question,user/space,user/scorelist,question/search,question/add,question/tagask,gift/default,gift/search,gift/add,user/register,user/default,user/score,user/ask,user/answer,user/profile,user/uppass,attach/upload,question/answer,question/adopt,question/govote,question/close,question/supply,question/add,question/addscore,question/editanswer,question/search,message/send,message/new,message/personal,message/system,message/outbox,message/view,message/remove');
INSERT INTO ask_usergroup VALUES (13, '会元', 2, 7000, 10000,9,9,9, 'index/tagquestion,question/answercomment,user/exchange,expert/default,index/taglist,user/famouslist,user/favorite,question/addfavorite,user/space_ask,user/space_answer,user/saveimg,user/editimg,category/recommend,user/register,index/default,category/view,category/list,question/view,note/list,note/view,rss/category,rss/list,rss/question,user/space,user/scorelist,question/search,question/add,question/tagask,gift/default,gift/search,gift/add,user/register,user/default,user/score,user/ask,user/answer,user/profile,user/uppass,attach/upload,question/answer,question/adopt,question/govote,question/close,question/supply,question/add,question/addscore,question/editanswer,question/search,message/send,message/new,message/personal,message/system,message/outbox,message/view,message/remove');
INSERT INTO ask_usergroup VALUES (14, '同进士出身', 2, 10000, 14000,10,10,10, 'index/tagquestion,question/answercomment,user/exchange,expert/default,index/taglist,user/famouslist,user/favorite,question/addfavorite,user/space_ask,user/space_answer,user/saveimg,user/editimg,category/recommend,user/register,index/default,category/view,category/list,question/view,note/list,note/view,rss/category,rss/list,rss/question,user/space,user/scorelist,question/search,question/add,question/tagask,gift/default,gift/search,gift/add,user/register,user/default,user/score,user/ask,user/answer,user/profile,user/uppass,attach/upload,question/answer,question/adopt,question/govote,question/close,question/supply,question/add,question/addscore,question/editanswer,question/search,message/send,message/new,message/personal,message/system,message/outbox,message/view,message/remove');
INSERT INTO ask_usergroup VALUES (15, '大学士', 2, 14000, 18000, 11,11,10,'index/tagquestion,question/answercomment,user/exchange,expert/default,index/taglist,user/famouslist,user/favorite,question/addfavorite,user/space_ask,user/space_answer,user/saveimg,user/editimg,category/recommend,user/register,index/default,category/view,category/list,question/view,note/list,note/view,rss/category,rss/list,rss/question,user/space,user/scorelist,question/search,question/add,question/tagask,gift/default,gift/search,gift/add,user/register,user/default,user/score,user/ask,user/answer,user/profile,user/uppass,attach/upload,question/answer,question/adopt,question/govote,question/close,question/supply,question/add,question/addscore,question/editanswer,question/search,message/send,message/new,message/personal,message/system,message/outbox,message/view,message/remove');
INSERT INTO ask_usergroup VALUES (16, '探花', 2, 18000, 22000,12,12,11, 'index/tagquestion,question/answercomment,user/exchange,expert/default,index/taglist,user/famouslist,user/favorite,question/addfavorite,user/space_ask,user/space_answer,user/saveimg,user/editimg,category/recommend,user/register,index/default,category/view,category/list,question/view,note/list,note/view,rss/category,rss/list,rss/question,user/space,user/scorelist,question/search,question/add,question/tagask,gift/default,gift/search,gift/add,user/register,user/default,user/score,user/ask,user/answer,user/profile,user/uppass,attach/upload,question/answer,question/adopt,question/govote,question/close,question/supply,question/add,question/addscore,question/editanswer,question/search,message/send,message/new,message/personal,message/system,message/outbox,message/view,message/remove');
INSERT INTO ask_usergroup VALUES (17, '榜眼', 2, 22000, 32000,13,13,11, 'index/tagquestion,question/answercomment,user/exchange,expert/default,index/taglist,user/famouslist,user/favorite,question/addfavorite,user/space_ask,user/space_answer,user/saveimg,user/editimg,category/recommend,user/register,index/default,category/view,category/list,question/view,note/list,note/view,rss/category,rss/list,rss/question,user/space,user/scorelist,question/search,question/add,question/tagask,gift/default,gift/search,gift/add,user/register,user/default,user/score,user/ask,user/answer,user/profile,user/uppass,attach/upload,question/answer,question/adopt,question/govote,question/close,question/supply,question/add,question/addscore,question/editanswer,question/search,message/send,message/new,message/personal,message/system,message/outbox,message/view,message/remove');
INSERT INTO ask_usergroup VALUES (18, '状元', 2, 32000, 45000,14,14,12, 'index/tagquestion,question/answercomment,user/exchange,expert/default,index/taglist,user/famouslist,user/favorite,question/addfavorite,user/space_ask,user/space_answer,user/saveimg,user/editimg,category/recommend,user/register,index/default,category/view,category/list,question/view,note/list,note/view,rss/category,rss/list,rss/question,user/space,user/scorelist,question/search,question/add,question/tagask,gift/default,gift/search,gift/add,user/register,user/default,user/score,user/ask,user/answer,user/profile,user/uppass,attach/upload,question/answer,question/adopt,question/govote,question/close,question/supply,question/add,question/addscore,question/editanswer,question/search,message/send,message/new,message/personal,message/system,message/outbox,message/view,message/remove');
INSERT INTO ask_usergroup VALUES (19, '编修', 2, 45000, 60000,14,15,12, 'index/tagquestion,question/answercomment,user/exchange,expert/default,index/taglist,user/famouslist,user/favorite,question/addfavorite,user/space_ask,user/space_answer,user/saveimg,user/editimg,category/recommend,user/register,index/default,category/view,category/list,question/view,note/list,note/view,rss/category,rss/list,rss/question,user/space,user/scorelist,question/search,question/add,question/tagask,gift/default,gift/search,gift/add,user/register,user/default,user/score,user/ask,user/answer,user/profile,user/uppass,attach/upload,question/answer,question/adopt,question/govote,question/close,question/supply,question/add,question/addscore,question/editanswer,question/search,message/send,message/new,message/personal,message/system,message/outbox,message/view,message/remove');
INSERT INTO ask_usergroup VALUES (20, '府丞', 2, 60000, 100000,14,16,12, 'index/tagquestion,question/answercomment,user/exchange,expert/default,index/taglist,user/famouslist,user/favorite,question/addfavorite,user/space_ask,user/space_answer,user/saveimg,user/editimg,category/recommend,user/register,index/default,category/view,category/list,question/view,note/list,note/view,rss/category,rss/list,rss/question,user/space,user/scorelist,question/search,question/add,question/tagask,gift/default,gift/search,gift/add,user/register,user/default,user/score,user/ask,user/answer,user/profile,user/uppass,attach/upload,question/answer,question/adopt,question/govote,question/close,question/supply,question/add,question/addscore,question/editanswer,question/search,message/send,message/new,message/personal,message/system,message/outbox,message/view,message/remove');
INSERT INTO ask_usergroup VALUES (21, '翰林学士', 2, 100000, 150000,15,14,13, 'index/tagquestion,question/answercomment,user/exchange,expert/default,index/taglist,user/famouslist,user/favorite,question/addfavorite,user/space_ask,user/space_answer,user/saveimg,user/editimg,category/recommend,user/register,index/default,category/view,category/list,question/view,note/list,note/view,rss/category,rss/list,rss/question,user/space,user/scorelist,question/search,question/add,question/tagask,gift/default,gift/search,gift/add,user/register,user/default,user/score,user/ask,user/answer,user/profile,user/uppass,attach/upload,question/answer,question/adopt,question/govote,question/close,question/supply,question/add,question/addscore,question/editanswer,question/search,message/send,message/new,message/personal,message/system,message/outbox,message/view,message/remove');
INSERT INTO ask_usergroup VALUES (22, '御史中丞', 2, 150000, 250000,16,15,13, 'index/tagquestion,question/answercomment,user/exchange,expert/default,index/taglist,user/famouslist,user/favorite,question/addfavorite,user/space_ask,user/space_answer,user/saveimg,user/editimg,category/recommend,user/register,index/default,category/view,category/list,question/view,note/list,note/view,rss/category,rss/list,rss/question,user/space,user/scorelist,question/search,question/add,question/tagask,gift/default,gift/search,gift/add,user/register,user/default,user/score,user/ask,user/answer,user/profile,user/uppass,attach/upload,question/answer,question/adopt,question/govote,question/close,question/supply,question/add,question/addscore,question/editanswer,question/search,message/send,message/new,message/personal,message/system,message/outbox,message/view,message/remove');
INSERT INTO ask_usergroup VALUES (23, '詹士', 2, 250000, 400000,18,16,14, 'index/tagquestion,question/answercomment,user/exchange,expert/default,index/taglist,user/famouslist,user/favorite,question/addfavorite,user/space_ask,user/space_answer,user/saveimg,user/editimg,category/recommend,user/register,index/default,category/view,category/list,question/view,note/list,note/view,rss/category,rss/list,rss/question,user/space,user/scorelist,question/search,question/add,question/tagask,gift/default,gift/search,gift/add,user/register,user/default,user/score,user/ask,user/answer,user/profile,user/uppass,attach/upload,question/answer,question/adopt,question/govote,question/close,question/supply,question/add,question/addscore,question/editanswer,question/search,message/send,message/new,message/personal,message/system,message/outbox,message/view,message/remove');
INSERT INTO ask_usergroup VALUES (24, '侍郎', 2, 400000, 700000, 20,18,16,'index/tagquestion,question/answercomment,user/exchange,expert/default,index/taglist,user/famouslist,user/favorite,question/addfavorite,user/space_ask,user/space_answer,user/saveimg,user/editimg,category/recommend,user/register,index/default,category/view,category/list,question/view,note/list,note/view,rss/category,rss/list,rss/question,user/space,user/scorelist,question/search,question/add,question/tagask,gift/default,gift/search,gift/add,user/register,user/default,user/score,user/ask,user/answer,user/profile,user/uppass,attach/upload,question/answer,question/adopt,question/govote,question/close,question/supply,question/add,question/addscore,question/editanswer,question/search,message/send,message/new,message/personal,message/system,message/outbox,message/view,message/remove');
INSERT INTO ask_usergroup VALUES (25, '大学士', 2, 700000, 1000000,24,20,18, 'index/tagquestion,question/answercomment,user/exchange,expert/default,index/taglist,user/famouslist,user/favorite,question/addfavorite,user/space_ask,user/space_answer,user/saveimg,user/editimg,category/recommend,user/register,index/default,category/view,category/list,question/view,note/list,note/view,rss/category,rss/list,rss/question,user/space,user/scorelist,question/search,question/add,question/tagask,gift/default,gift/search,gift/add,user/register,user/default,user/score,user/ask,user/answer,user/profile,user/uppass,attach/upload,question/answer,question/adopt,question/govote,question/close,question/supply,question/add,question/addscore,question/editanswer,question/search,message/send,message/new,message/personal,message/system,message/outbox,message/view,message/remove');
INSERT INTO ask_usergroup VALUES (26, '文曲星', 2, 1000000, 999999999,0,0,0, 'index/tagquestion,question/answercomment,user/exchange,expert/default,index/taglist,user/famouslist,user/favorite,question/addfavorite,user/space_ask,user/space_answer,user/saveimg,user/editimg,category/recommend,user/register,index/default,category/view,category/list,question/view,note/list,note/view,rss/category,rss/list,rss/question,user/space,user/scorelist,question/search,question/add,question/tagask,gift/default,gift/search,gift/add,user/register,user/default,user/score,user/ask,user/answer,user/profile,user/uppass,attach/upload,question/answer,question/adopt,question/govote,question/close,question/supply,question/add,question/addscore,question/editanswer,question/search,message/send,message/new,message/personal,message/system,message/outbox,message/view,message/remove');


DROP TABLE IF EXISTS ask_datacall;
CREATE TABLE ask_datacall (
  `id` smallint(5) unsigned NOT NULL auto_increment,
  `title` varchar(50) NOT NULL default '',
  `expression` text NOT NULL,
  `time` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM;


DROP TABLE IF EXISTS ask_badword;
CREATE TABLE ask_badword (
  id smallint(6) unsigned NOT NULL auto_increment,
  admin varchar(15) NOT NULL default '',
  find varchar(255) NOT NULL default '',
  replacement varchar(255) NOT NULL default '',
  findpattern varchar(255) NOT NULL default '',
  PRIMARY KEY  (id),
  UNIQUE KEY `find` (`find`)
) ENGINE=MyISAM;


DROP TABLE IF EXISTS ask_link;
CREATE TABLE ask_link (
  id smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  displayorder tinyint(3) NOT NULL DEFAULT '0',
  `name` varchar(100) NOT NULL DEFAULT '',
  url varchar(255) NOT NULL DEFAULT '',
  description mediumtext NOT NULL,
  logo varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (id)
) ENGINE=MyISAM;

INSERT INTO ask_link (`id`, `displayorder`, `name`, `url`, `description`, `logo`) VALUES (1, 0, 'Tipask问答平台', 'http://help.tipask.com', 'Tipask建站问答！', '');


DROP TABLE IF EXISTS ask_nav;
CREATE TABLE ask_nav (
  id smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `name` char(50) NOT NULL,
  title char(255) NOT NULL,
  url char(255) NOT NULL,
  target tinyint(1) NOT NULL DEFAULT '0',
  available tinyint(1) NOT NULL DEFAULT '0',
  type tinyint(1) not null default 0,
  displayorder tinyint(3) NOT NULL,
  PRIMARY KEY (id)
) ENGINE=MyISAM;

INSERT INTO ask_nav (`id`, `name`, `title`, `url`, `target`, `available`, `type`, `displayorder`) VALUES(1, '问答首页', '问答首页', 'index/default', 0, 1, 1, 1);
INSERT INTO ask_nav (`id`, `name`, `title`, `url`, `target`, `available`, `type`, `displayorder`) VALUES(2, '分类大全', '分类大全', 'category/view', 0, 1, 1, 6);
INSERT INTO ask_nav (`id`, `name`, `title`, `url`, `target`, `available`, `type`, `displayorder`) VALUES(3, '问答专家', '问答专家', 'expert/default', 0, 1, 1, 5);
INSERT INTO ask_nav (`id`, `name`, `title`, `url`, `target`, `available`, `type`, `displayorder`) VALUES(4, '知识专题', '知识专题', 'category/recommend', 0, 1, 1, 3);
INSERT INTO ask_nav (`id`, `name`, `title`, `url`, `target`, `available`, `type`, `displayorder`) VALUES(5, '问答之星', '问答之星', 'user/famouslist', 0, 1, 1, 4);
INSERT INTO ask_nav (`id`, `name`, `title`, `url`, `target`, `available`, `type`, `displayorder`) VALUES(6, '标签大全', '标签大全', 'index/taglist', 0, 1, 1, 7);
INSERT INTO ask_nav (`id`, `name`, `title`, `url`, `target`, `available`, `type`, `displayorder`) VALUES(7, '礼品商店', '礼品商店', 'gift/default', 0, 1, 1, 8);

DROP TABLE IF EXISTS ask_ad;
CREATE TABLE ask_ad (
  html text,
  page varchar(50) NOT NULL DEFAULT '',
  position varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`page`,`position`)
) ENGINE=MyISAM;


DROP TABLE IF EXISTS ask_attach;
CREATE TABLE ask_attach (
  id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  qid mediumint(8) unsigned NOT NULL DEFAULT '0',
  aid int(10) unsigned NOT NULL DEFAULT '0',
  time int(10) unsigned NOT NULL DEFAULT '0',
  filename char(100) NOT NULL DEFAULT '',
  filetype char(50) NOT NULL DEFAULT '',
  filesize int(10) unsigned NOT NULL DEFAULT '0',
  location char(100) NOT NULL DEFAULT '',
  downloads mediumint(8) NOT NULL DEFAULT '0',
  isimage tinyint(1) NOT NULL DEFAULT '0',
  uid mediumint(8) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (id),
  KEY qid (qid,aid),
  KEY uid (uid),
  KEY time (time, isimage, downloads)
) ENGINE=MyISAM;


DROP TABLE IF EXISTS ask_banned;
CREATE TABLE ask_banned (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `ip1` char(3) NOT NULL,
  `ip2` char(3) NOT NULL,
  `ip3` char(3) NOT NULL,
  `ip4` char(3) NOT NULL,
  `admin` varchar(15) NOT NULL,
  `time` int(10) unsigned NOT NULL DEFAULT '0',
  `expiration` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM;

DROP TABLE IF EXISTS ask_visit;
CREATE TABLE ask_visit (
  ip varchar(15) NOT NULL,
  time int(10) NOT NULL default '0',
  KEY time(time,ip)
) ENGINE=MyISAM ;

DROP TABLE IF EXISTS ask_editor;
CREATE TABLE ask_editor (
  id smallint(6) unsigned NOT NULL auto_increment,
  available tinyint(1) NOT NULL default '1',
  tag varchar(100) NOT NULL default '',
  icon varchar(255) NOT NULL default '',
  code text NOT NULL,
  displayorder smallint(3) unsigned NOT NULL default '0',
  description text NOT NULL,
  PRIMARY KEY  (id)
) ENGINE=MyISAM ;

DROP TABLE IF EXISTS ask_gift;
CREATE TABLE ask_gift (
  id smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  title varchar(80) NOT NULL,
  description varchar(250) NOT NULL,
  image varchar(250) NOT NULL,
  credit int(10) NOT NULL DEFAULT '0',
  time int(11) NOT NULL,
  available tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM;

DROP TABLE IF EXISTS ask_giftlog;
CREATE TABLE ask_giftlog (
  id smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  uid int(10) unsigned NOT NULL,
  username char(20) NOT NULL,
  realname char(20) NOT NULL,
  gid int(10) NOT NULL,
  giftname varchar(30) NOT NULL,
  address varchar(100) NOT NULL,
  postcode char(10) NOT NULL,
  phone char(15) NOT NULL,
  qq char(15) NOT NULL,
  email varchar(30) NOT NULL DEFAULT '',
  notes text NOT NULL,
  credit int(10) NOT NULL,
  time int(11) NOT NULL,
  status tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM;

DROP TABLE IF EXISTS ask_favorite;
CREATE TABLE ask_favorite (
  uid mediumint(8) unsigned NOT NULL DEFAULT '0',
  qid mediumint(10) unsigned NOT NULL DEFAULT '0',
  cid smallint(5) unsigned NOT NULL DEFAULT '0',
  KEY uid(uid)
) ENGINE=MyISAM;

DROP TABLE IF EXISTS ask_inform;
CREATE TABLE ask_inform (
  qid int(10) NOT NULL,
  title varchar(100) NOT NULL,
  content text NOT NULL,
  keywords varchar(100) NOT NULL,
  counts int(11) NOT NULL DEFAULT '1',
  time int(10) NOT NULL,
  PRIMARY KEY (qid)
) ENGINE=MyISAM;

DROP TABLE IF EXISTS ask_answer_comment;
CREATE TABLE ask_answer_comment (
  id int(10) NOT NULL AUTO_INCREMENT,
  aid int(10) NOT NULL,
  authorid int(10) NOT NULL,
  author char(18) NOT NULL,
  content varchar(100) NOT NULL,
  credit smallint(6) NOT NULL DEFAULT '0',
  time int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (id)
) ENGINE=MyISAM;

DROP TABLE IF EXISTS ask_expert;
CREATE TABLE ask_expert (
  uid int(10) NOT NULL,
  cid INT( 10 ) NOT NULL,
  PRIMARY KEY (uid,cid)
)ENGINE=MyISAM;

DROP TABLE IF EXISTS ask_famous;
CREATE TABLE IF NOT EXISTS ask_famous(
  id int(10) NOT NULL AUTO_INCREMENT,
  uid mediumint(8) unsigned NOT NULL DEFAULT '0',
  reason char(50) DEFAULT NULL,
  time int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (id),
  KEY time (`time`)
)ENGINE=MyISAM;

DROP TABLE IF EXISTS ask_topic;
CREATE TABLE ask_topic (
  id int(10) NOT NULL AUTO_INCREMENT,
  title varchar(50) DEFAULT NULL,
  describtion varchar(200) DEFAULT NULL,
  image varchar(100) DEFAULT NULL,
  displayorder INT( 10 ) NOT NULL DEFAULT '0',
  PRIMARY KEY (id)
)ENGINE=MyISAM;

DROP TABLE IF EXISTS ask_tid_qid;
CREATE TABLE ask_tid_qid (
  tid int(10) NOT NULL DEFAULT '0',
  qid int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (tid,qid)
)ENGINE=MyISAM;

DROP TABLE IF EXISTS ask_tag;
CREATE TABLE ask_tag(
`id` INT( 10 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`letter` CHAR( 2 ) NULL ,
`name` VARCHAR( 20 ) NULL ,
`questions` INT( 10 ) NOT NULL DEFAULT '0'
) ENGINE = MYISAM ;

DROP TABLE IF EXISTS ask_question_tag;
CREATE TABLE ask_question_tag (
  tid int(10) NOT NULL,
  qid int(10) NOT NULL,
  tname varchar(20) NOT NULL,
  PRIMARY KEY (tid,qid)
)ENGINE=MyISAM;

DROP TABLE IF EXISTS ask_crontab;
CREATE TABLE ask_crontab(
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `available` tinyint(1) NOT NULL DEFAULT '0',
  `type` enum('user','system') NOT NULL DEFAULT 'user',
  `name` char(50) NOT NULL DEFAULT '',
  `method` varchar(50) NOT NULL DEFAULT '',
  `lastrun` int(10) unsigned NOT NULL DEFAULT '0',
  `nextrun` int(10) unsigned NOT NULL DEFAULT '0',
  `weekday` tinyint(1) NOT NULL DEFAULT '0',
  `day` tinyint(2) NOT NULL DEFAULT '0',
  `hour` tinyint(2) NOT NULL DEFAULT '0',
  `minute` char(36) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `nextrun` (`available`,`nextrun`)
) ENGINE=MyISAM ;
INSERT INTO ask_crontab (`id`, `available`, `type`, `name`, `method`, `lastrun`, `nextrun`, `weekday`, `day`, `hour`, `minute`) VALUES(1, 1, 'system', '每日分类统计', 'sum_category_question', 1341160751, 1341164351, -1, -1, -1, '60');

DROP TABLE IF EXISTS ask_userlog;
CREATE TABLE ask_userlog (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `sid` varchar(10) NOT NULL DEFAULT '',
  `type` enum('login','ask','answer') NOT NULL,
  `time` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sid` (`sid`),
  KEY `time` (`time`)
)ENGINE=MyISAM ;


