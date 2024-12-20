<?php
require_once('includes/load.php');

/*--------------------------------------------------------------*/
/* Function for find all database table rows by table name
/*--------------------------------------------------------------*/
function find_all($table)
{
  global $db;
  if (tableExists($table)) {
    return find_by_sql("SELECT * FROM " . $db->escape($table));
  }
}
/*--------------------------------------------------------------*/
/* Function for Perform queries
/*--------------------------------------------------------------*/
function find_by_sql($sql)
{
  global $db;
  $result = $db->query($sql);
  $result_set = $db->while_loop($result);
  return $result_set;
}
/*--------------------------------------------------------------*/
/*  Function for Find data from table by id
/*--------------------------------------------------------------*/

function find_by_id($table, $id)
{
  global $db;

  // Ensure the ID is an integer
  $id = (int)$id;

  // Handle exceptions for specific tables with unique column structures
  if ($table === 'admin') {
    // `admin` table uses `id` as its primary key
    $sql = $db->query("SELECT * FROM {$db->escape($table)} WHERE id='{$db->escape($id)}' LIMIT 1");
  } elseif ($table === 'payments') {
    // `payments` table uses `PayId` as its primary key
    $sql = $db->query("SELECT * FROM {$db->escape($table)} WHERE PayId='{$db->escape($id)}' LIMIT 1");
  } elseif ($table === 'customer') {
    // `customer` table uses `user_id` as its primary key
    $sql = $db->query("SELECT * FROM {$db->escape($table)} WHERE userid='{$db->escape($id)}' LIMIT 1");
  } else {
    // Default case for tables that use `Id` as their primary key
    $sql = $db->query("SELECT * FROM {$db->escape($table)} WHERE Id='{$db->escape($id)}' LIMIT 1");
  }

  // Fetch and return the result as an associative array
  if ($result = $db->fetch_assoc($sql)) {
    return $result;
  } else {
    return null; // Return null if no matching record is found
  }
}



function find_by_value($table, $value, $columun)
{
  global $db;
  // $value = (int)$value;
  if (tableExists($table)) {
    $sql = $db->query("SELECT * FROM {$db->escape($table)} WHERE {$db->escape($columun)}='{$db->escape($value)}' ");
    $results = [];
    while ($row = $db->fetch_assoc($sql)) {
      $results[] = $row;
    }
    // if ($result = $db->fetch_assoc($sql))
    return $results;
    // else
  }
  return null;
}

/*--------------------------------------------------------------*/
/* Function for Delete data from table by id
/*--------------------------------------------------------------*/
function delete_by_id($table, $id)
{
  global $db;
  if (tableExists($table)) {
    $sql = "DELETE FROM " . $db->escape($table);
    $sql .= " WHERE Id=" . $db->escape($id);
    $sql .= " LIMIT 1";
    $db->query($sql);
    return ($db->affected_rows() === 1) ? true : false;
  }
}
/*--------------------------------------------------------------*/
/* Function for Count id  By table name
/*--------------------------------------------------------------*/

function count_by_id($table)
{
  global $db;
  if ($table !== 'payments' && tableExists($table)) {
    $sql    = "SELECT COUNT(id) AS total FROM " . $db->escape($table);
    $result = $db->query($sql);
    return $db->fetch_assoc($result);
  } elseif ($table === 'payments') {
    $sql    = "SELECT PayId, COUNT(PayId) AS total FROM payments WHERE OrderStatus LIKE 'Payed'";
    $result = $db->query($sql);
    return $db->fetch_assoc($result);
  }
}


/*--------------------------------------------------------------*/
/* Determine if database table exists
/*--------------------------------------------------------------*/
function tableExists($table)
{
  global $db;
  $table_exit = $db->query('SHOW TABLES FROM ' . DB_NAME . ' LIKE "' . $db->escape($table) . '"');
  if ($table_exit) {
    if ($db->num_rows($table_exit) > 0)
      return true;
    else
      return false;
  }
}
/*--------------------------------------------------------------*/
/* Login with the data provided in $_POST,
 /* coming from the login form.
/*--------------------------------------------------------------*/
function authenticate($username = '', $password = '')
{
  global $db;
  $username = $db->escape($username);
  $password = $db->escape($password);
  $sql  = sprintf("SELECT id,username,password,user_level FROM admin WHERE username ='%s' LIMIT 1", $username);
  $result = $db->query($sql);
  if ($db->num_rows($result)) {
    $user = $db->fetch_assoc($result);
    $password_request = sha1($password);
    if ($password_request === $user['password']) {
      return $user['id'];
    }
  }
  return false;
}
/*--------------------------------------------------------------*/
/* Login with the data provided in $_POST,
  /* coming from the login_v2.php form.
  /* If you used this method then remove authenticate function.
 /*--------------------------------------------------------------*/
function authenticate_v2($username = '', $password = '')
{
  global $db;
  $username = $db->escape($username);
  $password = $db->escape($password);
  $sql  = sprintf("SELECT id,username,password,user_level FROM admin WHERE username ='%s' LIMIT 1", $username);
  $result = $db->query($sql);
  if ($db->num_rows($result)) {
    $user = $db->fetch_assoc($result);
    $password_request = sha1($password);
    if ($password_request === $user['password']) {
      return $user;
    }
  }
  return false;
}


/*--------------------------------------------------------------*/
/* Find current log in user by session id
  /*--------------------------------------------------------------*/
function current_user()
{
  static $current_user;
  global $db;
  if (!$current_user) {
    if (isset($_SESSION['user_id'])):
      $user_id = intval($_SESSION['user_id']);
      $current_user = find_by_id('admin', $user_id);
    endif;
  }
  return $current_user;
}
/*--------------------------------------------------------------*/
/* Find all user by
  /* Joining users table and user gropus table
  /*--------------------------------------------------------------*/
function find_all_user()
{
  global $db;
  $results = array();
  $sql = "SELECT u.id,u.name,u.username,u.user_level,u.status,u.last_login,";
  $sql .= "g.group_name ";
  $sql .= "FROM admin u ";
  $sql .= "LEFT JOIN user_groups g ";
  $sql .= "ON g.group_level=u.user_level ORDER BY u.name ASC";
  $result = find_by_sql($sql);
  return $result;
}
/*--------------------------------------------------------------*/
/* Function to update the last log in of a user
  /*--------------------------------------------------------------*/

function updateLastLogIn($user_id)
{
  global $db;
  $date = make_date();
  $sql = "UPDATE admin SET last_login='{$date}' WHERE id ='{$user_id}' LIMIT 1";
  $result = $db->query($sql);
  return ($result && $db->affected_rows() === 1 ? true : false);
}

/*--------------------------------------------------------------*/
/* Find all Group name
  /*--------------------------------------------------------------*/
function find_by_groupName($val)
{
  global $db;
  $sql = "SELECT group_name FROM user_groups WHERE group_name = '{$db->escape($val)}' LIMIT 1 ";
  $result = $db->query($sql);
  return ($db->num_rows($result) === 0 ? true : false);
}
/*--------------------------------------------------------------*/
/* Find group level
  /*--------------------------------------------------------------*/
function find_by_groupLevel($level)
{
  global $db;
  $sql = "SELECT group_level FROM user_groups WHERE group_level = '{$db->escape($level)}' LIMIT 1 ";
  $result = $db->query($sql);
  return ($db->num_rows($result) === 0 ? true : false);
}
/*--------------------------------------------------------------*/
/* Function for cheaking which user level has access to page
  /*--------------------------------------------------------------*/

//    }

function find_all_purchases()
{
  global $db;
  $sql  = "SELECT purchase.Id,purchase.status, purchase.unitPrice, purchase.quantity, purchase.totalPrice, ";
  $sql .= "suppliers.supplierName, product.Productname ";
  $sql .= "FROM purchase ";
  $sql .= "LEFT JOIN suppliers ON purchase.supplierId = suppliers.Id ";
  $sql .= "LEFT JOIN product ON purchase.itemId = product.Id ORDER BY purchase.Id";
  return find_by_sql($sql);
}
function debug($var)
{
  echo '<pre>';
  print_r($var);
  echo '</pre>';
}


function page_require_level($require_level)
{
  global $session, $db;
  $current_user = current_user();
  $sql = sprintf("SELECT group_level FROM user_groups WHERE group_level = '%d'", $current_user['user_level']);
  $result = $db->query($sql);

  if (!$result || $db->num_rows($result) == 0) {
    error_log('Error: User level not found');
    $session->msg("d", "Sorry! you don't have permission to view the page.");
    redirect('home.php', false);
  } elseif ($current_user['user_level'] <= (int)$require_level) {
    return true;
  } else {
    error_log('Access Denied: ' . $current_user['username']);
    $session->msg("d", "Sorry! you don't have permission to view the page.");
    redirect('home.php', false);
  }
}


// function find_all_purchases() {
//   global $db;
//   $sql  = "SELECT purchase.Id, purchase.unitPrice, purchase.quantity, purchase.totalPrice, ";
//   $sql .= "suppliers.supplierName, product.Productname ";
//   $sql .= "FROM purchase ";
//   $sql .= "JOIN suppliers ON purchase.supplierId = suppliers.Id ";
//   $sql .= "JOIN product ON purchase.itemId = product.Id ORDER BY purchase.Id";
//   return find_by_sql($sql);
// }

/*--------------------------------------------------------------*/
/* Function for Finding all product name
   /* JOIN with categorie  and media database table
   /*--------------------------------------------------------------*/
function join_product_table()
{
  global $db;
  $sql  = " SELECT p.Id,p.Productname, p.productImage,p.ProductDetail ,c.categoryname,s.Subcategoryname";
  $sql  .= " FROM product p ";
  $sql  .= " LEFT JOIN category c ON c.Id = p.ProductCatID";
  $sql  .= " LEFT JOIN subcatgory s ON s.Id = p.Prodsubcatid";
  $sql  .= " ORDER BY p.Id ASC";
  return find_by_sql($sql);
}
/*--------------------------------------------------------------*/
/* Function for Finding all product name
  /* Request coming from ajax.php for auto suggest
  /*--------------------------------------------------------------*/

function find_product_by_title($product_name)
{
  global $db;
  $p_name = remove_junk($db->escape($product_name));
  $sql = "SELECT name FROM products WHERE name like '%$p_name%' LIMIT 5";
  $result = find_by_sql($sql);
  return $result;
}

/*--------------------------------------------------------------*/
/* Function for Finding all product info by product title
  /* Request coming from ajax.php
  /*--------------------------------------------------------------*/
function find_all_product_info_by_title($title)
{
  global $db;
  $sql  = "SELECT * FROM product ";
  $sql .= " WHERE Productname ='{$title}'";
  $sql .= " LIMIT 1";
  return find_by_sql($sql);
}

/*--------------------------------------------------------------*/
/* Function for Update product quantity
  /*--------------------------------------------------------------*/
function update_product_qty($qty, $p_id)
{
  global $db;
  $qty = (int) $qty;
  $id  = (int)$p_id;
  $sql = "UPDATE product SET quantity=quantity -'{$qty}' WHERE id = '{$id}'";
  $result = $db->query($sql);
  return ($db->affected_rows() === 1 ? true : false);
}
/*--------------------------------------------------------------*/
/* Function for Display Recent product Added
//   /*--------------------------------------------------------------*/
function find_recent_product_added($limit)
{
  global $db;
  $sql   = " SELECT p.Id AS product_id, p.Productname AS product_name, p.productImage AS product_image, ";
  $sql  .= " c.categoryname AS category_name, s.sellPrice AS selling_price";
  $sql  .= " FROM product p";
  $sql  .= " LEFT JOIN category c ON p.ProductCatID = c.Id";
  $sql  .= " LEFT JOIN stock s ON p.Id = s.itemId";
  $sql  .= " ORDER BY p.Id DESC LIMIT " . $db->escape((int)$limit);
  return find_by_sql($sql);
}

/*--------------------------------------------------------------*/
/* Function for Find Highest saleing Product
 /*--------------------------------------------------------------*/
function find_higest_saleing_product($limit)
{
  global $db;
  $sql  = "SELECT p.Productname, COUNT(od.Productid) AS totalSold, SUM(od.Quantity) AS totalQty";
  $sql .= " FROM orderdetail od";
  $sql .= " LEFT JOIN product p ON p.Id = od.Productid";
  $sql .= " LEFT JOIN customorder co ON od.Orderid = co.OrderId";
  $sql .= " LEFT JOIN payments pay ON co.OrderId = pay.POrderId";
  $sql .= " WHERE pay.OrderStatus LIKE 'Payed'";
  $sql .= " GROUP BY od.Productid";
  $sql .= " ORDER BY SUM(od.Quantity) DESC LIMIT " . $db->escape((int)$limit);
  return $db->query($sql);
}

/*--------------------------------------------------------------*/
/* Function for find all sales
 /*--------------------------------------------------------------*/
function find_all_sale()
{
  global $db;

  $sql  = "SELECT 
                 od.OrderDId AS sale_id, 
                 od.Quantity AS qty, 
                 od.productPrice AS price, 
                 p.Productname AS product_name, 
                 co.city AS customer_city, 
                 co.country AS customer_country, 
                 co.Payment_Method AS payment_method, 
                 co.Order_Status AS order_status, 
                 pay.amount AS total_amount, 
                 pay.Currency AS currency, 
                 pay.OrderStatus AS payment_status, 
                 pay.date AS payment_date 
              FROM orderdetail od 
              LEFT JOIN product p ON od.Productid = p.Id 
              LEFT JOIN customorder co ON od.Orderid = co.OrderId 
              LEFT JOIN payments pay ON co.OrderId = pay.POrderId 
              WHERE pay.OrderStatus = 'Payed' 
              ORDER BY pay.date DESC";

  return find_by_sql($sql);
}


/*--------------------------------------------------------------*/
/* Function for Display Recent sale
//  /*--------------------------------------------------------------*/
function find_recent_sale_added($limit)
{
  global $db;
  $sql  = "SELECT od.OrderDId, od.Productid, od.Quantity, od.productPrice, pay.date, p.Productname";
  $sql .= " FROM orderdetail od";
  $sql .= " LEFT JOIN product p ON od.Productid = p.Id";
  $sql .= " LEFT JOIN customorder co ON od.Orderid = co.OrderId";
  $sql .= " LEFT JOIN payments pay ON co.OrderId = pay.POrderId";
  $sql .= " WHERE pay.OrderStatus LIKE 'Payed'";
  $sql .= " ORDER BY pay.date DESC LIMIT " . $db->escape((int)$limit);
  return find_by_sql($sql);
}

/*--------------------------------------------------------------*/
/* Function for Generate sales report by two dates
/*--------------------------------------------------------------*/
function find_sale_by_dates($start_date, $end_date) {
  global $db;

  // Ensure date format matches the database
  $start_date = date("Y-m-d", strtotime($start_date));
  $end_date = date("Y-m-d", strtotime($end_date));

  $sql = "SELECT 
              p.Productname AS name,
              st.buyPrice AS buy_price, 
              st.sellPrice AS sale_price, 
              SUM(od.Quantity) AS total_sales,
              SUM(od.Quantity * st.sellPrice) AS total_sale_price,
              DATE(pay.date) AS date
          FROM orderdetail od
          INNER JOIN product p ON od.Productid = p.Id
          INNER JOIN stock st ON p.Id = st.itemId
          LEFT JOIN customorder co ON od.Orderid = co.OrderId
          LEFT JOIN payments pay ON co.OrderId = pay.POrderId
          WHERE pay.date BETWEEN '{$start_date}' AND '{$end_date}'
            AND pay.OrderStatus = 'Payed'
          GROUP BY DATE(pay.date), p.Productname, st.buyPrice, st.sellPrice
          ORDER BY DATE(pay.date) DESC";

  // Debug query for further investigation
  error_log("SQL Query: $sql");

  return $db->query($sql);
}



/*--------------------------------------------------------------*/
/* Function for Generate Daily sales report
/*--------------------------------------------------------------*/
function dailySales($year, $month)
{
  global $db;

  $sql = "SELECT 
              SUM(od.Quantity) AS qty,
              DATE(pay.date) AS date, 
              p.Productname AS name,
              SUM(st.sellPrice * od.Quantity) AS total_saleing_price
          FROM orderdetail od
          INNER JOIN product p ON od.Productid = p.Id
          INNER JOIN stock st ON p.Id = st.itemId
          LEFT JOIN customorder co ON od.Orderid = co.OrderId
          LEFT JOIN payments pay ON co.OrderId = pay.POrderId
          WHERE MONTH(pay.date) = '{$month}' 
            AND YEAR(pay.date) = '{$year}' 
            AND pay.OrderStatus = 'Payed'
          GROUP BY DATE(pay.date), p.Productname
          ORDER BY DATE(pay.date) ASC";

  return find_by_sql($sql);
}


/*--------------------------------------------------------------*/
/* Function for Generate Monthly sales report
/*--------------------------------------------------------------*/
function monthlySales($year)
{
  global $db;

  $sql = "SELECT 
              MONTH(pay.date) AS month,
              p.Productname AS name,
              SUM(od.Quantity) AS qty,
              SUM(st.sellPrice * od.Quantity) AS total_saleing_price
          FROM orderdetail od
          INNER JOIN product p ON od.Productid = p.Id
          INNER JOIN stock st ON p.Id = st.itemId
          LEFT JOIN customorder co ON od.Orderid = co.OrderId
          LEFT JOIN payments pay ON co.OrderId = pay.POrderId
          WHERE YEAR(pay.date) = '{$year}' 
            AND pay.OrderStatus = 'Payed'
          GROUP BY MONTH(pay.date), p.Productname
          ORDER BY MONTH(pay.date) ASC";

  return find_by_sql($sql);
}
