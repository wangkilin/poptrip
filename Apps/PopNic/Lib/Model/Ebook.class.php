<?php
/**
 * book list
 * chapter
 *
 *
 * @author Mingxia
 *
 */
class EbookModel extends Model
{
    // 数据表前缀
    protected $tablePrefix  =   ' ';
    // 模型名称
    protected $name = 'EbookModel';
    // 数据表名（不包含表前缀）
    protected $tableName = 'vote_list';

    public function getBookList()
    {

    }

    public function addBook() {}

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