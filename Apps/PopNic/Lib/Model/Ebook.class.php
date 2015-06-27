<?php
/**
 * book list
 * chapter
 *
 *
 * @author Mingxia
 *
 *-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2015 年 01 月 27 日 15:11
-- 服务器版本: 5.1.36-community
-- PHP 版本: 5.4.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- 数据库: `dict`
--

-- --------------------------------------------------------

--
-- 表的结构 `ebook_admin`
--

CREATE TABLE IF NOT EXISTS `ebook_admin` (
  `admin_id` int(4) NOT NULL AUTO_INCREMENT,
  `username` varchar(32) NOT NULL,
  `password` char(32) NOT NULL,
  `role_id` int(4) NOT NULL,
  PRIMARY KEY (`admin_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `ebook_book`
--

CREATE TABLE IF NOT EXISTS `ebook_book` (
  `book_id` int(4) NOT NULL AUTO_INCREMENT,
  `book_name` varchar(255) NOT NULL,
  `book_desc` text NOT NULL,
  `is_public` tinyint(1) NOT NULL,
  `admin_id` tinyint(1) NOT NULL,
  `has_try_it` tinyint(1) NOT NULL,
  `book_param_json` text NOT NULL,
  PRIMARY KEY (`book_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `ebook_chapter`
--

CREATE TABLE IF NOT EXISTS `ebook_chapter` (
  `chapter_id` int(6) NOT NULL AUTO_INCREMENT,
  `chapter_title` varchar(255) NOT NULL,
  `book_id` int(4) NOT NULL,
  `is_public` tinyint(1) NOT NULL,
  `prev_chapter_id` int(6) NOT NULL,
  `next_chapter_id` int(6) NOT NULL,
  `parent_id` int(6) NOT NULL,
  `admin_id` int(4) NOT NULL,
  PRIMARY KEY (`chapter_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `ebook_comment`
--

CREATE TABLE IF NOT EXISTS `ebook_comment` (
  `comment_id` int(11) NOT NULL,
  `author_mail` varchar(255) NOT NULL,
  `comment_content` text NOT NULL,
  `is_public` tinyint(1) NOT NULL,
  `create_time` datetime NOT NULL,
  `admin_id` int(4) NOT NULL,
  `pass_time` datetime NOT NULL,
  `order_id` int(11) NOT NULL,
  `thumb_up_times` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `ebook_link_chapter_page`
--

CREATE TABLE IF NOT EXISTS `ebook_link_chapter_page` (
  `link_id` int(11) NOT NULL AUTO_INCREMENT,
  `chapter_id` int(6) NOT NULL,
  `page_id` int(11) NOT NULL,
  PRIMARY KEY (`link_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `ebook_page`
--

CREATE TABLE IF NOT EXISTS `ebook_page` (
  `page_id` int(11) NOT NULL AUTO_INCREMENT,
  `page_tag` varchar(100) NOT NULL,
  `chapter_id` int(6) NOT NULL,
  `is_public` tinyint(1) NOT NULL,
  `prev_page_id` int(11) NOT NULL,
  `next_page_id` int(11) NOT NULL,
  `admin_id` int(4) NOT NULL,
  PRIMARY KEY (`page_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `ebook_permission`
--

CREATE TABLE IF NOT EXISTS `ebook_permission` (
  `permission_id` int(8) NOT NULL AUTO_INCREMENT,
  `permission_name` varchar(255) NOT NULL,
  `permission_tag` char(32) NOT NULL,
  `permission_desc` text NOT NULL,
  PRIMARY KEY (`permission_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `ebook_role`
--

CREATE TABLE IF NOT EXISTS `ebook_role` (
  `role_id` int(4) NOT NULL AUTO_INCREMENT,
  `role_name` varchar(255) NOT NULL,
  `permissions` text NOT NULL,
  PRIMARY KEY (`role_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

 */
class EbookModel extends Model
{
    // 数据表前缀
    protected $tablePrefix  =   ' ebook_';
    // 模型名称
    protected $name = 'EbookModel';
    // 数据表名（不包含表前缀）
    protected $tableName = 'vote_list';

    public function getBookList()
    {
        $sql = 'SELECT * FROM ' . $this->tablePrefix . 'book';
    }

    public function addBook($bookName)
    {
        $sql = 'INSERT INTO ' . $this->tablePrefix . 'book (book_name)
                VALUES ()';
    }

    public function updateBook () {}

    public function deleteBook () {}
    public function  addComment () {}
    public function updateComment () {}
    public function deleteComment () {}
    public  function addPage () {}
    public function updatePage () {}
    public function addChapter () {}
    public function addNextPage () {}
    public function addPrevPage () {}
    public function thumbsUpComment () {}

    public function getChapterByBookId ($bookId)
    {

    }

    public function getPageByShortTag ($shortTag, $bookId)
    {

    }

    public  function getCommentsByPageId ($pageId)
    {

    }


}