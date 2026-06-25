This homepage is mostly a **frontend display page**, but there is one section that requires backend processing: the **newsletter subscription form** in the footer. For a complete e-commerce system like *Thrift & Trend*, additional backend scripts can also be created to support dynamic categories, products, user accounts, and content management.

---

# 1. Header Section

### HTML Section

```html
<header>
    <h1>Thrift & Trend</h1>
    <nav>
        <a href="Homepage.html">Home</a>
        <a href="Shoppage.html">Shop</a>
        <a href="#">Sell With Us</a>
        <a href="Blogpage.html">Blog</a>
        <a href="Aboutpage.html">About Us</a>
        <a href="Contactpage.html">Contact Us</a>
        <a href="Loginpage.html">My Account</a>
    </nav>
</header>
```

## Function

The header provides navigation links to various pages of the website.

## PHP Backend Version

Rename the file to **Homepage.php** and use session management.

```php
<?php
session_start();

$username = "";

if(isset($_SESSION['username']))
{
    $username = $_SESSION['username'];
}
?>
```

### Dynamic Navigation

```php
<header>
    <h1>Thrift & Trend</h1>

    <nav>
        <a href="Homepage.php">Home</a>
        <a href="Shoppage.php">Shop</a>
        <a href="Sellpage.php">Sell With Us</a>
        <a href="Blogpage.php">Blog</a>
        <a href="Aboutpage.php">About Us</a>
        <a href="Contactpage.php">Contact Us</a>

        <?php
        if(isset($_SESSION['username']))
        {
            echo "<a href='dashboard.php'>Welcome $username</a>";
            echo "<a href='logout.php'>Logout</a>";
        }
        else
        {
            echo "<a href='Loginpage.php'>My Account</a>";
        }
        ?>
    </nav>
</header>
```

### Explanation

* `session_start()` starts a user session.
* `$_SESSION['username']` stores logged-in user information.
* Navigation changes depending on login status.
* Logged-in users see Dashboard and Logout links.

---

# 2. Hero Section

### HTML Section

```html
<section class="hero">
    <h2>Affordable Lifestyle Marketplace</h2>
    <p>Discover thrift wears, perfumes, shoes, handbags & more!</p>
    <button onclick="window.location.href='Shoppage.html'">
        Shop Now
    </button>
</section>
```

## Function

Displays promotional content and directs visitors to the shop.

## PHP Enhancement

```php
<?php

$conn = new mysqli("localhost","root","","thrift_trend");

$query = "SELECT * FROM promotions ORDER BY id DESC LIMIT 1";

$result = $conn->query($query);

$promo = $result->fetch_assoc();

?>

<section class="hero">

<h2><?php echo $promo['title']; ?></h2>

<p><?php echo $promo['description']; ?></p>

<a href="Shoppage.php">
<button>Shop Now</button>
</a>

</section>
```

### Explanation

* Retrieves promotional content from database.
* Allows admin to update homepage promotions without editing code.

---

# 3. Categories Section

### HTML Section

```html
<section class="categories">
```

## Function

Displays available product categories.

## Database Table

```sql
CREATE TABLE categories(
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_name VARCHAR(100),
    category_image VARCHAR(255)
);
```

## PHP Script

```php
<?php

$sql = "SELECT * FROM categories";

$result = $conn->query($sql);

?>

<section class="categories">

<h3>Quick Categories</h3>

<div class="grid">

<?php

while($row = $result->fetch_assoc())
{
?>

<div class="card">

<img src="uploads/<?php echo $row['category_image']; ?>"
width="140"
height="180">

<?php echo $row['category_name']; ?>

</div>

<?php
}
?>

</div>

</section>
```

### Explanation

* Reads categories from database.
* Categories can be added through an admin panel.
* No need to manually edit HTML whenever a category changes.

---

# 4. Trending Products Section

### HTML Section

```html
<section class="trending">
```

## Database Table

```sql
CREATE TABLE products(
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_name VARCHAR(200),
    product_image VARCHAR(255),
    status VARCHAR(50)
);
```

## PHP Script

```php
<?php

$query = "SELECT * FROM products
          WHERE status='Trending'
          LIMIT 6";

$result = $conn->query($query);

?>

<section class="trending">

<h3>Trending Now</h3>

<div class="grid">

<?php

while($product = $result->fetch_assoc())
{
?>

<div class="card">

<img src="uploads/<?php echo $product['product_image']; ?>"
width="140"
height="180">

<?php echo $product['product_name']; ?>

</div>

<?php
}
?>

</div>

</section>
```

### Explanation

* Fetches trending products from database.
* Admin can mark products as "Trending."
* Homepage updates automatically.

---

# 5. Why Choose Us Section

### HTML Section

```html
<section class="why-us">
```

## Function

Displays company advantages.

## PHP Version

```php
<?php

$benefits = [
    "Affordable Prices",
    "Sustainable Fashion",
    "Trusted Sellers"
];

?>

<section class="why-us">

<h3>Why Choose Us?</h3>

<div class="grid">

<?php

foreach($benefits as $benefit)
{
    echo "<div class='card'>$benefit</div>";
}

?>

</div>

</section>
```

### Explanation

* Uses an array to store company benefits.
* Easier to maintain and expand.

---

# 6. Newsletter Subscription Section

This is the main section that actually requires backend processing.

### Modified HTML

```html
<form action="subscribe.php" method="POST">

<input
type="email"
name="email"
placeholder="Enter your email"
required>

<button type="submit">
Subscribe
</button>

</form>
```

---

# subscribe.php

```php
<?php

$conn = new mysqli(
    "localhost",
    "root",
    "",
    "thrift_trend"
);

if($_SERVER["REQUEST_METHOD"] == "POST")
{
    $email = trim($_POST['email']);

    if(filter_var($email,FILTER_VALIDATE_EMAIL))
    {
        $stmt = $conn->prepare(
            "INSERT INTO subscribers(email)
             VALUES(?)"
        );

        $stmt->bind_param("s",$email);

        if($stmt->execute())
        {
            echo "Subscription Successful!";
        }
        else
        {
            echo "Subscription Failed!";
        }
    }
    else
    {
        echo "Invalid Email Address";
    }
}
?>
```

---

## Database Table

```sql
CREATE TABLE subscribers(
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) UNIQUE,
    subscribed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

### Explanation

* Receives submitted email.
* Validates email format.
* Stores subscriber email.
* Prevents duplicate subscriptions.
* Can later be used for marketing campaigns.

---

# 7. Database Connection File

Create **db_connect.php**

```php
<?php

$servername = "localhost";
$username = "root";
$password = "";
$database = "thrift_trend";

$conn = new mysqli(
    $servername,
    $username,
    $password,
    $database
);

if($conn->connect_error)
{
    die("Connection Failed: " .
        $conn->connect_error);
}

?>
```

### Function

This file centralizes database connectivity so all pages can simply use:

```php
include("db_connect.php");
```

instead of repeatedly writing connection code.

---

# 8. Suggested Additional Backend Files

For a complete Thrift & Trend marketplace, you would typically create:

| File                | Purpose                  |
| ------------------- | ------------------------ |
| register.php        | User registration        |
| login.php           | User authentication      |
| logout.php          | Session termination      |
| dashboard.php       | User account area        |
| add_product.php     | Seller product upload    |
| edit_product.php    | Product modification     |
| delete_product.php  | Product deletion         |
| shop.php            | Dynamic product display  |
| cart.php            | Shopping cart management |
| checkout.php        | Payment processing       |
| contact_process.php | Contact form handling    |
| blog_admin.php      | Blog post management     |
| subscribe.php       | Newsletter subscription  |

### Overall System Flow

1. Visitor enters Homepage.
2. PHP loads categories from database.
3. PHP loads trending products.
4. User logs in through Login Page.
5. Session is created.
6. User shops or sells products.
7. Newsletter emails are stored through `subscribe.php`.
8. Admin manages products, categories, blog posts, and subscribers through the database.

This transforms the static HTML homepage into a dynamic PHP-driven e-commerce marketplace backed by a MySQL database.
