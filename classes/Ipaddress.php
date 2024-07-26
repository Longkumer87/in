<?php

class Ipaddress
{
    //method to get and display IP Adress
    public static function getIp($conn, $limit, $offset)
    {
        $sqlIp = "SELECT `items`.`ipAddress`, `items`.`particularsTo`
          FROM `items` 
          ORDER BY INET_ATON(`items`.`ipAddress`) ASC
          Limit :limit offset :offset";

        $stmtIp = $conn->prepare($sqlIp);
        $stmtIp->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmtIp->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmtIp->execute();
        return $stmtIp->fetchAll(PDO::FETCH_ASSOC);
    }

    //method to get total number of IP address entered
    public static function getTotalIp($conn, $limit)
    {
        $sqlTotalIp = "SELECT COUNT(*) as total
                   FROM `items`";

        $stmtTotalIp = $conn->prepare($sqlTotalIp);
        $stmtTotalIp->execute();
        $totalIp= $stmtTotalIp->fetch(PDO::FETCH_ASSOC)['total'];
        return ceil($totalIp / $limit);
    }
}