 <?php 

 Class Pagination{
    public function getPage($conn, $limit, $offset) {
        
        $query = 'SELECT * FROM `items` LIMIT :limit OFFSET :offset';
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
 }
 
 ?>
 
 