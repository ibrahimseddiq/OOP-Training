<?php
    // Note: 
    //  The Code Has many Proplems and Bugs, this because i'm just Implement what i've learned in OOP..
    //  I will Fix it later in other lessons..

class Product implements IDiscountable{
    private int $id;
    private string $name;
    private float $price;
    private int $stock;

    public function __construct(float $price, int $stock, string $name, int $id )
    {
        if($price < 0) {
            echo "Price Cannot be Negative";
            return;
            }
        
        if($stock < 0) {
            echo "Stock cannot be negative";
            return;
        }

        $this->id = $id;
        $this->name = $name;
        $this->stock = $stock;
        $this->price = $price;

    }
    public function increasePrice(float $amount) : void {
        if($amount < 0) return;

        $this->price += $amount;
    }
    public function applyFirstDiscount(float $percentage): void {
        $discount = $this->price * ($percentage / 100);
        $this->price -= $discount;
    }
    public function applyDiscount() {
        // Discount Logic
    }
    public function increaseStock(int $quantity): void {
        if ($quantity <= 0) {
            return;
        }

        $this->stock += $quantity;
    }

    public function decreaseStock(int $quantity): void {
        
        if ($quantity <= 0) {
            return;
        }

        if ($quantity > $this->stock) {
            return;
        }

        $this->stock -= $quantity;
    }

    public function getPrice() : float {
        return $this->price;
    }

    public function getStock() : int {
        return $this->stock;
    }
    public function getId() : int {
        return $this->id;
    }
    public function getName() : string {
        return $this->name;
    }
}

class DigitalProduct extends Product { // DigitalProduct not have a Stock
    private string $downloadLink;

    public function __construct(float $price, int $stock, string $name, int $id, string $downloadLink)
    {
        parent::__construct($price, $stock, $name, $id);
        $this->downloadLink = $downloadLink;
    }
    
    // Override
    public function decreaseStock(int $quantity): void
    {
        echo "Digital products don't have stock";
        
    }
    public function increaseStock(int $quantity): void
    {
        echo "Digital products don't have stock";
        
    }
}

class User implements IDiscountable {
    private int $id;
    private string $name;
    private string $email;
    private string $phoneNumber;
    private array $orders;

    public function __construct(string $email,  string $phoneNumber, int $id ,string $name)
    {
        if(!$this->validateEmail($email)) return;
        if(!$this->validatePhoneNumber($phoneNumber)) return;

        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->phoneNumber = $phoneNumber;
        $this->orders = [];
    }

    public function changeEmail(string $newEmail): void {
        if($this->validateEmail($newEmail)) {
            $this->email = $newEmail;
        } else {
            echo "Invalid Email Enterd!";
        }
    }
    public function validateEmail(string $email) : bool {
        if(filter_var($email,FILTER_VALIDATE_EMAIL)) {
            return true;
        }
        return false;
    }
    public function validatePhoneNumber(string $phone) {

        $phone = preg_replace('/[\s-]/', '', $phone); // Cleaning the Phone From Spaces(s) and Dashes(-)..

        $pattern = '/^(010|011|012|015)[0-9]{8}$/';

        return preg_match($pattern,$phone);
    }

    public function changePhone(string $newPhone) : void {
        
        if($this->validatePhoneNumber($newPhone)) { //Object Behavior Collaboration
            $this->phoneNumber = $newPhone;
        } else {
            echo "Invalid Phone Number Enterd!";
        }
    }
    
    public function getName() : string {
        return $this->name;
    }
    
    public function getId() : int {
        return $this->id;
    }
        public function getEmail() : string {
        return $this->email;
    }

    public function getPhoneNumber() : string {
        return $this->phoneNumber;
    }
    
    public function addOrder(Order $order) : void {
        $this->orders[] = $order;
    }

    public function applyDiscount() {
        // Discount Logic
    }
}

class Admin extends User {
    private string $role;

    public function __construct(string $email,  string $phoneNumber, int $id ,string $name, string $role)
    {
        parent::__construct($email, $phoneNumber, $id, $name);
        $this->role = $role;
    }
    public function getRole() : string {
        return $this->role;
    }
}

class Seller extends User {
    
}

class Order {
    private int $id;
    private string $status;
    private string $date;
    private array $orderItems;
    private User $user;
    public function __construct(User $user)
    {
        $this->id = rand(1000,9999);
        $this->status = "Pending";
        $this->date = date("Y-m-d");
        $this->orderItems = [];
        $this->user = $user;
    }

    public function addOrderItem(OrderItem $orderitem): void
    {
        $this->orderItems[] = $orderitem;
    }
    public function calculatePrice() : float {
        $orderPrice = 0;
        foreach ($this->orderItems as  $item) {
            $orderPrice += $item->getTotalPrice();
        }
        return $orderPrice;
    }
    public function EnterPromoCode(string $code): void {
        /*
        $data = file_get_contents('promo.json');

        $promoCodes = json_decode($data, true); // promoCode is an Array Because of "true"

        if(isset($promoCodes[$code])) {
            
            $discount = $this->orderPrice * ($promoCodes[$code] / 100);
            $this->orderPrice -= $discount;
            echo "Order_Price After Discounting $promoCodes[$code]%: " . $this->orderPrice;
        
            } else {
            echo "Invalid Code";
        }
        */
    }

    public function getID(): int {
        return $this->id;
    }
    public function getStatus(): string {
        return $this->status;
    }
    public function getDate(): string {
        return $this->date;
    }
    public function getUser(): User {
        return $this->user;
    }

}

class OrderItem {
    private Product $product; // Composition => OrderItem has Product..
    private int $quantity;
    private float $totalPrice;
    public function __construct(Product $product, int $quantity)
    {
        if($quantity <= 0) return;
        $this->product = $product;
        $this->quantity = $quantity;
    }

    public function getTotalPrice() : float {
        $this->totalPrice = $this->product->getPrice() * $this->quantity;
        return $this->totalPrice;
    }
}

abstract class Payment {
    private float $amount;
    private string $status;
    private string $date;
    abstract public function pay();
    abstract public function getSuccessMessage(): string;
    public function __construct(Order $order)
    {
        $this->status = "Pending";
        $this->date = date("Y-m-d");
        $this->amount = $order->calculatePrice();
    }
    public function getAmount() : float {
        return $this->amount;
    }
    public function getStatus() : string {
        return $this->status;
    }
    public function getDate() : string {
        return $this->date;
    }
}
class VisaPayment extends Payment {
    private string $transactionID;
    private int $lastFourNumber;

    public function __construct(Order $order, int $lastFourNumber, string $transactionID)
    {
        parent::__construct($order);
        $this->lastFourNumber = $lastFourNumber;
        $this->transactionID = $transactionID;
    }

    public function pay() {
        // Add Visa Pay Logic
    }
    public function getSuccessMessage(): string {
        return "Payment completed using Visa.";
    }
    public function getTransactionID() : string {
        return $this->transactionID;
    }
    public function getLastFourNumber() : int {
        return $this->lastFourNumber;
    }
}
class WalletPayment extends Payment implements IDiscountable{
    private string $transactionID;
    private int $walletNumber;

    public function __construct(Order $order, int $walletNumber, string $transactionID)
    {
        parent::__construct($order);
        $this->walletNumber = $walletNumber;
        $this->transactionID = $transactionID;
    }

    public function pay() {
        // Add Wallet Pay Logic
    }
    public function getSuccessMessage(): string {
        return "Payment completed using Wallet.";
    }
    public function applyDiscount() {
        // Discount Logic
    }
    public function getTransactionID() : string {
        return $this->transactionID;
    }
    public function getLastFourNumber() : int {
        return $this->walletNumber;
    }
}
class ApplePayment extends Payment {
    private string $transactionID;

    public function __construct(Order $order, string $transactionID)
    {
        parent::__construct($order);
        $this->transactionID = $transactionID;
    }
    public function pay() {
        // Add Apple Pay Logic
    }
    public function getSuccessMessage(): string {
        return "Payment completed using ApplePay.";
    }
    public function getTransactionID() : string {
        return $this->transactionID;
    }
}
class CashPayment extends Payment {
    
    public function pay() {
        // Add Cash Pay Logic
    }
    public function getSuccessMessage(): string {
        return "Cash payment registered.";
    }
}
class PaymentService
{
    public function processPayment(Payment $payment)
    {
        $payment->pay();
        echo $payment->getSuccessMessage();
    }
}

interface IDiscountable {
    public function applyDiscount();
}