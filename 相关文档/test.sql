-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 2016-06-06 14:49:09
-- 服务器版本： 5.6.17-log
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `test1`
--

-- --------------------------------------------------------

--
-- 表的结构 `g_activity`
--

CREATE TABLE IF NOT EXISTS `g_activity` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `eid` int(10) NOT NULL COMMENT '关联大钢外课程表主键',
  `stuid` int(10) NOT NULL COMMENT '学生的id',
  `content` text NOT NULL COMMENT '活动内容',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态(默认状态为0，只有老师点确认后才改为1，改为1以后，管理者后台查看按钮变可以点击)',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='大钢外课程记录表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `g_check`
--

CREATE TABLE IF NOT EXISTS `g_check` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `stuid` int(10) NOT NULL COMMENT '学生id',
  `department_id` int(10) NOT NULL COMMENT '科室id',
  `attendance` int(10) NOT NULL DEFAULT '0' COMMENT '出勤天数',
  `late` int(10) NOT NULL DEFAULT '0' COMMENT '迟到天数',
  `early` int(10) NOT NULL DEFAULT '0' COMMENT '早退天数',
  `leave` int(10) NOT NULL DEFAULT '0' COMMENT '请假天数',
  `teacher` varchar(10) DEFAULT NULL COMMENT '教师签字',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='学生考勤表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `g_course_main`
--

CREATE TABLE IF NOT EXISTS `g_course_main` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `department_id` int(10) NOT NULL COMMENT '科室id',
  `type` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'type为类型1:病种2:临床操作技术3:大病历书写4:活动记录',
  `title` varchar(100) NOT NULL DEFAULT '' COMMENT '标题',
  `ctype` tinyint(1) NOT NULL DEFAULT '1' COMMENT '需要达到的要求1:掌握2:熟悉',
  `num` int(4) NOT NULL DEFAULT '0' COMMENT '需要完成的数量',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='大钢课程设置表' AUTO_INCREMENT=5 ;

--
-- 转存表中的数据 `g_course_main`
--

INSERT INTO `g_course_main` (`id`, `department_id`, `type`, `title`, `ctype`, `num`) VALUES
(1, 8, 1, '急性气管支气管炎', 1, 3),
(2, 8, 1, '支气管哮喘', 1, 2),
(3, 8, 1, '支气管扩张', 2, 5),
(4, 8, 2, '动脉采血', 1, 3);

-- --------------------------------------------------------

--
-- 表的结构 `g_department`
--

CREATE TABLE IF NOT EXISTS `g_department` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL COMMENT '科室名字',
  `pid` varchar(255) NOT NULL COMMENT '所属的父科室',
  `hospital_id` int(10) NOT NULL COMMENT '关联hospital表',
  `teacher_ids` varchar(255) NOT NULL COMMENT '用来拼接负责此科室的老师的userid',
  `teacher_id` int(10) NOT NULL COMMENT '科室主要负责人',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='科室表' AUTO_INCREMENT=15 ;

--
-- 转存表中的数据 `g_department`
--

INSERT INTO `g_department` (`id`, `name`, `pid`, `hospital_id`, `teacher_ids`, `teacher_id`) VALUES
(1, '急诊科', '0', 0, '', 5),
(2, '急诊流水', '1', 0, '', 5),
(3, '抢救室', '1', 0, '', 5),
(4, '观察室', '1', 0, '', 6),
(5, '院外急救', '1', 0, '', 6),
(6, '急诊ICU/综合ICU', '1', 0, '', 6),
(7, '内科', '0', 0, '', 6),
(8, '呼吸科', '7', 0, '', 6),
(9, '心血管科', '7', 0, '', 6),
(10, '神经科', '7', 0, '', 6),
(11, '消化科', '7', 0, '', 6),
(12, '感染科', '7', 0, '', 5),
(13, '妇科', '0', 0, '', 5),
(14, '妇产科', '13', 0, '', 5);

-- --------------------------------------------------------

--
-- 表的结构 `g_elective_course`
--

CREATE TABLE IF NOT EXISTS `g_elective_course` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `ctime` int(10) NOT NULL COMMENT '上课时间',
  `cname` varchar(30) NOT NULL COMMENT '课程名字',
  `department_id` int(10) NOT NULL COMMENT '科室id',
  `venue` varchar(50) NOT NULL COMMENT '授课地点',
  `tid` int(10) NOT NULL COMMENT '授课老师的id',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='大纲外课程表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `g_hospital`
--

CREATE TABLE IF NOT EXISTS `g_hospital` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL COMMENT '不同的医院',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='医院表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `g_kcycle`
--

CREATE TABLE IF NOT EXISTS `g_kcycle` (
  `mid` int(10) NOT NULL COMMENT '关联总体轮转表主键',
  `department_id` int(10) NOT NULL COMMENT '科室id',
  `plan_starttime` int(10) DEFAULT NULL COMMENT '计划轮转开始时间',
  `plan_endtime` int(10) DEFAULT NULL COMMENT '计划轮转结束时间',
  `starttime` int(10) DEFAULT NULL COMMENT '实际入科时间',
  `endtime` int(10) DEFAULT NULL COMMENT '实际出科时间',
  `teacher` varchar(18) DEFAULT NULL COMMENT '负责人签字',
  UNIQUE KEY `mid` (`mid`,`department_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='科室轮转表';

--
-- 转存表中的数据 `g_kcycle`
--

INSERT INTO `g_kcycle` (`mid`, `department_id`, `plan_starttime`, `plan_endtime`, `starttime`, `endtime`, `teacher`) VALUES
(2, 4, 1465171200, 1467590400, NULL, NULL, NULL),
(2, 3, 1465171200, 1467590400, NULL, NULL, NULL),
(2, 2, 1465171200, 1467590400, NULL, NULL, NULL),
(2, 5, 1465171200, 1467590400, NULL, NULL, NULL),
(2, 6, 1467590400, 1470009600, NULL, NULL, NULL),
(2, 14, 1467590400, 1470009600, NULL, NULL, NULL),
(3, 2, 1465171200, 1467590400, NULL, NULL, NULL),
(3, 3, 1465171200, 1467590400, NULL, NULL, NULL),
(3, 4, 1465171200, 1467590400, NULL, NULL, NULL),
(3, 5, 1465171200, 1467590400, NULL, NULL, NULL),
(3, 6, 1467590400, 1470009600, NULL, NULL, NULL),
(3, 14, 1467590400, 1470009600, NULL, NULL, NULL),
(4, 2, 1465171200, 1470009600, NULL, NULL, NULL),
(4, 3, 1465171200, 1470009600, NULL, NULL, NULL),
(4, 4, 1465171200, 1470009600, NULL, NULL, NULL),
(4, 5, 1465171200, 1470009600, NULL, NULL, NULL),
(4, 6, 1470009600, 1474848000, NULL, NULL, NULL),
(4, 8, 1470009600, 1474848000, NULL, NULL, NULL),
(4, 9, 1470009600, 1474848000, NULL, NULL, NULL),
(4, 10, 1470009600, 1474848000, NULL, NULL, NULL),
(4, 11, 1474848000, 1479686400, NULL, NULL, NULL),
(4, 12, 1474848000, 1479686400, NULL, NULL, NULL),
(4, 14, 1474848000, 1479686400, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- 表的结构 `g_mcycle`
--

CREATE TABLE IF NOT EXISTS `g_mcycle` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `name` varchar(50) DEFAULT NULL COMMENT '此次轮转的名称',
  `starttime` int(10) DEFAULT NULL COMMENT '开始时间',
  `endtime` int(10) DEFAULT NULL COMMENT '结束时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='总体轮转表' AUTO_INCREMENT=5 ;

--
-- 转存表中的数据 `g_mcycle`
--

INSERT INTO `g_mcycle` (`id`, `name`, `starttime`, `endtime`) VALUES
(3, '方案20160606031235', 1465171200, 1470009600),
(2, '方案20160606030935', 1465171200, 1470009600),
(4, '方案20160606072219', 1465171200, 1479686400);

-- --------------------------------------------------------

--
-- 表的结构 `g_message`
--

CREATE TABLE IF NOT EXISTS `g_message` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `accepter` tinyint(1) NOT NULL COMMENT '接受对象，1代表学生2代表老师',
  `department_id` int(10) NOT NULL COMMENT '所属科室id',
  `title` varchar(15) NOT NULL COMMENT '标题',
  `content` text NOT NULL COMMENT '内容',
  `time` int(10) NOT NULL COMMENT '日期',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='消息表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `g_sdcycle`
--

CREATE TABLE IF NOT EXISTS `g_sdcycle` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `mid` int(10) NOT NULL COMMENT '关联总体轮转表主键',
  `stuid` int(10) NOT NULL COMMENT '学生id',
  `department_id` int(10) NOT NULL COMMENT '科室id',
  `plan_starttime` int(10) DEFAULT NULL COMMENT '计划轮转开始时间',
  `plan_endtime` int(10) DEFAULT NULL COMMENT '计划轮转结束时间',
  `starttime` int(10) DEFAULT NULL COMMENT '实际入科时间',
  `endtime` int(10) DEFAULT NULL COMMENT '实际出科时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='学生与科室轮转关系表' AUTO_INCREMENT=111 ;

--
-- 转存表中的数据 `g_sdcycle`
--

INSERT INTO `g_sdcycle` (`id`, `mid`, `stuid`, `department_id`, `plan_starttime`, `plan_endtime`, `starttime`, `endtime`) VALUES
(36, 2, 3, 14, 1469404800, 1470009600, NULL, NULL),
(35, 2, 3, 6, 1468800000, 1469404800, NULL, NULL),
(34, 2, 3, 5, 1465776000, 1466380800, NULL, NULL),
(33, 2, 3, 4, 1465171200, 1465776000, NULL, NULL),
(32, 2, 3, 3, 1466985600, 1467590400, NULL, NULL),
(31, 2, 3, 2, 1466380800, 1466985600, NULL, NULL),
(30, 2, 2, 14, 1467590400, 1468195200, NULL, NULL),
(29, 2, 2, 6, 1468195200, 1468800000, NULL, NULL),
(28, 2, 2, 5, 1466380800, 1466985600, NULL, NULL),
(27, 2, 2, 4, 1466985600, 1467590400, NULL, NULL),
(26, 2, 2, 3, 1465171200, 1465776000, NULL, NULL),
(25, 2, 2, 2, 1465776000, 1466380800, NULL, NULL),
(24, 2, 1, 14, 1468195200, 1468800000, NULL, NULL),
(23, 2, 1, 6, 1467590400, 1468195200, NULL, NULL),
(22, 2, 1, 5, 1466985600, 1467590400, NULL, NULL),
(21, 2, 1, 4, 1466380800, 1466985600, NULL, NULL),
(20, 2, 1, 3, 1465776000, 1466380800, NULL, NULL),
(19, 2, 1, 2, 1465171200, 1465776000, NULL, NULL),
(37, 2, 4, 2, 1466985600, 1467590400, NULL, NULL),
(38, 2, 4, 3, 1466380800, 1466985600, NULL, NULL),
(39, 2, 4, 4, 1465776000, 1466380800, NULL, NULL),
(40, 2, 4, 5, 1465171200, 1465776000, NULL, NULL),
(41, 2, 4, 6, 1469404800, 1470009600, NULL, NULL),
(42, 2, 4, 14, 1468800000, 1469404800, NULL, NULL),
(43, 3, 1, 2, 1465171200, 1465776000, NULL, NULL),
(44, 3, 1, 3, 1465776000, 1466380800, NULL, NULL),
(45, 3, 1, 4, 1466380800, 1466985600, NULL, NULL),
(46, 3, 1, 5, 1466985600, 1467590400, NULL, NULL),
(47, 3, 1, 6, 1467590400, 1468195200, NULL, NULL),
(48, 3, 1, 14, 1468195200, 1468800000, NULL, NULL),
(49, 3, 2, 2, 1465776000, 1466380800, NULL, NULL),
(50, 3, 2, 3, 1465171200, 1465776000, NULL, NULL),
(51, 3, 2, 4, 1466985600, 1467590400, NULL, NULL),
(52, 3, 2, 5, 1466380800, 1466985600, NULL, NULL),
(53, 3, 2, 6, 1468195200, 1468800000, NULL, NULL),
(54, 3, 2, 14, 1467590400, 1468195200, NULL, NULL),
(55, 3, 3, 2, 1466380800, 1466985600, NULL, NULL),
(56, 3, 3, 3, 1466985600, 1467590400, NULL, NULL),
(57, 3, 3, 4, 1465171200, 1465776000, NULL, NULL),
(58, 3, 3, 5, 1465776000, 1466380800, NULL, NULL),
(59, 3, 3, 6, 1468800000, 1469404800, NULL, NULL),
(60, 3, 3, 14, 1469404800, 1470009600, NULL, NULL),
(61, 3, 4, 2, 1466985600, 1467590400, NULL, NULL),
(62, 3, 4, 3, 1466380800, 1466985600, NULL, NULL),
(63, 3, 4, 4, 1465776000, 1466380800, NULL, NULL),
(64, 3, 4, 5, 1465171200, 1465776000, NULL, NULL),
(65, 3, 4, 6, 1469404800, 1470009600, NULL, NULL),
(66, 3, 4, 14, 1468800000, 1469404800, NULL, NULL),
(67, 4, 1, 2, 1465171200, 1466380800, NULL, NULL),
(68, 4, 1, 3, 1466380800, 1467590400, NULL, NULL),
(69, 4, 1, 4, 1467590400, 1468800000, NULL, NULL),
(70, 4, 1, 5, 1468800000, 1470009600, NULL, NULL),
(71, 4, 1, 6, 1470009600, 1471219200, NULL, NULL),
(72, 4, 1, 8, 1471219200, 1472428800, NULL, NULL),
(73, 4, 1, 9, 1472428800, 1473638400, NULL, NULL),
(74, 4, 1, 10, 1473638400, 1474848000, NULL, NULL),
(75, 4, 1, 11, 1474848000, 1476057600, NULL, NULL),
(76, 4, 1, 12, 1476057600, 1477267200, NULL, NULL),
(77, 4, 1, 14, 1477267200, 1478476800, NULL, NULL),
(78, 4, 2, 2, 1466380800, 1467590400, NULL, NULL),
(79, 4, 2, 3, 1465171200, 1466380800, NULL, NULL),
(80, 4, 2, 4, 1468800000, 1470009600, NULL, NULL),
(81, 4, 2, 5, 1467590400, 1468800000, NULL, NULL),
(82, 4, 2, 6, 1471219200, 1472428800, NULL, NULL),
(83, 4, 2, 8, 1470009600, 1471219200, NULL, NULL),
(84, 4, 2, 9, 1473638400, 1474848000, NULL, NULL),
(85, 4, 2, 10, 1472428800, 1473638400, NULL, NULL),
(86, 4, 2, 11, 1476057600, 1477267200, NULL, NULL),
(87, 4, 2, 12, 1474848000, 1476057600, NULL, NULL),
(88, 4, 2, 14, 1478476800, 1479686400, NULL, NULL),
(89, 4, 3, 2, 1467590400, 1468800000, NULL, NULL),
(90, 4, 3, 3, 1468800000, 1470009600, NULL, NULL),
(91, 4, 3, 4, 1465171200, 1466380800, NULL, NULL),
(92, 4, 3, 5, 1466380800, 1467590400, NULL, NULL),
(93, 4, 3, 6, 1472428800, 1473638400, NULL, NULL),
(94, 4, 3, 8, 1473638400, 1474848000, NULL, NULL),
(95, 4, 3, 9, 1470009600, 1471219200, NULL, NULL),
(96, 4, 3, 10, 1471219200, 1472428800, NULL, NULL),
(97, 4, 3, 11, 1477267200, 1478476800, NULL, NULL),
(98, 4, 3, 12, 1478476800, 1479686400, NULL, NULL),
(99, 4, 3, 14, 1474848000, 1476057600, NULL, NULL),
(100, 4, 4, 2, 1468800000, 1470009600, NULL, NULL),
(101, 4, 4, 3, 1467590400, 1468800000, NULL, NULL),
(102, 4, 4, 4, 1466380800, 1467590400, NULL, NULL),
(103, 4, 4, 5, 1465171200, 1466380800, NULL, NULL),
(104, 4, 4, 6, 1473638400, 1474848000, NULL, NULL),
(105, 4, 4, 8, 1472428800, 1473638400, NULL, NULL),
(106, 4, 4, 9, 1471219200, 1472428800, NULL, NULL),
(107, 4, 4, 10, 1470009600, 1471219200, NULL, NULL),
(108, 4, 4, 11, 1478476800, 1479686400, NULL, NULL),
(109, 4, 4, 12, 1477267200, 1478476800, NULL, NULL),
(110, 4, 4, 14, 1476057600, 1477267200, NULL, NULL);

-- --------------------------------------------------------

--
-- 表的结构 `g_stu_activity`
--

CREATE TABLE IF NOT EXISTS `g_stu_activity` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `eid` int(10) NOT NULL COMMENT '关联大钢课程设置表id',
  `stuid` int(10) NOT NULL COMMENT '学生id',
  `department_id` int(10) NOT NULL DEFAULT '0' COMMENT '科室id',
  `htime` int(10) DEFAULT NULL COMMENT '活动记录时间',
  `subject` varchar(50) DEFAULT NULL COMMENT '题目',
  `person` varchar(10) DEFAULT NULL COMMENT '课程负责人',
  `role` varchar(20) DEFAULT NULL COMMENT '参与角色',
  `description` varchar(200) DEFAULT NULL COMMENT '概述',
  `publication` varchar(20) DEFAULT NULL COMMENT '发表刊物',
  `type` varchar(20) DEFAULT NULL COMMENT '类别',
  `level` varchar(20) DEFAULT NULL COMMENT '事帮等级',
  `reason` varchar(200) DEFAULT NULL COMMENT '原因',
  `learned` varchar(200) DEFAULT NULL COMMENT '经验教训',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='学生活动记录表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `g_stu_case`
--

CREATE TABLE IF NOT EXISTS `g_stu_case` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `eid` int(10) NOT NULL COMMENT '关联大钢课程设置表主键',
  `stuid` int(10) NOT NULL COMMENT '学生id',
  `department_id` int(10) NOT NULL DEFAULT '0' COMMENT '科室id',
  `name` varchar(10) DEFAULT NULL COMMENT '病人名称',
  `number` varchar(30) DEFAULT NULL COMMENT '病历号',
  `isrescue` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否抢救0否1是',
  `case` varchar(200) DEFAULT NULL COMMENT '病例',
  `mdiagnosis` varchar(200) DEFAULT NULL COMMENT '主要诊断',
  `cdiagnosis` varchar(200) DEFAULT NULL COMMENT '次要诊断',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='学生课程病例表' AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `g_stu_case`
--

INSERT INTO `g_stu_case` (`id`, `eid`, `stuid`, `department_id`, `name`, `number`, `isrescue`, `case`, `mdiagnosis`, `cdiagnosis`) VALUES
(1, 1, 5, 8, '路飞', '13502544858', 0, '呼吸困难', '主要诊断一', '次要诊断二');

-- --------------------------------------------------------

--
-- 表的结构 `g_stu_clinical`
--

CREATE TABLE IF NOT EXISTS `g_stu_clinical` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `eid` int(10) NOT NULL COMMENT '关联大钢课程设置表主键',
  `stuid` int(10) NOT NULL COMMENT '学生id',
  `department_id` int(10) NOT NULL DEFAULT '0' COMMENT '科室id',
  `name` varchar(10) DEFAULT NULL COMMENT '病人名称',
  `number` varchar(30) DEFAULT NULL COMMENT '病历号',
  `case` varchar(200) DEFAULT NULL COMMENT '病例',
  `process` varchar(200) DEFAULT NULL COMMENT '操作过程',
  `remarks` varchar(200) DEFAULT NULL COMMENT '备注',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='学生课程临床操作表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `g_stu_disease`
--

CREATE TABLE IF NOT EXISTS `g_stu_disease` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `eid` int(10) NOT NULL COMMENT '关联大钢课程设置表主键',
  `stuid` int(10) NOT NULL COMMENT '学生id',
  `department_id` int(10) NOT NULL DEFAULT '0' COMMENT '科室id',
  `name` varchar(10) DEFAULT NULL COMMENT '病人名称',
  `number` varchar(30) DEFAULT NULL COMMENT '病历号',
  `case` varchar(200) DEFAULT NULL COMMENT '病例',
  `mdiagnosis` varchar(200) DEFAULT NULL COMMENT '主要诊断',
  `cdiagnosis` varchar(200) DEFAULT NULL COMMENT '次要诊断',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='学生大病历表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `g_stu_summary`
--

CREATE TABLE IF NOT EXISTS `g_stu_summary` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `stuid` int(10) NOT NULL COMMENT '学生id',
  `department_id` int(11) NOT NULL DEFAULT '0' COMMENT '科室id',
  `one` text COMMENT '医德医风，组织纪律处分，服务态度质量',
  `two` text COMMENT '理论学习，学习病种，技术操作',
  `three` text COMMENT '管理病床数，查房',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否确认出科 0没有出科 1出科',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='学生出科小结表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `g_user`
--

CREATE TABLE IF NOT EXISTS `g_user` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `type` tinyint(1) NOT NULL COMMENT '账号类型 1 代表学生  2代表老师  3代表管理',
  `name` varchar(30) NOT NULL COMMENT '名字',
  `certificate_type` tinyint(1) NOT NULL COMMENT '证件类型',
  `certificate_number` varchar(20) NOT NULL DEFAULT '' COMMENT '证件号',
  `national` varchar(20) NOT NULL COMMENT '民族',
  `birthday` int(10) NOT NULL COMMENT '生日',
  `phone_number` varchar(15) NOT NULL DEFAULT '' COMMENT '手机号',
  `sex` tinyint(1) NOT NULL DEFAULT '1' COMMENT '性别1男2女',
  `fixed_number` int(30) DEFAULT NULL COMMENT '电话号码',
  `email` varchar(50) DEFAULT NULL COMMENT '邮箱',
  `foreign_blity` varchar(30) NOT NULL DEFAULT '' COMMENT '外语能力',
  `stu_type` varchar(30) NOT NULL DEFAULT '' COMMENT '学院类型',
  `department_id` int(10) NOT NULL COMMENT '专业选择(科室id)',
  `communication_adress` varchar(50) NOT NULL DEFAULT '' COMMENT '通讯地址',
  `emergency_contact` varchar(30) NOT NULL DEFAULT '' COMMENT '紧急联系人',
  `ec_number` varchar(20) NOT NULL DEFAULT '' COMMENT '联系人电话',
  `ec_adress` varchar(50) NOT NULL DEFAULT '' COMMENT '紧急联系人地址',
  `self_introduce` varchar(200) NOT NULL DEFAULT '' COMMENT '自我介绍',
  `usernumber` varchar(40) NOT NULL COMMENT '账号',
  `password` varchar(255) NOT NULL COMMENT '密码',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '账号的状态',
  `hospital_id` int(10) NOT NULL COMMENT '关联医院',
  `finish` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0没有出科，1已出科',
  `stu_nu` varchar(20) NOT NULL COMMENT '学号',
  `token` varchar(32) DEFAULT NULL COMMENT 'token值验证',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='用户表' AUTO_INCREMENT=8 ;

--
-- 转存表中的数据 `g_user`
--

INSERT INTO `g_user` (`id`, `type`, `name`, `certificate_type`, `certificate_number`, `national`, `birthday`, `phone_number`, `sex`, `fixed_number`, `email`, `foreign_blity`, `stu_type`, `department_id`, `communication_adress`, `emergency_contact`, `ec_number`, `ec_adress`, `self_introduce`, `usernumber`, `password`, `status`, `hospital_id`, `finish`, `stu_nu`, `token`) VALUES
(1, 1, '学生一', 0, '', '', 0, '', 1, NULL, NULL, '', '', 2, '', '', '', '', '', 'test', 'eyJpdiI6Ijc4Y1h2MFR5VDRRR2l5THdIcFh5elE9PSIsInZhbHVlIjoicVN4MTNJZU1UZWs4SExDN2ttU0Zpdz09IiwibWFjIjoiZjM4NjAwMmQ1YjQxZWMyYjkzMjRkMjc3NDA5MzA5MWFlMmMyNDY1MzYwMmY5MjFjYTJiNWYxMDgyNWEyYmVhMiJ9', 1, 0, 0, '', 'ef7b2548d7f62653ae97505467592657'),
(2, 1, '学生二', 0, '', '', 0, '', 1, NULL, NULL, '', '', 2, '', '', '', '', '', '', '', 1, 0, 0, '', '6f1c3648dc70547281c00c389888bcb3'),
(3, 1, '学生三', 0, '', '', 0, '', 1, NULL, NULL, '', '', 3, '', '', '', '', '', '', '', 1, 0, 0, '', '6f1c3648dc70547281c00c389888bcb3'),
(4, 1, '学生四', 0, '', '', 0, '', 1, NULL, NULL, '', '', 4, '', '', '', '', '', '', '', 1, 0, 0, '', '6f1c3648dc70547281c00c389888bcb3'),
(5, 2, '程老师', 1, '1341335533', '汉', 1988, '13806521304', 1, 0, '', '', '1', 2, '', '', '', '', '', '123', 'eyJpdiI6IkJxTWY4dk1Ob3ZNZ21LTTRXS3I4VGc9PSIsInZhbHVlIjoiZXNhS1VxQXhUMGMrMjgzSDNsZGxIaXFIdXU4aFZBdWEwT2pLQUwySjBia1VwcFwvaUVkZU8rWGJhRWN6ZHRCc00iLCJtYWMiOiJkNzg0ZjFkODkwNzhhMDc5NjVlOTdjZDE3MTc5OTlhZmY1MjRhZDIyODIzODdkOGFlMjA4NjM4ZmQ2MTMzY2ZiIn0=', 1, 1, 0, '134313562343', '8050f838633602f7cef92b78850ba9d9'),
(6, 2, '柳老师', 1, '48941689745552', '汉', 1988, '1379631394', 1, 0, 'phpifno@foxmail.com', '', '1', 2, '', '', '', '', '', '588', '202cb962ac59075b964b07152d234b70', 1, 1, 0, '588', '5112ff28b759913273e723b81ef5bb97'),
(7, 3, '测试新密码', 1, '853235', '汉', 1988, '132584585258', 1, 0, 'hello@china.com', '', '1', 2, '', '', '', '', '', 'hello', '202cb962ac59075b964b07152d234b70', 1, 1, 0, 'hello', '590136f492f15470a1a4bb2d0dd359a3');

-- --------------------------------------------------------

--
-- 表的结构 `g_user_type`
--

CREATE TABLE IF NOT EXISTS `g_user_type` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NOT NULL,
  `level` tinyint(1) NOT NULL COMMENT '等级',
  `type` tinyint(1) NOT NULL COMMENT '类型 1管理者2老师3学生',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='角色类型' AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
