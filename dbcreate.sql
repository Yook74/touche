# phpMyAdmin MySQL-Dump
# version 2.3.2
# http://www.phpmyadmin.net/ (download page)
#
# Host: abe
# Generation Time: Jan 4, 2005 at 09:27 AM
# Server version: 3.23.54
# PHP Version: 4.1.1
# Database : `prog_contest`
# --------------------------------------------------------

#
# Copyright (C) 2003 David Whittington
# Copyright (C) 2005 Steve Overton
# Copyright (C) 2005 David Crim
# Copyright (C) 2009 Jonathan Geisler
#
# See the file "COPYING" for further information about the copyright
# and warranty status of this work.
#
# arch-tag: dbcreate.sql
#
#
# Table structure for table `CLARIFICATION_REQUESTS`
#
DROP TABLE IF EXISTS CLARIFICATION_REQUESTS;

CREATE TABLE CLARIFICATION_REQUESTS (
  CLARIFICATION_ID int(11) NOT NULL auto_increment,
  TEAM_ID int(11) NOT NULL default '0',
  PROBLEM_ID int(11) NOT NULL default '0',
  SUBMIT_TS int(11) NOT NULL default '0',
  QUESTION text NOT NULL,
  REPLY_TS int(11) NOT NULL default '0',
  RESPONSE text NOT NULL,
  BROADCAST int(11) NOT NULL default '0',
  PRIMARY KEY  (CLARIFICATION_ID)
);
# --------------------------------------------------------

#
# Table structure for table `JUDGED_SUBMISSIONS`
#
DROP TABLE IF EXISTS JUDGED_SUBMISSIONS;

CREATE TABLE JUDGED_SUBMISSIONS (
  JUDGED_ID int(11) NOT NULL auto_increment,
  TEAM_ID int(11) NOT NULL,
  PROBLEM_ID int(11) default NULL,
  TS int(11) NOT NULL,
  ATTEMPT int(11) default NULL,
  SOURCE_NAME char(255) NOT NULL,
  RESPONSE_ID int(11) NOT NULL default '0',
  VIEWED int(11) NOT NULL default '0', # This should be a boolean value???
  JUDGED int(10) NOT NULL default '0', # This should be a boolean value???
  TEST_COMPILE bool default FALSE,
  PRIMARY KEY  (JUDGED_ID)
);
# --------------------------------------------------------

#
# Table structure for table `QUEUED_SUBMISSIONS`
#
DROP TABLE IF EXISTS QUEUED_SUBMISSIONS;

CREATE TABLE QUEUED_SUBMISSIONS (
  QUEUE_ID int(11) NOT NULL auto_increment,
  TEAM_ID int(11) NOT NULL,
  PROBLEM_ID int(11) default NULL,
  TS int(11) NOT NULL,
  ATTEMPT int(11) default NULL,
  SOURCE_NAME char(10) NOT NULL,
  TEST_COMPILE bool default FALSE,
  PRIMARY KEY  (QUEUE_ID)
);
# --------------------------------------------------------

#
# Responses which are possible when a team submits a file.
#
DROP TABLE IF EXISTS RESPONSES;

CREATE TABLE RESPONSES (
  RESPONSE_ID int(11) NOT NULL,
  KEYWORD char(20) NOT NULL,
  DISPLAY_TEXT char(50) NOT NULL,
  COLOR char(255) NOT NULL default '',
  PRIMARY KEY  (RESPONSE_ID)
);

#
# The order indicates severity, where 0 is the most severe.
# The idea is that the submitting team will see the most severe error.
#

INSERT INTO RESPONSES VALUES("0", "PENDING", "Pending","FFFF00");
INSERT INTO RESPONSES VALUES("1", "EFILETYPE", "Undefined File Type","FF0000");
INSERT INTO RESPONSES VALUES("2", "EFORBIDDEN", "Forbidden Word in Source","FF0000");
INSERT INTO RESPONSES VALUES("3", "ECOMPILE", "Compile Error","FF0000");
INSERT INTO RESPONSES VALUES("4", "ERUNTIME", "Runtime Error","FF0000");
INSERT INTO RESPONSES VALUES("5", "EMAXOUTPUT", "Exceeds Output Limit","FF0000");
INSERT INTO RESPONSES VALUES("6", "EINCORRECT", "Incorrect Output","FF0000");
INSERT INTO RESPONSES VALUES("7", "ETIMEOUT", "Exceeds Time Limit","FF0000");
INSERT INTO RESPONSES VALUES("8", "EFORMAT", "Format Error","FF0000");
INSERT INTO RESPONSES VALUES("9", "EUNKNOWN", "Error (Reason Unknown)","FF0000");
INSERT INTO RESPONSES VALUES("10", "CORRECT", "Accepted","00FF00");
# --------------------------------------------------------

#
# Table structure for table `PROBLEMS`
#
DROP TABLE IF EXISTS PROBLEMS;

CREATE TABLE PROBLEMS (
  PROBLEM_ID int(11) NOT NULL auto_increment,
  PROBLEM_NAME char(30) NOT NULL default '',
  PROBLEM_LOC char(20) NOT NULL default '',
  PROBLEM_NOTE char(100) NOT NULL default '',
  PRIMARY KEY  (PROBLEM_ID)
);
# --------------------------------------------------------

#
# Table structure for table `TEAMS`
#
DROP TABLE IF EXISTS TEAMS;

CREATE TABLE TEAMS (
  TEAM_ID int(11) NOT NULL auto_increment,
  TEAM_NAME char(100) NOT NULL default '',
  ORGANIZATION char(100) NOT NULL default '',
  USERNAME char(30) NOT NULL default '',
  PASSWORD char(20) NOT NULL default '',
  SITE_ID int(11) NOT NULL default '0',
  COACH_NAME char(30) NOT NULL default '',
  CONTESTANT_1_NAME char(30) NOT NULL default '',
  CONTESTANT_2_NAME char(30) NOT NULL default '',
  CONTESTANT_3_NAME char(30) NOT NULL default '',
  ALTERNATE_NAME char(30) NOT NULL default '',
  EMAIL char(30) NOT NULL default '',
  PRIMARY KEY  (TEAM_ID)
);
# --------------------------------------------------------

#
# Table structure for table `CATEGORY_TEAM`
#
DROP TABLE IF EXISTS CATEGORY_TEAM;

CREATE TABLE CATEGORY_TEAM (
  TEAM_ID int(11) NOT NULL default '0',
  CATEGORY_ID int(11) NOT NULL default'0',
  PRIMARY KEY  (TEAM_ID,CATEGORY_ID)
);
# --------------------------------------------------------

#
# Table structure for table `CATEGORIES`
#
DROP TABLE IF EXISTS CATEGORIES;

CREATE TABLE CATEGORIES (
  CATEGORY_ID int(11) NOT NULL auto_increment,
  CATEGORY_NAME char(30) NOT NULL default '',
  PRIMARY KEY  (CATEGORY_ID)
);
# --------------------------------------------------------

#
# Table structure for table `SITE`
#
DROP TABLE IF EXISTS SITE;

CREATE TABLE SITE (
  SITE_ID int(11) NOT NULL auto_increment,
  SITE_NAME char(30) NOT NULL default '',
  START_TIME time NOT NULL default '0',
  PRIMARY KEY  (SITE_ID),
  START_TS int(11) NOT NULL default '0',
  HAS_STARTED int(11) NOT NULL default '0'
);
# --------------------------------------------------------

#
# Table structure for table `CONTEST_CONFIG`
#
DROP TABLE IF EXISTS CONTEST_CONFIG;

CREATE TABLE CONTEST_CONFIG (
  HOST char(30) NOT NULL default '',
  CONTEST_NAME char(30) NOT NULL default '',
  CONTEST_DATE date NOT NULL default '2001-01-01',
  START_TIME time NOT NULL default '12:00',
  FREEZE_DELAY int(11) NOT NULL default '0',
  CONTEST_END_DELAY int(11) NOT NULL default '0',
  BASE_DIRECTORY char(255) NOT NULL default '',
  QUEUE_DIRECTORY char(255) NOT NULL default '',
  JUDGE_DIRECTORY char(255) NOT NULL default '',
  DATA_DIRECTORY char(255) NOT NULL default '',
  NUM_PROBLEMS int(11) NOT NULL default '0',
  IGNORE_STDERR int(1) NOT NULL default '0',
  JUDGE_USER char(30) NOT NULL default '',
  JUDGE_PASS char(30) NOT NULL default '',
  START_TS int(11) NOT NULL default '0',
  HAS_STARTED int(11) NOT NULL default '0',
  TEAM_SHOW smallint(1) NOT NULL default '0'
);
# --------------------------------------------------------

#
# Table structure for table `LANGUAGE`
#
DROP TABLE IF EXISTS LANGUAGE;

CREATE TABLE LANGUAGE (
  LANGUAGE_ID int(11) NOT NULL auto_increment,
  LANGUAGE_NAME char(30) NOT NULL default '',
  MAX_CPU_TIME int(11) NOT NULL default '0',
  CHROOT_DIRECTORY char(15) NOT NULL default '',
  REPLACE_HEADERS int(1) NOT NULL default '0',
  CHECK_BAD_WORDS int(1) NOT NULL default '0',
  PRIMARY KEY  (LANGUAGE_ID)
);

#
# Set default table data
#
INSERT INTO LANGUAGE VALUES(NULL,"C","30","c_jail",
	"1","1");
INSERT INTO LANGUAGE VALUES(NULL,"CXX","30","cpp_jail",
	"1","1");
INSERT INTO LANGUAGE VALUES(NULL,"JAVA","60","java_jail",
	"0","0");
INSERT INTO LANGUAGE VALUES(NULL,"Python2","60","python2_jail",
	"1","1");
INSERT INTO LANGUAGE VALUES(NULL,"Python3","60","python3_jail",
	"1","1");
# --------------------------------------------------------

#
# Table structure for table `FILE_EXTENSIONS`
#
DROP TABLE IF EXISTS FILE_EXTENSIONS;
CREATE TABLE FILE_EXTENSIONS (
  EXT_ID int(11) NOT NULL auto_increment,
  EXT char(10) NOT NULL default '',
  PRIMARY KEY  (EXT_ID)
);

#
# Set default table data
#
INSERT INTO FILE_EXTENSIONS VALUES(NULL,"c");
INSERT INTO FILE_EXTENSIONS VALUES(NULL,"C");
INSERT INTO FILE_EXTENSIONS VALUES(NULL,"cpp");
INSERT INTO FILE_EXTENSIONS VALUES(NULL,"cc");
INSERT INTO FILE_EXTENSIONS VALUES(NULL,"java");
INSERT INTO FILE_EXTENSIONS VALUES(NULL,"py");
INSERT INTO FILE_EXTENSIONS VALUES(NULL,"py2");
# --------------------------------------------------------

#
# Table structure for table `LANGUAGE_FILE_EXTENSIONS`
#
DROP TABLE IF EXISTS LANGUAGE_FILE_EXTENSIONS;

CREATE TABLE LANGUAGE_FILE_EXTENSIONS (
  EXT_ID int(11) NOT NULL default '0',
  LANGUAGE_ID int(11) NOT NULL default '0',
  PRIMARY KEY  (EXT_ID,LANGUAGE_ID)
);

#
# Set default table data
#
INSERT INTO LANGUAGE_FILE_EXTENSIONS VALUES(1,1);
INSERT INTO LANGUAGE_FILE_EXTENSIONS VALUES(2,2);
INSERT INTO LANGUAGE_FILE_EXTENSIONS VALUES(3,2);
INSERT INTO LANGUAGE_FILE_EXTENSIONS VALUES(4,2);
INSERT INTO LANGUAGE_FILE_EXTENSIONS VALUES(5,3);
INSERT INTO LANGUAGE_FILE_EXTENSIONS VALUES(6,5);
INSERT INTO LANGUAGE_FILE_EXTENSIONS VALUES(7,4);
# --------------------------------------------------------

#
# Table structure for table `FORBIDDEN_WORDS`
#
DROP TABLE IF EXISTS FORBIDDEN_WORDS;

CREATE TABLE FORBIDDEN_WORDS (
  LANGUAGE_ID int(11) NOT NULL default '0',
  WORD char(15) NOT NULL default ''
);

#
# Set default table data
#
INSERT INTO FORBIDDEN_WORDS VALUES(1,"system");
INSERT INTO FORBIDDEN_WORDS VALUES(1,"fstream");
INSERT INTO FORBIDDEN_WORDS VALUES(1,"open");
INSERT INTO FORBIDDEN_WORDS VALUES(1,"__asm__");
INSERT INTO FORBIDDEN_WORDS VALUES(1,"socket");
INSERT INTO FORBIDDEN_WORDS VALUES(1,"connect");
INSERT INTO FORBIDDEN_WORDS VALUES(1,"accept");
INSERT INTO FORBIDDEN_WORDS VALUES(1,"listen");
INSERT INTO FORBIDDEN_WORDS VALUES(1,"mmap");
INSERT INTO FORBIDDEN_WORDS VALUES(2,"system");
INSERT INTO FORBIDDEN_WORDS VALUES(2,"fstream");
INSERT INTO FORBIDDEN_WORDS VALUES(2,"open");
INSERT INTO FORBIDDEN_WORDS VALUES(2,"__asm__");
INSERT INTO FORBIDDEN_WORDS VALUES(2,"socket");
INSERT INTO FORBIDDEN_WORDS VALUES(2,"connect");
INSERT INTO FORBIDDEN_WORDS VALUES(2,"accept");
INSERT INTO FORBIDDEN_WORDS VALUES(2,"listen");
INSERT INTO FORBIDDEN_WORDS VALUES(2,"mmap");
# --------------------------------------------------------

#
# Table structure for table `HEADERS`
#
DROP TABLE IF EXISTS HEADERS;

CREATE TABLE HEADERS (
  LANGUAGE_ID int(11) NOT NULL default '0',
  HEADER char(15) NOT NULL default ''
);

#
# Set default table data
#
INSERT INTO HEADERS VALUES("1","stdio.h");
INSERT INTO HEADERS VALUES("1","stdlib.h");
INSERT INTO HEADERS VALUES("1","string.h");
INSERT INTO HEADERS VALUES("1","math.h");
INSERT INTO HEADERS VALUES("1","malloc.h");
INSERT INTO HEADERS VALUES("1","ctype.h");
INSERT INTO HEADERS VALUES("1","assert.h");
INSERT INTO HEADERS VALUES("1","limits.h");
INSERT INTO HEADERS VALUES("2","cassert");
INSERT INTO HEADERS VALUES("2","cstdio");
INSERT INTO HEADERS VALUES("2","cstdlib");
INSERT INTO HEADERS VALUES("2","cstring");
INSERT INTO HEADERS VALUES("2","cmath");
INSERT INTO HEADERS VALUES("2","climits");
INSERT INTO HEADERS VALUES("2","iostream");
INSERT INTO HEADERS VALUES("2","sstream");
INSERT INTO HEADERS VALUES("2","iomanip");
INSERT INTO HEADERS VALUES("2","string");
INSERT INTO HEADERS VALUES("2","new");
INSERT INTO HEADERS VALUES("2","stdexcept");
INSERT INTO HEADERS VALUES("2","cctype");
INSERT INTO HEADERS VALUES("2","list");
INSERT INTO HEADERS VALUES("2","queue");
INSERT INTO HEADERS VALUES("2","stack");
INSERT INTO HEADERS VALUES("2","vector");
INSERT INTO HEADERS VALUES("2","map");
INSERT INTO HEADERS VALUES("2","iterator");
INSERT INTO HEADERS VALUES("2","bitset");
INSERT INTO HEADERS VALUES("2","algorithm");
INSERT INTO HEADERS VALUES("2","iomanip");
INSERT INTO HEADERS VALUES("2","set");
INSERT INTO HEADERS VALUES("3","java.lang.*");
INSERT INTO HEADERS VALUES("3","java.io.*");
INSERT INTO HEADERS VALUES("3","java.util.*");
INSERT INTO HEADERS VALUES("3","java.math.*");
#------------------------------------------------------------

#
# Table structure for table QUEUED_COMPILE
#
#
#DROP TABLE IF EXISTS QUEUED_COMPILE;
#
#CREATE TABLE QUEUED_COMPILE (
#  COMPILE_ID int(11) NOT NULL auto_increment,
#  TEAM_ID int(11) NOT NULL default '0',
#  PROBLEM_ID int(11) NOT NULL default '0',
#  TS int(11) NOT NULL default '0',
#  SOURCE_FILE varchar(255) NOT NULL,
#  PRIMARY KEY  (COMPILE_ID)
#);
#------------------------------------------------------------
#
#
# Table structure for table JUDGED_COMPILE
#
#
#DROP TABLE IF EXISTS JUDGED_COMPILE;
#
#CREATE TABLE JUDGED_COMPILE (
#  COMPILE_ID int(11) NOT NULL auto_increment,
#  TEAM_ID int(11) NOT NULL default '0',
#  TS int(11) NOT NULL default '0',
#  SOURCE_FILE varchar(255) NOT NULL,
#  RESPONSE_ID int(11) NOT NULL default '0',
#  AUTO_RESPONSE_ID int(11) NOT NULL default '0',
#  PRIMARY KEY  (COMPILE_ID)
#);

#
# Rows in this table contain the result of running one input file through the compiled submission.
# If the submission was not successfully compiled, there will be exactly one entry in this table describing the error and no other entries with that JUDGED_ID
# The INPUT_FILE is the name of a file (not absolute path) which contains the input for this one run.
# If a pre-execution error has occurred, this field is null
# The OUTPUT_FILE contains the output of the program or error text.
# RESPONSE_CODE is taken from the RESPONSES table and is the response for this set of input/output files (not the whole submission)
# ERROR_NO contains the error number in the case of a compile or runtime error
#

DROP TABLE IF EXISTS AUTO_RESPONSES;

CREATE TABLE AUTO_RESPONSES (
  JUDGED_ID int(11) NOT NULL default '0',
  INPUT_FILE varchar(255) default NULL,
  OUTPUT_FILE varchar(255) NOT NULL,
  RESPONSE_ID int(10) NOT NULL default '0',
  ERROR_NO int(10) default NULL,
  PRIMARY KEY (JUDGED_ID, OUTPUT_FILE)
);
