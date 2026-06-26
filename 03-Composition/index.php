<?php
    // Note: 
    //  The Code Has many Proplems and Bugs, this because i'm just Implement what i've learned in OOP..
    //  I will Fix it later in other lessons..
class Product {
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
    public function applayDiscount(float $percentage): void {
        $discount = $this->price * ($percentage / 100);
        $this->price -= $discount;
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

class User {
    private int $id;
    private string $name;
    private string $email;
    private string $phoneNumber;
    private array $orders; // New Added Line .. // Composition => User has Orders

    public function __construct(string $email,  string $phoneNumber, int $id ,string $name)
    {
        if(!$this->ValidateEmail($email)) return;
        if(!$this->validatePhoneNumber($phoneNumber)) return;

        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->phoneNumber = $phoneNumber;
        $this->orders = []; // New Line Added
    }

    public function changeEmail(string $newEmail): void {
        if($this->ValidateEmail($newEmail)) {
            $this->email = $newEmail;
        } else {
            echo "Invalid Email Enterd!";
        }
    }
    public function ValidateEmail(string $email) : bool {
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
    
    //New Method..
    public function addOrder(Order $order) : void {
        $this->orders[] = $order;
    }

}

class Order {
    private int $id;
    private string $status;
    private string $date;
    private array $products;
    private User $user;
    public function __construct(User $user)
    {
        $this->id = rand(1000,9999);
        $this->status = "Pending";
        $this->date = date("Y-m-d");
        $this->products = [];
        $this->user = $user;
    }

    public function addProduct(Product $product): void
    {
        //Code
    }
    public function calculatePrice() /*: float*/ {
        
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
    private float $price;
}