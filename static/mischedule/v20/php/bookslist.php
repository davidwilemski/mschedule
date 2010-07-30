<?php

require_once("book.php");
require_once("dbfunctions.php");


class BooksList
{
    // An Array of Book objects
    var $books;    

    var $division;
    var $course;
    var $term;

    function BooksList($pTerm, $pDivision, $pCourse)
    {
        $this->term = $pTerm;
        $this->division = $pDivision;
        $this->course = $pCourse;
        $this->whereClause = "WHERE term = '$this->term' AND division = '$this->division' AND course = '$this->course'";
    }

    function readListFromDatabase()
    {
        connectToDB();

        $result = execQuery("SELECT count(*) FROM books $this->whereClause");
        $line = mysql_fetch_array($result, MYSQL_ASSOC);
        if ($line['count(*)'] == 0) return -1;

        $result = execQuery("SELECT sections, title, ISBN, required FROM books $this->whereClause");
        $row = 0;
        while ($line = mysql_fetch_array($result, MYSQL_ASSOC))
        {
            $book = new Book();      
            $book->sections = $line['sections'];      
            $book->title = $line['title'];
            $book->ISBN = $line['ISBN'];
            $book->required = $line['required'];
            $this->books[$row] = $book;
            $row++;
        }
        mysql_close();
        return 0;
    }

    function outputList()
    {
        if (!isset($this->books))
        {
            printf("0\n");
            return;
        }

        $n = sizeof($this->books); 
        printf("%d\n", $n);
        for ($i=0; $i<$n; $i++)
        {
            printf("%s\n", $this->books[$i]->sections);
            printf("%s\n", $this->books[$i]->title);
            printf("%s\n", $this->books[$i]->ISBN);
            printf("%s\n", $this->books[$i]->required);
        }
    }
}

?>