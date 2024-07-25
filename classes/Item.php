<?php
class Item
{
  //method to get single item
  public static function getItem($conn)
  {
    $id = $_GET['id'];
    $sql = "SELECT items.*, categories.cat_name , categories.cat_brand
      FROM items 
      INNER JOIN categories 
      ON items.category_id = categories.cat_id 
      WHERE items.id = :id
      AND `items`.`deleted`= 0";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  //method to get items for items list and edit
  public static function getItems($conn, $limit, $offset)
  {
    $sql = "SELECT items.*, categories.cat_name , categories.cat_brand
    FROM items 
    INNER JOIN categories 
    ON items.category_id = categories.cat_id 
    AND `items`.`deleted`= 0
    LIMIT :limit OFFSET :offset ";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);

  }

  //to get total number of items added
  public static function getItemsTotal($conn, $limit)
  {
    $sqlTotal = "SELECT COUNT(*) as total FROM items";
    $stmtTotal = $conn->prepare($sqlTotal);
    $stmtTotal->execute();
    $totalItems = $stmtTotal->fetch(PDO::FETCH_ASSOC)['total'];
    return ceil($totalItems / $limit);
  }


}
