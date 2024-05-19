<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:admin_login.php');
}

if (isset($_POST['update_payment'])) {
    $order_id = $_POST['order_id'];
    $payment_status = $_POST['payment_status'];
    $payment_status = filter_var($payment_status, FILTER_SANITIZE_STRING);
    $update_payment = $conn->prepare("UPDATE `orders` SET payment_status = ? WHERE id = ?");
    $update_payment->execute([$payment_status, $order_id]);
    $message[] = 'payment status updated!';
}

if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    $delete_order = $conn->prepare("DELETE FROM `orders` WHERE id = ?");
    $delete_order->execute([$delete_id]);
    header('location:placed_orders.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Placed Orders</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="../css/admin_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/css/bootstrap.min.css">
</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="orders">

<h1 class="heading">Placed Orders</h1>

<div class="box-container">

    <?php
    $status = "Pending";
    $select_orders = $conn->prepare("SELECT * FROM `orders`WHERE payment_status = ?");
    $select_orders->execute([$status]);
    if ($select_orders->rowCount() > 0) {
        while ($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)) {
    ?>
    <div class="box">
        <p> Placed On : <span><?= $fetch_orders['placed_on']; ?></span> </p>
        <p> Name : <span><?= $fetch_orders['name']; ?></span> </p>
        <p> Number : <span><?= $fetch_orders['number']; ?></span> </p>
        <p> Address : <span><?= $fetch_orders['address']; ?></span> </p>
        <p> Total products : <span><?= $fetch_orders['total_products']; ?></span> </p>
        <p> Total price : <span>Php.<?= $fetch_orders['total_price']; ?></span> </p>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#receiptModal" data-receipt="<?= $fetch_orders['receipt']; ?>" style="height:30px;font-size: 15px;">
            View Receipt
        </button>
        <form action="" method="post">
            <input type="hidden" name="order_id" value="<?= $fetch_orders['id']; ?>">
            <p>Order Status</p>
            <select name="payment_status" class="select">
                <option selected disabled><?= $fetch_orders['payment_status']; ?></option required>
                <option value="Pending">Pending</option>
                <option value="To Process">To Process</option>
            </select>
            <div class="flex-btn">
                <input type="submit" value="update" class="option-btn" name="update_payment">
            </div>
        </form>
    </div>
    <?php
        }
    } else {
        echo '<p class="empty">no orders placed yet!</p>';
    }
    ?>

</div>

</section>

<!-- Modal -->
<div class="modal fade" id="receiptModal" tabindex="-1" aria-labelledby="receiptModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="receiptModalLabel">Receipt</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <img id="receiptImage" src="" class="img-fluid" alt="Receipt Image">
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var receiptModal = document.getElementById('receiptModal');
    receiptModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        var receipt = button.getAttribute('data-receipt');
        var receiptImage = document.getElementById('receiptImage');
        receiptImage.src = '../receipt/' + receipt;
    });
});
</script>

<script src="../js/admin_script.js"></script>

</body>
</html>
