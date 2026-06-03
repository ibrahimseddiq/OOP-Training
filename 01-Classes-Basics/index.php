<?php

class Product {
    public int $id;
    public string $name;
    public float $price;
    public int $stock;

    public function applayDiscount(float $percentage): void {
        $discount = $this->price * ($percentage / 100);
        $this->price -= $discount;
    }

    public function increaseStock(int $quantity): void {
        $this->stock += $quantity;
    }

    public function decreaseStock(int $quantity): void {
        $this->stock -= $quantity;
    }
}

class User {
    public int $id;
    public string $name;
    public string $email;
    public string $phoneNumber;

    public function changeEmail(string $newEmail): void {
        if(filter_var($newEmail,FILTER_VALIDATE_EMAIL)) {
            $this->email = $newEmail;
        } else {
            echo "Invalid Email Enterd!";
        }
    }
    public function validatePhoneNumber(string $phone) {

        $phone = preg_replace('/[\s-]/', '', $phone); // Cleaning the Phone From Spaces(s) and Dashes(-)..

        $pattern = '/^(010|011|012|015)[0-9]{8}$/';

        return preg_match($pattern,$phone);
    }

    public function changePhone(string $newPhone) : void {
        
        if($this->validatePhoneNumber($newPhone)) {
            $this->phoneNumber = $newPhone;
        } else {
            echo "Invalid Phone Number Enterd!";
        }
    }
    
}

class Order {
    public int $id;
    public string $status;
    public string $date;
    public float $orderPrice;

    

    public function EnterPromoCode(string $code): void {

        $data = file_get_contents('promo.json');

        $promoCodes = json_decode($data, true); // promoCode is an Array Because of "true"

        if(isset($promoCodes[$code])) {
            $discount = $this->orderPrice * ($promoCodes[$code] / 100);
            $this->orderPrice -= $discount;
            echo "Order_Price After Discounting $promoCodes[$code]%: " . $this->orderPrice;
        } else {
            echo "Invalid Code";
        }
    }
    
}