<?php

namespace Database\Seeders;

use App\Models\IoTKit;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // Owner akun demo yang mengelola akun asisten lab.
        User::create([
            'name' => 'Budi Santoso',
            'email' => 'admin@lab.com',
            'password' => Hash::make('LabIoT2026!'),
            'role' => 'owner',
        ]);

        // 2 Mahasiswa
        User::create([
            'name' => 'Andi Pratama',
            'email' => 'mhs1@lab.com',
            'password' => Hash::make('LabIoT2026!'),
            'role' => 'mahasiswa',
        ]);

        User::create([
            'name' => 'Siti Nurhaliza',
            'email' => 'mhs2@lab.com',
            'password' => Hash::make('LabIoT2026!'),
            'role' => 'mahasiswa',
        ]);

        // 5 IoT Kits dengan lokasi penyimpanan
        IoTKit::create([
            'code' => 'KIT-ESP32-001',
            'name' => 'ESP32 Starter Kit Complete',
            'category' => 'Microcontroller',
            'storage_location' => 'Lab IoT 1 - Rak A1',
            'stock' => 10,
            'status' => 'AVAILABLE',
        ]);
        IoTKit::create([
            'code' => 'KIT-SEN-001',
            'name' => 'Sensor Pack Basic (DHT22, LDR, PIR)',
            'category' => 'Sensor Pack',
            'storage_location' => 'Lab IoT 1 - Rak A2',
            'stock' => 15,
            'status' => 'AVAILABLE',
        ]);
        IoTKit::create([
            'code' => 'KIT-ACT-001',
            'name' => 'Actuator Pack Core (Servo, Relay, Motor)',
            'category' => 'Actuator Pack',
            'storage_location' => 'Lab IoT 2 - Rak B1',
            'stock' => 5,
            'status' => 'AVAILABLE',
        ]);
        IoTKit::create([
            'code' => 'KIT-RPI-001',
            'name' => 'Raspberry Pi 4 Model B 4GB',
            'category' => 'Microcomputer',
            'storage_location' => 'Lab IoT 2 - Rak B3',
            'stock' => 3,
            'status' => 'AVAILABLE',
        ]);
        IoTKit::create([
            'code' => 'KIT-ARD-001',
            'name' => 'Arduino Uno R3 Starter',
            'category' => 'Microcontroller',
            'storage_location' => 'Lab IoT 1 - Rak A3',
            'stock' => 20,
            'status' => 'AVAILABLE',
        ]);
    }
}
