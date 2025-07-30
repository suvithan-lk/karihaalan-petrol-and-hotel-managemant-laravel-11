<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class OrdersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        $deliveryMethods = [
            "Standard Home Delivery",
            "Express Home Delivery",
            "Standard PickupPoint",
            "Express PickupPoint"
        ];
        $statuses = [
            "Pending",
            "Accepted",
            "Rejected",
            "Shipped",
            "Delivered",
            "Returned"
        ];
        $paymentMethods = [
            "Cash on Delivery",
            "Bank Deposite"
        ];
        $isPaidOptions = [
            "Paid",
            "Not Paid"
        ];

        for ($i = 0; $i < 50; $i++) {
            DB::table('orders')->insert([
                'order_num' => Str::random(10),
                'user_id' => $faker->numberBetween(1, 100),
                'shipping_cost' => $faker->randomFloat(2, 0, 100),
                'total_cost' => $faker->randomFloat(2, 0, 500),
                'delivery_method' => $faker->randomElement($deliveryMethods),
                'phone' => $faker->phoneNumber,
                'address' => $faker->address,
                'status' => $faker->randomElement($statuses),
                'payment_method' => $faker->randomElement($paymentMethods),
                'is_Paid' => $faker->randomElement($isPaidOptions),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
