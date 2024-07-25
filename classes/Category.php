<?php

class Category
{
    //method to get the categories list while adding
    public static function getCategories($conn, )
    {
        $sql = "SELECT * 
        FROM `categories`
        ORDER BY cat_id";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);

    }
    //method to fatch all the given values for counting
    public static function getCatDetails($conn)
    {
        $sql = "SELECT `categories`.*, items.*
    FROM `categories`
    LEFT JOIN `items` 
    ON categories.cat_id = items.category_id  
    ORDER BY `categories`.`cat_id` ASC";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);

    }
    //Method to get Categories Total 
    public static function getCatTotal($conn, $limit)
    {
        $sqlCatTotal = "SELECT COUNT(*)as total FROM `categories`";
        $stmtCatTotal = $conn->prepare($sqlCatTotal);
        $stmtCatTotal->execute();
        $totalCat = $stmtCatTotal->fetch(PDO::FETCH_ASSOC)['total'];
        return ceil($totalCat / $limit);
    }
    //Method to display Category inside the table
    public static function getCategory($conn, $limit, $offset)
    {
        $sqlShow = "SELECT * 
            FROM `categories`
            ORDER BY `cat_id`
            LIMIT :limit 
            OFFSET :offset";
        $stmtShow = $conn->prepare($sqlShow);
        $stmtShow->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmtShow->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmtShow->execute();
        return $stmtShow->fetchAll(PDO::FETCH_ASSOC);
    }

}



