<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Client;
use App\Models\Department;

class ClientSeeder extends Seeder
{
    public function run(): void
    {
        // Disable foreign key checks for safe truncation
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Client::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Map department name => department id
        $departments = Department::pluck('id','department')->toArray();

        // Array of clients from your CSV
        $clients = [
            [
                'client_name' => 'Abdikarim Ismail Mohamed',
                'company_name' => 'GIZ',
                'phone' => '+252 (0) 634488573',
                'email' => 'abdikarim.mohamed@giz.de',
                'address' => 'BAIDOA',
                'department' => 'Camp & Accom - Baidoa',
            ],
            [
                'client_name' => 'Aakriti Pandey',
                'company_name' => 'UNSOS',
                'phone' => null,
                'email' => null,
                'address' => 'Mogadishu-Somalia',
                'department' => 'Camp & Accomodation',
            ],
            [
                'client_name' => 'Allin Abdifatah-Giz Rmo',
                'company_name' => 'GIZ-SOMALIA',
                'phone' => '+252634425292',
                'email' => 'abdifatah.allin@giz.de',
                'address' => 'SKA-COMPOUND AAIA-SOMALIA',
                'department' => 'Construction',
            ],
            [
                'client_name' => 'Abdalla Rashid',
                'company_name' => 'UNOPS',
                'phone' => null,
                'email' => null,
                'address' => 'MOGADISHU - SOMALIA',
                'department' => 'DFAC',
            ],
            [
                'client_name' => 'Capt. Sam Carpenter',
                'company_name' => 'UKSST',
                'phone' => '+252610751626',
                'email' => 'crisis.deployeduser294@mod.gov.uk',
                'address' => 'Baidoa',
                'department' => 'DFAC Baidoa',
            ],
            [
                'client_name' => 'Alan Mckenna',
                'company_name' => 'CODNAN',
                'phone' => '+252613980576',
                'email' => 'alan.mckenna@codnancomms.com',
                'address' => null,
                'department' => 'Life Support Services',
            ],
            [
                'client_name' => 'Action Express International – Aei Jordan W.L.L.',
                'company_name' => 'Action Express International – AEI Jordan W.L.L.',
                'phone' => '+962 6 462 18 08',
                'email' => 'raja.dobaisi@jo.danzasjo.com',
                'address' => 'P.O.Box 910 603 Amman 11191 8, Khareja Al Ashjae Street, Peace Building - Jabal Luweibdeh - Jordan',
                'department' => 'Logistics',
            ],
            [
                'client_name' => 'British Embassy Mogadishu/Bakery Sales',
                'company_name' => 'BRITISH EMBASSSY MOGADISHU',
                'phone' => 'N/A',
                'email' => 'N/A',
                'address' => 'MGQ',
                'department' => 'PX',
            ],
            [
                'client_name' => 'Professor Yahya H Ibrahim',
                'company_name' => 'British Embassy-(Rupert Compston)',
                'phone' => '+252 624036788 / +44 7385 348886',
                'email' => 'rupert.compston@fcdo.gov.uk',
                'address' => 'Rupert Compston | Governance Advisor | British Embassy Mogadishu',
                'department' => 'SKA Risk Management',
            ],
            [
                'client_name' => 'Abdi Abdullahi',
                'company_name' => 'ECHO-SOMALIA',
                'phone' => '0616437319',
                'email' => 'abdiabdullahi@echofield.eu',
                'address' => 'Mogadishu-Somalia',
                'department' => 'Toyota',
            ],
            [
                'client_name' => 'Cash Customer',
                'company_name' => 'BAKERY',
                'phone' => null,
                'email' => null,
                'address' => null,
                'department' => 'Vees Lounge',
            ],
            [
                'client_name' => 'Abdi Adan',
                'company_name' => 'ASAL AIRPORT SERVICES',
                'phone' => '+252-614960888',
                'email' => 'info@asalairportservices.so',
                'address' => 'BAIDOA',
                'department' => 'Workshop Baidoa',
            ],
        ];

        // Loop through each client and create in DB
        foreach ($clients as $c) {

            // Get department_id, if department not found set null
            $department_id = $departments[$c['department']] ?? null;

            // Create client record
            Client::create([
                'department_id' => $department_id,
                'client_name'   => $c['client_name'],
                'company_name'  => $c['company_name'],
                'phone'         => $c['phone'],
                'email'         => $c['email'],
                'address'       => $c['address'],
                'is_active'     => true, // mark active by default
            ]);

            // Output progress
            $this->command->info("✓ Created client {$c['client_name']}");
        }

        $this->command->info('✓ All clients seeded successfully');
    }
}
