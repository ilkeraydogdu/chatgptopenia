<?php

class OrderController
{
    protected $orderModel;

    public function __construct()
    {
        $this->orderModel = new Order();
    }

    // Sipariş listeleme
    public function index()
    {
        $orders = $this->orderModel->getAllOrders();
        include_once __DIR__ . '/../views/order_list.php';
    }

    // Sipariş detayı
    public function show($orderId)
    {
        $order = $this->orderModel->getOrderById($orderId);
        if ($order) {
            include_once __DIR__ . '/../views/order_detail.php';
        } else {
            echo "Sipariş bulunamadı!";
        }
    }

    // Sipariş ekleme
    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'user_id' => $_POST['user_id'],
                'products' => $_POST['products'],  // JSON formatında ürünler
                'total_price' => $_POST['total_price'],
                'status' => 'pending',
                'notes' => $_POST['notes'] ?? null,
            ];
            $this->orderModel->createOrder($data);
            header("Location: /order/success");
        } else {
            include_once __DIR__ . '/../views/order_create.php';
        }
    }

    // Sipariş güncelleme
    public function update($orderId)
    {
        $order = $this->orderModel->getOrderById($orderId);
        if (!$order) {
            echo "Sipariş bulunamadı!";
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'status' => $_POST['status'],
                'notes' => $_POST['notes'] ?? $order['notes'],
            ];
            $this->orderModel->updateOrder($orderId, $data);
            header("Location: /order/$orderId");
        } else {
            include_once __DIR__ . '/../views/order_edit.php';
        }
    }

    // Sipariş silme
    public function delete($orderId)
    {
        $order = $this->orderModel->getOrderById($orderId);
        if ($order) {
            $this->orderModel->deleteOrder($orderId);
            header("Location: /order");
        } else {
            echo "Sipariş bulunamadı!";
        }
    }

    // Sipariş durumu güncelleme
    public function updateStatus($orderId, $status)
    {
        $order = $this->orderModel->getOrderById($orderId);
        if ($order) {
            $this->orderModel->updateOrder($orderId, ['status' => $status]);
            header("Location: /order/$orderId");
        } else {
            echo "Sipariş bulunamadı!";
        }
    }
}
