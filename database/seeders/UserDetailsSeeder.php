<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Profile;
use App\Models\CarDetails;
use App\Models\CarServices;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserDetailsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //For insert Admin Data...................................
        $user = new User;
        $user->name = 'Admin';
        $user->email = 'admin@a.com';
        $user->phone_num = '01944444444444';
        $user->address = 'Lorem ipsum .';
        $user->accept_terms    = true;
        $user->role = 1;
        $user->email_verified_at = now();
        $user->password = Hash::make('Admin@123');
        $user->remember_token = Str::random(10);
        $user->save();

       //For insert Admin profile...................................
          $prof = new Profile;
          $prof->user_id             = $user->id;
        //   $prof->service_charge_id   = 1;
          $prof->save();
        

          //For insert Merchant User Data...................................
        $user = new User;
        $user->name = 'hasib';
        $user->email = 'hasib@h.com';
        $user->phone_num = '01944444444445';
        $user->address = 'Lorem ipsum ';
        $user->accept_terms    = true;
        $user->role = 0;
        $user->email_verified_at = now();
        $user->password = Hash::make('Hasib@123');
        $user->remember_token = Str::random(10);
        $user->save();

       //For insert Admin profile...................................
          $prof = new Profile;
          $prof->user_id             = $user->id;
          $prof->service_charge_id   = 1;
          $prof->save();

          //For insert Merchant User Data...................................
        $user = new User;
        $user->name = 'shayer';
        $user->email = 'shayer@s.com';
        $user->phone_num = '0194444444445';
        $user->address = 'Lorem ipsum, nisi.';
        $user->accept_terms    = true;
        $user->role = 0;
        $user->email_verified_at = now();
        $user->password = Hash::make('Shayer@123');
        $user->remember_token = Str::random(10);
        $user->save();

       //For insert Admin profile...................................
          $prof = new Profile;
          $prof->user_id             = $user->id;
          $prof->service_charge_id   = 1;
          $prof->save();

          //For insert Merchant User Data...................................
        $user = new User;
        $user->name = 'ismail';
        $user->email = 'ismail@i.com';
        $user->phone_num = '0194444444422';
        $user->address = 'Lorem ipsum isi.';
        $user->accept_terms    = true;
        $user->role = 0;
        $user->email_verified_at = now();
        $user->password = Hash::make('Ismail@123');
        $user->remember_token = Str::random(10);
        $user->save();

       //For insert Admin profile...................................
          $prof = new Profile;
          $prof->user_id             = $user->id;
          $prof->service_charge_id   = 1;
          $prof->save();

          //For insert Merchant User Data...................................
        $user = new User;
        $user->name = 'tamim';
        $user->email = 'tamim@t.com';
        $user->phone_num = '0194444444412';
        $user->address = 'Lorem ipsum dolor ';
        $user->accept_terms    = true;
        $user->role = 0;
        $user->email_verified_at = now();
        $user->password = Hash::make('Tamim@123');
        $user->remember_token = Str::random(10);
        $user->save();

       //For insert Admin profile...................................
          $prof = new Profile;
          $prof->user_id             = $user->id;
          $prof->service_charge_id   = 1;
          $prof->save();
          //For insert Merchant User Data...................................
        $user = new User;
        $user->name = 'mesad';
        $user->email = 'mesad@m.com';
        $user->phone_num = '0194444444512';
        $user->address = ' quia ab faciliiente, nisi.';
        $user->accept_terms    = true;
        $user->role = 0;
        $user->email_verified_at = now();
        $user->password = Hash::make('Mesad@123');
        $user->remember_token = Str::random(10);
        $user->save();

       //For insert Admin profile...................................
          $prof = new Profile;
          $prof->user_id             = $user->id;
          $prof->service_charge_id   = 1;
          $prof->save();


          //For insert Merchant User Data...................................
        $user = new User;
        $user->name = 'fahad';
        $user->email = 'fahad@f.com';
        $user->phone_num = '0194424444512';
        $user->address = 'm impedit numquam, incidunt quia';
        $user->accept_terms    = true;
        $user->role = 0;
        $user->email_verified_at = now();
        $user->password = Hash::make('Fahad@123');
        $user->remember_token = Str::random(10);
        $user->save();

       //For insert Admin profile...................................
          $prof = new Profile;
          $prof->user_id             = $user->id;
          $prof->service_charge_id   = 1;
          $prof->save();

          //For insert Merchant User Data...................................
        $user = new User;
        $user->name = 'abid';
        $user->email = 'abid@a.com';
        $user->phone_num = '0194424445512';
        $user->address = 'edit numquam, si.';
        $user->accept_terms    = true;
        $user->role = 3;
        $user->email_verified_at = now();
        $user->password = Hash::make('Abid@123');
        $user->remember_token = Str::random(10);
        $user->save();

       //For insert Car Details...................................
          $CarDetails = new CarDetails;
          $CarDetails->chassis_num          = "HS1-BUY-20015";
          $CarDetails->reg_num              = "reg-12451511";
          $CarDetails->model                = "2005";
          $CarDetails->insurance_image      = "not set Yet";
          $CarDetails->memo_image           = "not set Yet";
          $CarDetails->status               = 'busy';
          $CarDetails->save();

       //For insert Admin profile...................................
          $prof = new Profile;
          $prof->user_id             = $user->id;
          $prof->assign_car_id   = $CarDetails->id;
          $prof->save();

       //For insert CAr  ar services...................................
          $carService = new CarServices;
          $carService->car_id                   = $CarDetails->id;
          $carService->service_charge           = 2510;
          $carService->repair_hardware_name     = "motor spring";
          $carService->repair_hardware_price    = 3890;
          $carService->garage_name              = "kuddud ali gareg";
          $carService->garage_address           = "32/6 hatirGhil";
          $carService->garage_phone_num         = '0194424445512';
          $carService->save();
         

         //For insert Merchant User Data...................................
        $user = new User;
        $user->name = 'bindu';
        $user->email = 'bindu@b.com';
        $user->phone_num = '0194424445456';
        $user->address = 'edit numquam, si.';
        $user->accept_terms    = true;
        $user->role = 3;
        $user->email_verified_at = now();
        $user->password = Hash::make('Bindu@123');
        $user->remember_token = Str::random(10);
        $user->save();

       //For insert Car Details...................................


          $CarDetails = new CarDetails;
          $CarDetails->chassis_num          = "ARN-VURR-804545";
          $CarDetails->reg_num              = "reg-14559971";
          $CarDetails->model                = "2005";
          $CarDetails->insurance_image      = "not set Yet";
          $CarDetails->memo_image           = "not set Yet";
          $CarDetails->status               = 'busy';
          $CarDetails->save();

       //For insert Admin profile...................................
          $prof = new Profile;
          $prof->user_id             = $user->id;
          $prof->assign_car_id   = $CarDetails->id;
          $prof->save();

          
       //For insert CAr  ar services...................................
       $carService = new CarServices;
       $carService->car_id                   = $CarDetails->id;
       $carService->service_charge           = 1500;
       $carService->repair_hardware_name     = "Head Light";
       $carService->repair_hardware_price    = 2500;
       $carService->garage_name              = "kuddud ali gareg";
       $carService->garage_address           = "32/6 hatirGhil";
       $carService->garage_phone_num         = '01246444468';
       $carService->save();


         //For insert Merchant User Data...................................
        $user = new User;
        $user->name = 'neha';
        $user->email = 'neha@n.com';
        $user->phone_num = '0194424789956';
        $user->address = 'edit numquam, si.';
        $user->accept_terms    = true;
        $user->role = 2;
        $user->email_verified_at = now();
        $user->password = Hash::make('Neha@123');
        $user->remember_token = Str::random(10);
        $user->save();

       //For insert Car Details...................................


          $CarDetails = new CarDetails;
          $CarDetails->chassis_num          = "ARN-VURR-804545";
          $CarDetails->reg_num              = "reg-14559971";
          $CarDetails->model                = "2005";
          $CarDetails->insurance_image      = "not set Yet";
          $CarDetails->memo_image           = "not set Yet";
          $CarDetails->status               = 'free';
          $CarDetails->save();

       //For insert Admin profile...................................
          $prof = new Profile;
          $prof->user_id             = $user->id;
          $prof->save();

          
    }
}
