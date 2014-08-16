-- Table for activity centric Peer Evalaution --

CREATE TABLE IF NOT EXISTS /*_*/pe_evaluations (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Activity` varchar(50) NOT NULL,
  `activityId` int(11) NOT NULL,
  `evaluaterUName` varchar(25) NOT NULL,
  `evaluation` blob NOT NULL,
  PRIMARY KEY (`id`)
) /*$wgDBTableOptions*/ ; 

-- Old Tables ( Used for OCL4Ed snapshot prototype ) --

-- Meta data for course --

CREATE TABLE IF NOT EXISTS /*_*/pe_cd_Activities (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `course_id` int(11) NOT NULL,
  `about_url` int(11) NOT NULL,
  `pe_flag` tinyint(1) NOT NULL,
  `timestamp` datetime NOT NULL,
  `type` int(11) NOT NULL DEFAULT '1',
  `title` varchar(32) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `title` (`title`)
)/*$wgDBTableOptions*/;

CREATE TABLE IF NOT EXISTS /*_*/pe_questions_10point (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `activity_id` int(11) NOT NULL,
  `Question` varchar(1024) NOT NULL,
  `weightage_question` int(11) NOT NULL,
  `Notachieved_rubric` varchar(2048) NOT NULL,
  `Achieved_rubric` varchar(2048) NOT NULL,
  `Merit_rubric` varchar(2048) NOT NULL,
  `timestamp` int(11) NOT NULL,
  PRIMARY KEY (`id`)
)/*$wgDBTableOptions*/;

CREATE TABLE IF NOT EXISTS /*_*/pe_questions_mcq (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `activity_id` int(11) NOT NULL,
  `Question` varchar(1024) NOT NULL,
  `No_options` int(11) NOT NULL,
  `option1` varchar(1024) NOT NULL,
  `option1_weight` int(11) NOT NULL,
  `option2` varchar(1024) NOT NULL,
  `option2_weight` int(11) NOT NULL,
  `option3` varchar(1024) NOT NULL,
  `option3_weight` int(11) NOT NULL,
  `option4` varchar(1024) NOT NULL,
  `option4_weight` int(11) NOT NULL,
  `option5` varchar(1024) NOT NULL,
  `option5_weight` int(11) NOT NULL,
  `timestamp` datetime NOT NULL,
  `weightage_question` int(11) NOT NULL,
  PRIMARY KEY (`id`)
)/*$wgDBTableOptions*/;

-- Activity data : Frequently Updated--

CREATE TABLE IF NOT EXISTS /*_*/pe_Activities (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `userId` int(10) NOT NULL,
  `URL` varchar(255) NOT NULL,
  `Title` varchar(255) NOT NULL,
  `Comment` varchar(255) NOT NULL,
  `OptedIn` tinyint(1) NOT NULL,
  `Activity_id` int(2) NOT NULL,
  `Timestamp` datetime NOT NULL,
  `EvalNum` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) /*$wgDBTableOptions*/;

CREATE TABLE IF NOT EXISTS /*_*/pe_eval_main (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ActivityId` int(11) NOT NULL,
  `EvaluaterId` int(11) NOT NULL,
  `LearnerId` int(11) NOT NULL,
  `Related` tinyint(1) NOT NULL,
  `Related_comment` varchar(512) NOT NULL,
  `Other_comments` varchar(512) NOT NULL,
  `Timestamp` datetime NOT NULL,
  PRIMARY KEY (`id`)
)/*$wgDBTableOptions*/;

ALTER TABLE  /*_*/pe_eval_main ADD  `Score` VARCHAR( 5 ) NOT NULL DEFAULT  'N/A' AFTER  `Other_comments`;
ALTER TABLE  /*_*/pe_eval_main ADD  `Learners_comment` VARCHAR( 512 ) NOT NULL AFTER  `Other_comments`;

ALTER TABLE  /*_*/pe_eval_main CHANGE  `Score`  `Score` VARCHAR( 20 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT  'N/A'


CREATE TABLE IF NOT EXISTS /*_*/pe_answers (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `qid` int(11) NOT NULL,
  `EvalMainId` int(11) NOT NULL,
  `answer` varchar(256) NOT NULL,
  `Comment` varchar(512) NOT NULL,
  `Timestamp` datetime NOT NULL,
  PRIMARY KEY (`id`)
)/*$wgDBTableOptions*/;