<?php

namespace Database\Seeders;

use App\Models\PhBarangay;
use App\Models\PhCity;
use App\Models\PhProvince;
use Illuminate\Database\Seeder;

class PhilippineAddressSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Seeding Philippine address data...');

        foreach ($this->getData() as $region => $provinces) {
            foreach ($provinces as $provinceName => $cities) {
                $province = PhProvince::firstOrCreate(
                    ['name' => $provinceName],
                    ['region_name' => $region]
                );

                foreach ($cities as $cityName => $barangays) {
                    $city = PhCity::firstOrCreate(
                        ['name' => $cityName, 'province_id' => $province->id]
                    );

                    if (is_array($barangays)) {
                        foreach ($barangays as $barangayName) {
                            PhBarangay::firstOrCreate([
                                'name' => $barangayName,
                                'city_id' => $city->id,
                            ]);
                        }
                    }
                }
            }
        }

        $this->command->info('Philippine address data seeded successfully!');
        $this->command->info('Provinces: '.PhProvince::count());
        $this->command->info('Cities/Municipalities: '.PhCity::count());
        $this->command->info('Barangays: '.PhBarangay::count());
    }

    private function getData(): array
    {
        return [
            'NCR (National Capital Region)' => [
                'Metro Manila' => [
                    'City of Manila' => ['Binondo', 'Ermita', 'Intramuros', 'Malate', 'Paco', 'Pandacan', 'Port Area', 'Quiapo', 'Sampaloc', 'San Andres', 'San Miguel', 'San Nicolas', 'Santa Ana', 'Santa Cruz', 'Santa Mesa', 'Tondo'],
                    'Quezon City' => ['Alicia', 'Bagong Pag-Asa', 'Bahay Toro', 'Balingasa', 'Batasan Hills', 'Commonwealth', 'Culiat', 'Damayang Lagi', 'Diliman', 'Fairview', 'Holy Spirit', 'Kamuning', 'Katipunan', 'Krus Na Ligas', 'Loyola Heights', 'New Manila', 'Novaliches', 'Pinyahan', 'Project 6', 'Sangandaan', 'Sikatuna Village', 'South Triangle', 'Tandang Sora', 'Teachers Village', 'U.P. Campus', 'Vasra', 'West Triangle'],
                    'Caloocan City' => ['Bagong Barrio', 'Camarin', 'Grace Park', 'Maypajo', 'Sangandaan', 'Tala', 'Deparo'],
                    'Las Piñas City' => ['Almanza Uno', 'Almanza Dos', 'B.F. International Village', 'Daniel Fajardo', 'Elias Aldana', 'Ilaya', 'Manuyo Uno', 'Manuyo Dos', 'Pamplona Uno', 'Pamplona Dos', 'Pamplona Tres', 'Pilar', 'Pulang Lupa Uno', 'Pulang Lupa Dos', 'Talon Uno', 'Talon Dos', 'Talon Tres', 'Talon Kuatro', 'Talon Singko', 'Zapote'],
                    'Makati City' => ['Bangkal', 'Bel-Air', 'Carmona', 'Cembo', 'Comembo', 'Dasmariñas', 'East Rembo', 'Forbes Park', 'Guadalupe Nuevo', 'Guadalupe Viejo', 'Kasilawan', 'La Paz', 'Magallanes', 'Olympia', 'Palanan', 'Pembo', 'Pinagkaisahan', 'Pio del Pilar', 'Poblacion', 'Post Proper Northside', 'Post Proper Southside', 'Rizal', 'San Antonio', 'San Isidro', 'San Lorenzo', 'Santa Cruz', 'Singkamas', 'South Cembo', 'Tejeros', 'Urdaneta', 'Valenzuela', 'West Rembo'],
                    'Malabon City' => ['Acacia', 'Baritan', 'Bayan-Bayanan', 'Catmon', 'Concepcion', 'Dampalit', 'Flores', 'Hulong Duhat', 'Ibaba', 'Longos', 'Maysilo', 'Muzon', 'Niugan', 'Panghulo', 'Potrero', 'San Agustin', 'Santolan', 'Tañong', 'Tinajeros', 'Tonsuya', 'Tugatog'],
                    'Mandaluyong City' => ['Addition Hills', 'Bagong Silang', 'Barangka Drive', 'Barangka Ibaba', 'Barangka Ilaya', 'Buayang Bato', 'Highway Hills', 'Hulo', 'Mabini-J. Rizal', 'Malamig', 'Mauway', 'Namayan', 'New Zaniga', 'Old Zaniga', 'Pag-Asa', 'Plainview', 'Pleasant Hills', 'Poblacion', 'San Jose', 'Vergara', 'Wack-Wack Greenhills'],
                    'Marikina City' => ['Barangka', 'Calumpang', 'Concepcion Dos', 'Concepcion Uno', 'Fortune', 'Industrial Valley', 'Jesus Dela Peña', 'Malanday', 'Nangka', 'Parang', 'San Roque', 'Santa Elena', 'Santo Niño', 'Tañong', 'Tumana'],
                    'Muntinlupa City' => ['Alabang', 'Ayala Alabang', 'Bayanan', 'Buli', 'Cupang', 'New Alabang Village', 'Poblacion', 'Putatan', 'Sucat', 'Tunasan'],
                    'Navotas City' => ['Bagumbayan North', 'Bagumbayan South', 'Bangculasi', 'Daanghari', 'Navotas East', 'Navotas West', 'North Bay Blvd. North', 'North Bay Blvd. South', 'San Jose', 'San Roque', 'Sipac-Almacen', 'Tangos', 'Tanza'],
                    'Parañaque City' => ['B.F. Homes', 'Baclaran', 'Don Bosco', 'Don Galo', 'La Huerta', 'Marcelo Green Village', 'Merville', 'Moonwalk', 'San Antonio', 'San Dionisio', 'San Isidro', 'San Martin de Porres', 'Santo Niño', 'Sun Valley', 'Tambo', 'Vitalez'],
                    'Pasay City' => ['Baclaran', 'Cay Pombo', 'Malibay', 'Maricaban', 'Pasay City Proper', 'San Isidro', 'San Jose', 'San Rafael', 'San Roque', 'Santa Clara', 'Santo Niño', 'Tramo', 'Villamor Airbase'],
                    'Pasig City' => ['Bagong Ilog', 'Bagong Katipunan', 'Caniogan', 'Dela Paz', 'Kalawaan', 'Kapitolyo', 'Manggahan', 'Maybunga', 'Oranbo', 'Palatiw', 'Pinagbuhatan', 'Rosario', 'Sagad', 'San Antonio', 'San Joaquin', 'San Jose', 'San Miguel', 'Santa Cruz', 'Santa Lucia', 'Santa Rosa', 'Santo Tomas', 'Santolan', 'Sumilang', 'Ugong'],
                    'Pateros' => ['Aguho', 'Magtanggol', 'Martelo', 'Poblacion', 'San Pedro', 'San Roque', 'Santa Ana', 'Santo Rosario-Kanluran', 'Santo Rosario-Silangan', 'Tabacalera'],
                    'San Juan City' => ['Addition Hills', 'Balong-Bato', 'Batis', 'Corazon de Jesus', 'Ermitaño', 'Greenhills', 'Isabelita', 'Kabayanan', 'Little Baguio', 'Maytunas', 'Onse', 'Pasadena', 'Pedro Cruz', 'Progreso', 'Rivera', 'Salapan', 'San Perfecto', 'Santa Lucia', 'St. Joseph', 'Tibagan', 'West Crame'],
                    'Taguig City' => ['Bagumbayan', 'Bambang', 'Calzada', 'Central Bicutan', 'Central Signal Village', 'Fort Bonifacio', 'Hagonoy', 'Ibayo-Tipas', 'Ligid-Tipas', 'Lower Bicutan', 'Maharlika Village', 'New Lower Bicutan', 'North Daang Hari', 'North Signal Village', 'Palingon', 'Pinagsama', 'San Martin de Porres', 'Santa Ana', 'South Daang Hari', 'South Signal Village', 'Tanyag', 'Tuktukan', 'Upper Bicutan', 'Ususan', 'Wawa', 'Western Bicutan'],
                    'Valenzuela City' => ['Arkong Bato', 'Bagbaguin', 'Balangkas', 'Bignay', 'Bisig', 'Canumay East', 'Canumay West', 'Coloong', 'Dalandanan', 'Gen. T. de Leon', 'Isla', 'Karuhatan', 'Lawang Bato', 'Lingunan', 'Mabolo', 'Malanday', 'Malinta', 'Mapulang Lupa', 'Marulas', 'Maysan', 'Parada', 'Pariancillo Villa', 'Paso de Blas', 'Pasolo', 'Poblacion', 'Pulo', 'Punturin', 'Rincon', 'Tagalag', 'Ugong', 'Viente Reales', 'Wawang Pulo'],
                ],
            ],
            'CAR (Cordillera Administrative Region)' => [
                'Abra' => [
                    'Bangued' => [], 'Boliney' => [], 'Bucay' => [], 'Bucloc' => [], 'Daguioman' => [],
                    'Danglas' => [], 'Dolores' => [], 'La Paz' => [], 'Lacub' => [], 'Lagangilang' => [],
                    'Lagayan' => [], 'Langiden' => [], 'Licuan-Baay' => [], 'Luba' => [], 'Malibcong' => [],
                    'Manabo' => [], 'Peñarrubia' => [], 'Pidigan' => [], 'Pilar' => [], 'Sallapadan' => [],
                    'San Isidro' => [], 'San Juan' => [], 'San Quintin' => [], 'Tayum' => [], 'Tineg' => [],
                    'Tubo' => [], 'Villaviciosa' => [],
                ],
                'Apayao' => [
                    'Calanasan' => [], 'Conner' => [], 'Flora' => [], 'Kabugao' => [],
                    'Luna' => [], 'Pudtol' => [], 'Santa Marcela' => [],
                ],
                'Benguet' => [
                    'Baguio City' => [], 'Atok' => [], 'Bakun' => [], 'Bokod' => [], 'Buguias' => [],
                    'Itogon' => [], 'Kabayan' => [], 'Kapangan' => [], 'Kibungan' => [], 'La Trinidad' => [],
                    'Mankayan' => [], 'Sablan' => [], 'Tuba' => [], 'Tublay' => [],
                ],
                'Ifugao' => [
                    'Aguinaldo' => [], 'Alfonso Lista' => [], 'Asipulo' => [], 'Banaue' => [],
                    'Hingyon' => [], 'Hungduan' => [], 'Kiangan' => [], 'Lagawe' => [],
                    'Lamut' => [], 'Mayoyao' => [], 'Tinoc' => [],
                ],
                'Kalinga' => [
                    'Balbalan' => [], 'Lubuagan' => [], 'Pasil' => [], 'Pinukpuk' => [],
                    'Rizal' => [], 'Tabuk City' => [], 'Tanudan' => [], 'Tinglayan' => [],
                ],
                'Mountain Province' => [
                    'Barlig' => [], 'Bauko' => [], 'Besao' => [], 'Bontoc' => [],
                    'Natonin' => [], 'Paracelis' => [], 'Sabangan' => [], 'Sadanga' => [],
                    'Sagada' => [], 'Tadian' => [],
                ],
            ],
            'Region I (Ilocos Region)' => [
                'Ilocos Norte' => [
                    'Laoag City' => [], 'Batac City' => [], 'Adams' => [], 'Bacarra' => [],
                    'Badoc' => [], 'Bangui' => [], 'Banna' => [], 'Burgos' => [],
                    'Carasi' => [], 'Currimao' => [], 'Dingras' => [], 'Dumalneg' => [],
                    'Marcos' => [], 'Nueva Era' => [], 'Pagudpud' => [], 'Paoay' => [],
                    'Pasuquin' => [], 'Piddig' => [], 'Pinili' => [], 'San Nicolas' => [],
                    'Sarrat' => [], 'Solsona' => [], 'Vintar' => [],
                ],
                'Ilocos Sur' => [
                    'Vigan City' => [], 'Candon City' => [], 'Bantay' => [], 'Cabugao' => [],
                    'Caoayan' => [], 'Cervantes' => [], 'Galimuyod' => [], 'Gregorio Del Pilar' => [],
                    'Lidlidda' => [], 'Magsingal' => [], 'Nagbukel' => [], 'Narvacan' => [],
                    'Quirino' => [], 'San Esteban' => [], 'San Ildefonso' => [], 'San Juan' => [],
                    'San Vicente' => [], 'Santa' => [], 'Santa Catalina' => [], 'Santa Cruz' => [],
                    'Santa Lucia' => [], 'Santa Maria' => [], 'Santiago' => [], 'Santo Domingo' => [],
                    'Sigay' => [], 'Sinait' => [], 'Sugpon' => [], 'Suyo' => [],
                    'Tagudin' => [],
                ],
                'La Union' => [
                    'San Fernando City' => [], 'Agoo' => [], 'Aringay' => [], 'Bacnotan' => [],
                    'Bagulin' => [], 'Balaoan' => [], 'Bangar' => [], 'Bauang' => [],
                    'Burgos' => [], 'Caba' => [], 'Luna' => [], 'Naguilian' => [],
                    'Pugo' => [], 'Rosario' => [], 'San Gabriel' => [], 'San Juan' => [],
                    'Santo Tomas' => [], 'Santol' => [], 'Sudipen' => [], 'Tubao' => [],
                ],
                'Pangasinan' => [
                    'Dagupan City' => [], 'San Carlos City' => [], 'Urdaneta City' => [], 'Alaminos City' => [],
                    'Agno' => [], 'Aguilar' => [], 'Alcala' => [], 'Anda' => [],
                    'Asingan' => [], 'Balungao' => [], 'Bani' => [], 'Basista' => [],
                    'Bautista' => [], 'Bayambang' => [], 'Binalonan' => [], 'Binmaley' => [],
                    'Bolinao' => [], 'Bugallon' => [], 'Burgos' => [], 'Calasiao' => [],
                    'Dasol' => [], 'Infanta' => [], 'Labrador' => [], 'Laoac' => [],
                    'Lingayen' => [], 'Mabini' => [], 'Malasiqui' => [], 'Manaoag' => [],
                    'Mangaldan' => [], 'Mangatarem' => [], 'Mapandan' => [], 'Natividad' => [],
                    'Pozorrubio' => [], 'Rosales' => [], 'San Fabian' => [], 'San Jacinto' => [],
                    'San Manuel' => [], 'San Nicolas' => [], 'San Quintin' => [], 'Santa Barbara' => [],
                    'Santa Maria' => [], 'Santo Tomas' => [], 'Sison' => [], 'Sual' => [],
                    'Tayug' => [], 'Umingan' => [], 'Villasis' => [],
                ],
            ],
            'Region II (Cagayan Valley)' => [
                'Batanes' => [
                    'Basco' => [], 'Itbayat' => [], 'Ivana' => [], 'Mahatao' => [],
                    'Sabtang' => [], 'Uyugan' => [],
                ],
                'Cagayan' => [
                    'Tuguegarao City' => [], 'Abulug' => [], 'Alcala' => [], 'Allacapan' => [],
                    'Amulung' => [], 'Aparri' => [], 'Baggao' => [], 'Ballesteros' => [],
                    'Buguey' => [], 'Calayan' => [], 'Camalaniugan' => [], 'Claveria' => [],
                    'Enrile' => [], 'Gattaran' => [], 'Gonzaga' => [], 'Iguig' => [],
                    'Lal-lo' => [], 'Lasam' => [], 'Pamplona' => [], 'Peñablanca' => [],
                    'Piat' => [], 'Rizal' => [], 'Sanchez-Mira' => [], 'Santa Ana' => [],
                    'Santa Praxedes' => [], 'Santa Teresita' => [], 'Santo Niño' => [],
                    'Solana' => [], 'Tuao' => [],
                ],
                'Isabela' => [
                    'Cauayan City' => [], 'Ilagan City' => [], 'Santiago City' => [],
                    'Alicia' => [], 'Angadanan' => [], 'Aurora' => [], 'Benito Soliven' => [],
                    'Burgos' => [], 'Cabagan' => [], 'Cabatuan' => [], 'Cordon' => [],
                    'Delfin Albano' => [], 'Dinapigue' => [], 'Divilacan' => [], 'Echague' => [],
                    'Gamu' => [], 'Jones' => [], 'Luna' => [], 'Maconacon' => [],
                    'Mallig' => [], 'Naguilian' => [], 'Palanan' => [], 'Quezon' => [],
                    'Quirino' => [], 'Ramon' => [], 'Reina Mercedes' => [], 'Roxas' => [],
                    'San Agustin' => [], 'San Guillermo' => [], 'San Isidro' => [],
                    'San Manuel' => [], 'San Mariano' => [], 'San Mateo' => [],
                    'San Pablo' => [], 'Santa Maria' => [], 'Tumauini' => [],
                ],
                'Nueva Vizcaya' => [
                    'Bayombong' => [], 'Solano' => [], 'Ambaguio' => [], 'Aritao' => [],
                    'Bagabag' => [], 'Bambang' => [], 'Diadi' => [], 'Dupax del Norte' => [],
                    'Dupax del Sur' => [], 'Kasibu' => [], 'Kayapa' => [], 'Quezon' => [],
                    'Santa Fe' => [], 'Villaverde' => [],
                ],
                'Quirino' => [
                    'Cabarroguis' => [], 'Aglipay' => [], 'Diffun' => [], 'Maddela' => [],
                    'Nagtipunan' => [], 'Saguday' => [],
                ],
            ],
            'Region III (Central Luzon)' => [
                'Aurora' => [
                    'Baler' => [], 'Casiguran' => [], 'Dilasag' => [], 'Dinalungan' => [],
                    'Dingalan' => [], 'Dipaculao' => [], 'Maria Aurora' => [], 'San Luis' => [],
                ],
                'Bataan' => [
                    'Balanga City' => [], 'Abucay' => [], 'Bagac' => [], 'Dinalupihan' => [],
                    'Hermosa' => [], 'Limay' => [], 'Mariveles' => [], 'Morong' => [],
                    'Orani' => [], 'Orion' => [], 'Pilar' => [], 'Samal' => [],
                ],
                'Bulacan' => [
                    'Malolos City' => [], 'Meycauayan City' => [], 'San Jose del Monte City' => [],
                    'Angat' => [], 'Balagtas' => [], 'Baliuag' => [], 'Bocaue' => [],
                    'Bulakan' => [], 'Bustos' => [], 'Calumpit' => [], 'Doña Remedios Trinidad' => [],
                    'Guiguinto' => [], 'Hagonoy' => [], 'Marilao' => [], 'Norzagaray' => [],
                    'Obando' => [], 'Pandi' => [], 'Paombong' => [], 'Plaridel' => [],
                    'Pulilan' => [], 'San Ildefonso' => [], 'San Miguel' => [],
                    'San Rafael' => [], 'Santa Maria' => [],
                ],
                'Nueva Ecija' => [
                    'Cabanatuan City' => [], 'Gapan City' => [], 'Palayan City' => [],
                    'San Jose City' => [], 'Science City of Muñoz' => [],
                    'Aliaga' => [], 'Bongabon' => [], 'Cabiao' => [], 'Carranglan' => [],
                    'Cuyapo' => [], 'Gabaldon' => [], 'General Mamerto Natividad' => [],
                    'General Tinio' => [], 'Guimba' => [], 'Jaen' => [], 'Laur' => [],
                    'Licab' => [], 'Llanera' => [], 'Lupao' => [], 'Nampicuan' => [],
                    'Pantabangan' => [], 'Peñaranda' => [], 'Quezon' => [], 'Rizal' => [],
                    'San Antonio' => [], 'San Isidro' => [], 'San Leonardo' => [],
                    'Santa Rosa' => [], 'Santo Domingo' => [], 'Talavera' => [],
                    'Talugtug' => [], 'Zaragoza' => [],
                ],
                'Pampanga' => [
                    'City of San Fernando' => [], 'Angeles City' => [], 'Mabalacat City' => [],
                    'Apalit' => [], 'Arayat' => [], 'Bacolor' => [], 'Candaba' => [],
                    'Floridablanca' => [], 'Guagua' => [], 'Lubao' => [], 'Macabebe' => [],
                    'Magalang' => [], 'Masantol' => [], 'Mexico' => [], 'Minalin' => [],
                    'Porac' => [], 'San Luis' => [], 'San Simon' => [], 'Santa Ana' => [],
                    'Santa Rita' => [], 'Santo Tomas' => [], 'Sasmuan' => [],
                ],
                'Tarlac' => [
                    'Tarlac City' => [], 'Anao' => [], 'Bamban' => [], 'Camiling' => [],
                    'Capas' => [], 'Concepcion' => [], 'Gerona' => [], 'La Paz' => [],
                    'Mayantoc' => [], 'Moncada' => [], 'Paniqui' => [], 'Pura' => [],
                    'Ramos' => [], 'San Clemente' => [], 'San Jose' => [], 'San Manuel' => [],
                    'Santa Ignacia' => [], 'Victoria' => [],
                ],
                'Zambales' => [
                    'Olongapo City' => [], 'Botolan' => [], 'Cabangan' => [], 'Candelaria' => [],
                    'Castillejos' => [], 'Iba' => [], 'Masinloc' => [], 'Palauig' => [],
                    'San Antonio' => [], 'San Felipe' => [], 'San Marcelino' => [],
                    'San Narciso' => [], 'Santa Cruz' => [], 'Subic' => [],
                ],
            ],
            'Region IV-A (CALABARZON)' => [
                'Batangas' => [
                    'Batangas City' => [], 'Lipa City' => [], 'Tanauan City' => [], 'Santo Tomas City' => [],
                    'Agoncillo' => [], 'Alitagtag' => [], 'Balayan' => [], 'Balete' => [],
                    'Bauan' => [], 'Calaca' => [], 'Calatagan' => [], 'Cuenca' => [],
                    'Ibaan' => [], 'Laurel' => [], 'Lemery' => [], 'Lian' => [],
                    'Lobo' => [], 'Mabini' => [], 'Malvar' => [], 'Mataas Na Kahoy' => [],
                    'Nasugbu' => [], 'Padre Garcia' => [], 'Rosario' => [], 'San Jose' => [],
                    'San Juan' => [], 'San Luis' => [], 'San Nicolas' => [], 'San Pascual' => [],
                    'Santa Teresita' => [], 'Taysan' => [], 'Tingloy' => [], 'Tuy' => [],
                ],
                'Cavite' => [
                    'Bacoor City' => [], 'Cavite City' => [], 'Dasmariñas City' => [],
                    'General Trias City' => [], 'Imus City' => [], 'Tagaytay City' => [],
                    'Trece Martires City' => [],
                    'Alfonso' => [], 'Amadeo' => [], 'Carmona' => [], 'Gen. Mariano Alvarez' => [],
                    'General Emilio Aguinaldo' => [], 'Indang' => [], 'Kawit' => [],
                    'Magallanes' => [], 'Maragondon' => [], 'Mendez' => [], 'Naic' => [],
                    'Noveleta' => [], 'Rosario' => [], 'Silang' => [], 'Tanza' => [],
                    'Ternate' => [],
                ],
                'Laguna' => [
                    'Biñan City' => [], 'Cabuyao City' => [], 'Calamba City' => [],
                    'San Pablo City' => [], 'San Pedro City' => [], 'Santa Rosa City' => [],
                    'Alaminos' => [], 'Bay' => [], 'Calauan' => [], 'Cavinti' => [],
                    'Famy' => [], 'Kalayaan' => [], 'Liliw' => [], 'Los Baños' => [],
                    'Luisiana' => [], 'Lumban' => [], 'Mabitac' => [], 'Magdalena' => [],
                    'Majayjay' => [], 'Nagcarlan' => [], 'Paete' => [], 'Pagsanjan' => [],
                    'Pakil' => [], 'Pangil' => [], 'Pila' => [], 'Rizal' => [],
                    'Santa Cruz' => [], 'Santa Maria' => [], 'Siniloan' => [], 'Victoria' => [],
                ],
                'Quezon' => [
                    'Lucena City' => [], 'Tayabas City' => [],
                    'Agdangan' => [], 'Alabat' => [], 'Atimonan' => [], 'Buenavista' => [],
                    'Burdeos' => [], 'Calauag' => [], 'Candelaria' => [], 'Catanauan' => [],
                    'Dolores' => [], 'General Luna' => [], 'General Nakar' => [], 'Guinayangan' => [],
                    'Gumaca' => [], 'Infanta' => [], 'Jomalig' => [], 'Lopez' => [],
                    'Lucban' => [], 'Macalelon' => [], 'Mauban' => [], 'Mulanay' => [],
                    'Padre Burgos' => [], 'Pagbilao' => [], 'Panukulan' => [], 'Patnanungan' => [],
                    'Perez' => [], 'Pitogo' => [], 'Plaridel' => [], 'Polillo' => [],
                    'Quezon' => [], 'Real' => [], 'Sampaloc' => [], 'San Andres' => [],
                    'San Antonio' => [], 'San Francisco' => [], 'San Narciso' => [],
                    'Sariaya' => [], 'Tagkawayan' => [], 'Tiaong' => [], 'Unisan' => [],
                ],
                'Rizal' => [
                    'Antipolo City' => [], 'Angono' => [], 'Baras' => [], 'Binangonan' => [],
                    'Cainta' => [], 'Cardona' => [], 'Jalajala' => [], 'Morong' => [],
                    'Pililla' => [], 'Rodriguez' => [], 'San Mateo' => [], 'Tanay' => [],
                    'Taytay' => [], 'Teresa' => [],
                ],
            ],
            'Region IV-B (MIMAROPA)' => [
                'Marinduque' => [
                    'Boac' => [], 'Buenavista' => [], 'Gasan' => [], 'Mogpog' => [],
                    'Santa Cruz' => [], 'Torrijos' => [],
                ],
                'Occidental Mindoro' => [
                    'Abra de Ilog' => [], 'Calintaan' => [], 'Looc' => [], 'Lubang' => [],
                    'Magsaysay' => [], 'Mamburao' => [], 'Paluan' => [], 'Rizal' => [],
                    'Sablayan' => [], 'San Jose' => [], 'Santa Cruz' => [],
                ],
                'Oriental Mindoro' => [
                    'Calapan City' => [], 'Baco' => [], 'Bansud' => [], 'Bongabong' => [],
                    'Bulalacao' => [], 'Gloria' => [], 'Mansalay' => [], 'Naujan' => [],
                    'Pinamalayan' => [], 'Pola' => [], 'Puerto Galera' => [], 'Roxas' => [],
                    'San Teodoro' => [], 'Socorro' => [], 'Victoria' => [],
                ],
                'Palawan' => [
                    'Puerto Princesa City' => [], 'Aborlan' => [], 'Agutaya' => [], 'Araceli' => [],
                    'Balabac' => [], 'Bataraza' => [], 'Brooke\'s Point' => [], 'Busuanga' => [],
                    'Cagayancillo' => [], 'Coron' => [], 'Culion' => [], 'Cuyo' => [],
                    'Dumaran' => [], 'El Nido' => [], 'Kalayaan' => [], 'Linapacan' => [],
                    'Magsaysay' => [], 'Narra' => [], 'Quezon' => [], 'Rizal' => [],
                    'Roxas' => [], 'San Vicente' => [], 'Sofronio Española' => [], 'Taytay' => [],
                ],
                'Romblon' => [
                    'Alcantara' => [], 'Banton' => [], 'Cajidiocan' => [], 'Calatrava' => [],
                    'Concepcion' => [], 'Corcuera' => [], 'Ferrol' => [], 'Looc' => [],
                    'Magdiwang' => [], 'Odiongan' => [], 'Romblon' => [], 'San Agustin' => [],
                    'San Andres' => [], 'San Fernando' => [], 'San Jose' => [],
                    'Santa Fe' => [], 'Santa Maria' => [],
                ],
            ],
            'Region V (Bicol Region)' => [
                'Albay' => [
                    'Legazpi City' => [], 'Ligao City' => [], 'Tabaco City' => [],
                    'Bacacay' => [], 'Camalig' => [], 'Daraga' => [], 'Guinobatan' => [],
                    'Jovellar' => [], 'Libon' => [], 'Malilipot' => [], 'Malinao' => [],
                    'Manito' => [], 'Oas' => [], 'Pio Duran' => [], 'Polangui' => [],
                    'Rapu-Rapu' => [], 'Santo Domingo' => [], 'Tiwi' => [],
                ],
                'Camarines Norte' => [
                    'Daet' => [], 'Basud' => [], 'Capalonga' => [], 'Jose Panganiban' => [],
                    'Labo' => [], 'Mercedes' => [], 'Paracale' => [], 'San Lorenzo Ruiz' => [],
                    'San Vicente' => [], 'Santa Elena' => [], 'Talisay' => [], 'Vinzons' => [],
                ],
                'Camarines Sur' => [
                    'Naga City' => [], 'Iriga City' => [],
                    'Baao' => [], 'Balatan' => [], 'Bato' => [], 'Bombon' => [],
                    'Buhi' => [], 'Bula' => [], 'Cabusao' => [], 'Calabanga' => [],
                    'Camaligan' => [], 'Canaman' => [], 'Caramoan' => [], 'Del Gallego' => [],
                    'Gainza' => [], 'Garchitorena' => [], 'Goa' => [], 'Lagonoy' => [],
                    'Libmanan' => [], 'Lupi' => [], 'Magarao' => [], 'Milaor' => [],
                    'Minalabac' => [], 'Nabua' => [], 'Ocampo' => [], 'Pamplona' => [],
                    'Pasacao' => [], 'Pili' => [], 'Presentacion' => [], 'Ragay' => [],
                    'Sagñay' => [], 'San Fernando' => [], 'San Jose' => [], 'Sipocot' => [],
                    'Siruma' => [], 'Tigaon' => [], 'Tinambac' => [],
                ],
                'Catanduanes' => [
                    'Virac' => [], 'Bagamanoc' => [], 'Baras' => [], 'Bato' => [],
                    'Caramoran' => [], 'Gigmoto' => [], 'Pandan' => [], 'Panganiban' => [],
                    'San Andres' => [], 'San Miguel' => [], 'Viga' => [],
                ],
                'Masbate' => [
                    'Masbate City' => [], 'Aroroy' => [], 'Baleno' => [], 'Balud' => [],
                    'Batuan' => [], 'Cataingan' => [], 'Cawayan' => [], 'Claveria' => [],
                    'Dimasalang' => [], 'Esperanza' => [], 'Mandaon' => [], 'Milagros' => [],
                    'Mobo' => [], 'Monreal' => [], 'Palanas' => [], 'Pio V. Corpuz' => [],
                    'Placer' => [], 'San Fernando' => [], 'San Jacinto' => [], 'San Pascual' => [],
                    'Uson' => [],
                ],
                'Sorsogon' => [
                    'Sorsogon City' => [], 'Barcelona' => [], 'Bulan' => [], 'Bulusan' => [],
                    'Casiguran' => [], 'Castilla' => [], 'Donsol' => [], 'Gubat' => [],
                    'Irosin' => [], 'Juban' => [], 'Magallanes' => [], 'Matnog' => [],
                    'Pilar' => [], 'Prieto Diaz' => [], 'Santa Magdalena' => [],
                ],
            ],
            'Region VI (Western Visayas)' => [
                'Aklan' => [
                    'Altavas' => [], 'Balete' => [], 'Banga' => [], 'Batan' => [],
                    'Buruanga' => [], 'Ibajay' => [], 'Kalibo' => [], 'Lezo' => [],
                    'Libacao' => [], 'Madalag' => [], 'Makato' => [], 'Malay' => [],
                    'Malinao' => [], 'Nabas' => [], 'New Washington' => [], 'Numancia' => [],
                    'Tangalan' => [],
                ],
                'Antique' => [
                    'San Jose de Buenavista' => [], 'Anini-y' => [], 'Barbaza' => [], 'Belison' => [],
                    'Bugasong' => [], 'Caluya' => [], 'Culasi' => [], 'Hamtic' => [],
                    'Laua-an' => [], 'Libertad' => [], 'Pandan' => [], 'Patnongon' => [],
                    'San Remigio' => [], 'Sebaste' => [], 'Sibalom' => [], 'Tibiao' => [],
                    'Tobias Fornier' => [], 'Valderrama' => [],
                ],
                'Capiz' => [
                    'Roxas City' => [], 'Cuartero' => [], 'Dao' => [], 'Dumalag' => [],
                    'Dumarao' => [], 'Ivisan' => [], 'Jamindan' => [], 'Ma-ayon' => [],
                    'Mambusao' => [], 'Panay' => [], 'Panitan' => [], 'Pilar' => [],
                    'Pontevedra' => [], 'President Roxas' => [], 'Sapian' => [],
                    'Sigma' => [], 'Tapaz' => [],
                ],
                'Guimaras' => [
                    'Buenavista' => [], 'Jordan' => [], 'Nueva Valencia' => [],
                    'San Lorenzo' => [], 'Sibunag' => [],
                ],
                'Iloilo' => [
                    'Iloilo City' => [], 'Passi City' => [],
                    'Ajuy' => [], 'Alimodian' => [], 'Anilao' => [], 'Badiangan' => [],
                    'Balasan' => [], 'Banate' => [], 'Barotac Nuevo' => [], 'Barotac Viejo' => [],
                    'Batad' => [], 'Bingawan' => [], 'Cabatuan' => [], 'Calinog' => [],
                    'Carles' => [], 'Concepcion' => [], 'Dingle' => [], 'Dueñas' => [],
                    'Dumangas' => [], 'Estancia' => [], 'Guimbal' => [], 'Igbaras' => [],
                    'Janiuay' => [], 'Lambunao' => [], 'Leganes' => [], 'Lemery' => [],
                    'Leon' => [], 'Maasin' => [], 'Miagao' => [], 'Mina' => [],
                    'New Lucena' => [], 'Oton' => [], 'Pavia' => [], 'Pototan' => [],
                    'San Dionisio' => [], 'San Enrique' => [], 'San Joaquin' => [],
                    'San Miguel' => [], 'San Rafael' => [], 'Santa Barbara' => [],
                    'Sara' => [], 'Tigbauan' => [], 'Tubungan' => [], 'Zarraga' => [],
                ],
                'Negros Occidental' => [
                    'Bacolod City' => [], 'Bago City' => [], 'Cadiz City' => [],
                    'Escalante City' => [], 'Himamaylan City' => [], 'Kabankalan City' => [],
                    'La Carlota City' => [], 'Sagay City' => [], 'San Carlos City' => [],
                    'Silay City' => [], 'Sipalay City' => [], 'Talisay City' => [],
                    'Victorias City' => [],
                    'Binalbagan' => [], 'Calatrava' => [], 'Candoni' => [], 'Cauayan' => [],
                    'Enrique B. Magalona' => [], 'Hinigaran' => [], 'Hinoba-an' => [],
                    'Ilog' => [], 'Isabela' => [], 'La Castellana' => [], 'Manapla' => [],
                    'Moises Padilla' => [], 'Murcia' => [], 'Pontevedra' => [],
                    'Pulupandan' => [], 'Salvador Benedicto' => [], 'San Enrique' => [],
                    'Toboso' => [], 'Valladolid' => [],
                ],
            ],
            'Region VII (Central Visayas)' => [
                'Bohol' => [
                    'Tagbilaran City' => [], 'Alburquerque' => [], 'Alicia' => [], 'Anda' => [],
                    'Antequera' => [], 'Baclayon' => [], 'Balilihan' => [], 'Batuan' => [],
                    'Bien Unido' => [], 'Bilar' => [], 'Buenavista' => [], 'Calape' => [],
                    'Candijay' => [], 'Carmen' => [], 'Catigbian' => [], 'Clarin' => [],
                    'Corella' => [], 'Cortes' => [], 'Dagohoy' => [], 'Danao' => [],
                    'Dauis' => [], 'Dimiao' => [], 'Duero' => [], 'Garcia Hernandez' => [],
                    'Getafe' => [], 'Guindulman' => [], 'Inabanga' => [], 'Jagna' => [],
                    'Lila' => [], 'Loay' => [], 'Loboc' => [], 'Loon' => [],
                    'Mabini' => [], 'Maribojoc' => [], 'Panglao' => [], 'Pilar' => [],
                    'President Carlos P. Garcia' => [], 'Sagbayan' => [], 'San Isidro' => [],
                    'San Miguel' => [], 'Sevilla' => [], 'Sierra Bullones' => [],
                    'Sikatuna' => [], 'Talibon' => [], 'Trinidad' => [], 'Tubigon' => [],
                    'Ubay' => [], 'Valencia' => [],
                ],
                'Cebu' => [
                    'Cebu City' => [], 'Lapu-Lapu City' => [], 'Mandaue City' => [],
                    'Bogo City' => [], 'Carcar City' => [], 'Danao City' => [],
                    'Naga City' => [], 'Talisay City' => [], 'Toledo City' => [],
                    'Alcantara' => [], 'Alcoy' => [], 'Alegria' => [], 'Aloguinsan' => [],
                    'Argao' => [], 'Asturias' => [], 'Badian' => [], 'Balamban' => [],
                    'Bantayan' => [], 'Barili' => [], 'Boljoon' => [], 'Borbon' => [],
                    'Carmen' => [], 'Catmon' => [], 'Compostela' => [], 'Consolacion' => [],
                    'Cordova' => [], 'Daanbantayan' => [], 'Dalaguete' => [], 'Dumanjug' => [],
                    'Ginatilan' => [], 'Liloan' => [], 'Madridejos' => [], 'Malabuyoc' => [],
                    'Medellin' => [], 'Minglanilla' => [], 'Moalboal' => [], 'Oslob' => [],
                    'Pilar' => [], 'Pinamungajan' => [], 'Poro' => [], 'Ronda' => [],
                    'Samboan' => [], 'San Fernando' => [], 'San Francisco' => [],
                    'San Remigio' => [], 'Santa Fe' => [], 'Santander' => [],
                    'Sibonga' => [], 'Sogod' => [], 'Tabogon' => [], 'Tabuelan' => [],
                    'Tuburan' => [], 'Tudela' => [],
                ],
                'Negros Oriental' => [
                    'Dumaguete City' => [], 'Bayawan City' => [], 'Bais City' => [],
                    'Canlaon City' => [], 'Guihulngan City' => [], 'Tanjay City' => [],
                    'Amlan' => [], 'Ayungon' => [], 'Bacong' => [], 'Basay' => [],
                    'Bindoy' => [], 'Dauin' => [], 'Jimalalud' => [], 'La Libertad' => [],
                    'Mabinay' => [], 'Manjuyod' => [], 'Pamplona' => [], 'San Jose' => [],
                    'Santa Catalina' => [], 'Siaton' => [], 'Sibulan' => [], 'Tayasan' => [],
                    'Valencia' => [], 'Vallehermoso' => [], 'Zamboanguita' => [],
                ],
                'Siquijor' => [
                    'Enrique Villanueva' => [], 'Larena' => [], 'Lazi' => [],
                    'Maria' => [], 'San Juan' => [], 'Siquijor' => [],
                ],
            ],
            'Region VIII (Eastern Visayas)' => [
                'Biliran' => [
                    'Almeria' => [], 'Biliran' => [], 'Cabucgayan' => [], 'Caibiran' => [],
                    'Culaba' => [], 'Kawayan' => [], 'Maripipi' => [], 'Naval' => [],
                ],
                'Eastern Samar' => [
                    'Borongan City' => [], 'Arteche' => [], 'Balangiga' => [], 'Balangkayan' => [],
                    'Can-avid' => [], 'Dolores' => [], 'General MacArthur' => [], 'Giporlos' => [],
                    'Guiuan' => [], 'Hernani' => [], 'Jipapad' => [], 'Lawaan' => [],
                    'Llorente' => [], 'Maslog' => [], 'Maydolong' => [], 'Mercedes' => [],
                    'Oras' => [], 'Quinapondan' => [], 'Salcedo' => [], 'San Julian' => [],
                    'San Policarpo' => [], 'Sulat' => [], 'Taft' => [],
                ],
                'Leyte' => [
                    'Tacloban City' => [], 'Ormoc City' => [], 'Baybay City' => [],
                    'Abuyog' => [], 'Alangalang' => [], 'Albuera' => [], 'Babatngon' => [],
                    'Barugo' => [], 'Bato' => [], 'Burauen' => [], 'Calubian' => [],
                    'Capoocan' => [], 'Carigara' => [], 'Dagami' => [], 'Dulag' => [],
                    'Hilongos' => [], 'Hindang' => [], 'Inopacan' => [], 'Isabel' => [],
                    'Jaro' => [], 'Javier' => [], 'Julita' => [], 'Kananga' => [],
                    'La Paz' => [], 'Leyte' => [], 'MacArthur' => [], 'Mahaplag' => [],
                    'Mataasnakahoy' => [], 'Matalom' => [], 'Mayorga' => [], 'Merida' => [],
                    'Palo' => [], 'Palompon' => [], 'Pastrana' => [], 'San Isidro' => [],
                    'San Miguel' => [], 'Santa Fe' => [], 'Tabango' => [], 'Tabontabon' => [],
                    'Tanauan' => [], 'Tolosa' => [], 'Tunga' => [], 'Villaba' => [],
                ],
                'Northern Samar' => [
                    'Catarman' => [], 'Allen' => [], 'Biri' => [], 'Bobon' => [],
                    'Capul' => [], 'Catubig' => [], 'Gamay' => [], 'Laoang' => [],
                    'Lapinig' => [], 'Las Navas' => [], 'Lavezares' => [], 'Lope de Vega' => [],
                    'Mapanas' => [], 'Mondragon' => [], 'Palapag' => [], 'Pambujan' => [],
                    'Rosario' => [], 'San Antonio' => [], 'San Isidro' => [], 'San Jose' => [],
                    'San Roque' => [], 'San Vicente' => [], 'Silvino Lobos' => [],
                    'Victoria' => [],
                ],
                'Samar (Western Samar)' => [
                    'Catbalogan City' => [], 'Calbayog City' => [],
                    'Almagro' => [], 'Basey' => [], 'Calbiga' => [], 'Daram' => [],
                    'Gandara' => [], 'Hinabangan' => [], 'Jiabong' => [], 'Marabut' => [],
                    'Matuguinao' => [], 'Motiong' => [], 'Paranas' => [], 'San Jorge' => [],
                    'San Jose de Buan' => [], 'San Sebastian' => [], 'Santa Margarita' => [],
                    'Santa Rita' => [], 'Santo Niño' => [], 'Tagapul-an' => [],
                    'Talalora' => [], 'Tarangnan' => [], 'Villareal' => [], 'Zumarraga' => [],
                ],
                'Southern Leyte' => [
                    'Maasin City' => [], 'Anahawan' => [], 'Bontoc' => [], 'Hinunangan' => [],
                    'Hinundayan' => [], 'Libagon' => [], 'Liloan' => [], 'Limasawa' => [],
                    'Macrohon' => [], 'Malitbog' => [], 'Padre Burgos' => [],
                    'Pintuyan' => [], 'Saint Bernard' => [], 'San Francisco' => [],
                    'San Juan' => [], 'San Ricardo' => [], 'Silago' => [], 'Sogod' => [],
                    'Tomas Oppus' => [],
                ],
            ],
            'Region IX (Zamboanga Peninsula)' => [
                'Zamboanga del Norte' => [
                    'Dipolog City' => [], 'Dapitan City' => [],
                    'Bacungan' => [], 'Baliguian' => [], 'Godod' => [], 'Gutalac' => [],
                    'Jose Dalman' => [], 'Kalawit' => [], 'Katipunan' => [], 'La Libertad' => [],
                    'Labason' => [], 'Leon B. Postigo' => [], 'Liloy' => [], 'Manukan' => [],
                    'Mutia' => [], 'Piñan' => [], 'Polanco' => [], 'President Manuel A. Roxas' => [],
                    'Rizal' => [], 'Salug' => [], 'Sergio Osmeña Sr.' => [], 'Siayan' => [],
                    'Sibuco' => [], 'Sibutad' => [], 'Sindangan' => [], 'Siocon' => [],
                    'Sirawai' => [], 'Tampilisan' => [],
                ],
                'Zamboanga del Sur' => [
                    'Zamboanga City' => [], 'Pagadian City' => [],
                    'Aurora' => [], 'Bayog' => [], 'Dimataling' => [], 'Dinas' => [],
                    'Dumalinao' => [], 'Dumingag' => [], 'Guipos' => [], 'Josefina' => [],
                    'Kumalarang' => [], 'Labangan' => [], 'Lapuyan' => [], 'Mahayag' => [],
                    'Margosatubig' => [], 'Midsalip' => [], 'Molave' => [], 'Pitogo' => [],
                    'Ramon Magsaysay' => [], 'San Miguel' => [], 'San Pablo' => [],
                    'Sominot' => [], 'Tabina' => [], 'Tambulig' => [], 'Tigbao' => [],
                    'Tukuran' => [], 'Vincenzo A. Sagun' => [],
                ],
                'Zamboanga Sibugay' => [
                    'Alicia' => [], 'Buug' => [], 'Diplahan' => [], 'Imelda' => [],
                    'Ipil' => [], 'Kabasalan' => [], 'Mabuhay' => [], 'Malangas' => [],
                    'Naga' => [], 'Olutanga' => [], 'Payao' => [], 'Roseller Lim' => [],
                    'Siay' => [], 'Talusan' => [], 'Titay' => [], 'Tungawan' => [],
                ],
            ],
            'Region X (Northern Mindanao)' => [
                'Bukidnon' => [
                    'Malaybalay City' => [], 'Valencia City' => [],
                    'Baungon' => [], 'Cabanglasan' => [], 'Damulog' => [], 'Dangcagan' => [],
                    'Don Carlos' => [], 'Impasugong' => [], 'Kadingilan' => [], 'Kalilangan' => [],
                    'Kibawe' => [], 'Kitaotao' => [], 'Lantapan' => [], 'Libona' => [],
                    'Malitbog' => [], 'Manolo Fortich' => [], 'Maramag' => [], 'Pangantucan' => [],
                    'Quezon' => [], 'San Fernando' => [], 'Sumilao' => [], 'Talakag' => [],
                ],
                'Camiguin' => [
                    'Catarman' => [], 'Guinsiliban' => [], 'Mahinog' => [], 'Mambajao' => [],
                    'Sagay' => [],
                ],
                'Lanao del Norte' => [
                    'Iligan City' => [],
                    'Bacolod' => [], 'Baloi' => [], 'Baroy' => [], 'Kapatagan' => [],
                    'Kauswagan' => [], 'Kolambugan' => [], 'Lala' => [], 'Linamon' => [],
                    'Magsaysay' => [], 'Maigo' => [], 'Matungao' => [], 'Munai' => [],
                    'Nunungan' => [], 'Pantao Ragat' => [], 'Pantar' => [], 'Poona Piagapo' => [],
                    'Salvador' => [], 'Sapad' => [], 'Sultan Naga Dimaporo' => [], 'Tagoloan' => [],
                    'Tangcal' => [], 'Tubod' => [],
                ],
                'Misamis Occidental' => [
                    'Oroquieta City' => [], 'Ozamiz City' => [], 'Tangub City' => [],
                    'Aloran' => [], 'Baliangao' => [], 'Bonifacio' => [], 'Calamba' => [],
                    'Clarin' => [], 'Concepcion' => [], 'Don Victoriano Chiongbian' => [],
                    'Jimenez' => [], 'Lopez Jaena' => [], 'Panaon' => [], 'Plaridel' => [],
                    'Sapang Dalaga' => [], 'Sinacaban' => [], 'Tudela' => [],
                ],
                'Misamis Oriental' => [
                    'Cagayan de Oro City' => [], 'Gingoog City' => [], 'El Salvador City' => [],
                    'Alubijid' => [], 'Balingasag' => [], 'Balingoan' => [], 'Binuangan' => [],
                    'Claveria' => [], 'Gitagum' => [], 'Initao' => [], 'Jasaan' => [],
                    'Kinoguitan' => [], 'Lagonglong' => [], 'Laguindingan' => [], 'Libertad' => [],
                    'Lugait' => [], 'Magsaysay' => [], 'Manticao' => [], 'Medina' => [],
                    'Naawan' => [], 'Opol' => [], 'Salay' => [], 'Sugbongcogon' => [],
                    'Tagoloan' => [], 'Talisayan' => [], 'Villanueva' => [],
                ],
            ],
            'Region XI (Davao Region)' => [
                'Davao de Oro' => [
                    'Compostela' => [], 'Laak' => [], 'Mabini' => [], 'Maco' => [],
                    'Maragusan' => [], 'Mawab' => [], 'Monkayo' => [], 'Montevista' => [],
                    'Nabunturan' => [], 'New Bataan' => [], 'Pantukan' => [],
                ],
                'Davao del Norte' => [
                    'Tagum City' => [], 'Panabo City' => [], 'Island Garden City of Samal' => [],
                    'Asuncion' => [], 'Braulio E. Dujali' => [], 'Carmen' => [],
                    'Kapalong' => [], 'New Corella' => [], 'San Isidro' => [], 'Santo Tomas' => [],
                    'Talaingod' => [],
                ],
                'Davao del Sur' => [
                    'Davao City' => [], 'Digos City' => [],
                    'Bansalan' => [], 'Don Marcelino' => [], 'Hagonoy' => [], 'Jose Abad Santos' => [],
                    'Kiblawan' => [], 'Magsaysay' => [], 'Malalag' => [], 'Malita' => [],
                    'Matanao' => [], 'Padada' => [], 'Santa Cruz' => [], 'Sulop' => [],
                ],
                'Davao Occidental' => [
                    'Don Marcelino' => [], 'Jose Abad Santos' => [], 'Malita' => [],
                    'Santa Maria' => [], 'Sarangani' => [],
                ],
                'Davao Oriental' => [
                    'Mati City' => [],
                    'Baganga' => [], 'Banaybanay' => [], 'Boston' => [], 'Caraga' => [],
                    'Cateel' => [], 'Governor Generoso' => [], 'Lupon' => [], 'Manay' => [],
                    'San Isidro' => [], 'Tarragona' => [],
                ],
            ],
            'Region XII (SOCCSKSARGEN)' => [
                'Cotabato (North Cotabato)' => [
                    'Kidapawan City' => [],
                    'Alamada' => [], 'Aleosan' => [], 'Antipas' => [], 'Arakan' => [],
                    'Banisilan' => [], 'Carmen' => [], 'Kabacan' => [], 'Libungan' => [],
                    'M\'lang' => [], 'Magpet' => [], 'Makilala' => [], 'Matalam' => [],
                    'Midsayap' => [], 'Pigcawayan' => [], 'Pikit' => [], 'President Roxas' => [],
                    'Tulunan' => [],
                ],
                'Sarangani' => [
                    'Alabel' => [], 'Glan' => [], 'Kiamba' => [], 'Maasim' => [],
                    'Maitum' => [], 'Malapatan' => [], 'Malungon' => [],
                ],
                'South Cotabato' => [
                    'General Santos City' => [], 'Koronadal City' => [],
                    'Banga' => [], 'Lake Sebu' => [], 'Norala' => [], 'Polomolok' => [],
                    'Santo Niño' => [], 'Surallah' => [], 'T\'boli' => [], 'Tampakan' => [],
                    'Tantangan' => [], 'Tupi' => [],
                ],
                'Sultan Kudarat' => [
                    'Tacurong City' => [],
                    'Bagumbayan' => [], 'Columbio' => [], 'Esperanza' => [], 'Isulan' => [],
                    'Kalamansig' => [], 'Lambayong' => [], 'Lebak' => [], 'Lutayan' => [],
                    'Palimbang' => [], 'President Quirino' => [], 'Senator Ninoy Aquino' => [],
                ],
            ],
            'Region XIII (Caraga)' => [
                'Agusan del Norte' => [
                    'Butuan City' => [], 'Cabadbaran City' => [],
                    'Buenavista' => [], 'Carmen' => [], 'Jabonga' => [], 'Kitcharao' => [],
                    'Las Nieves' => [], 'Magallanes' => [], 'Nasipit' => [], 'Remedios T. Romualdez' => [],
                    'Santiago' => [], 'Tubay' => [],
                ],
                'Agusan del Sur' => [
                    'Bayugan City' => [],
                    'Bunawan' => [], 'Esperanza' => [], 'La Paz' => [], 'Loreto' => [],
                    'Prosperidad' => [], 'Rosario' => [], 'San Francisco' => [], 'San Luis' => [],
                    'Santa Josefa' => [], 'Sibagat' => [], 'Talacogon' => [], 'Trento' => [],
                    'Veruela' => [],
                ],
                'Dinagat Islands' => [
                    'Basilisa' => [], 'Cagdianao' => [], 'Dinagat' => [], 'Libjo' => [],
                    'Loreto' => [], 'San Jose' => [], 'Tubajon' => [],
                ],
                'Surigao del Norte' => [
                    'Surigao City' => [],
                    'Alegria' => [], 'Bacuag' => [], 'Burgos' => [], 'Claver' => [],
                    'Dapa' => [], 'Del Carmen' => [], 'General Luna' => [], 'Gigaquit' => [],
                    'Mainit' => [], 'Malimono' => [], 'Pilar' => [], 'Placer' => [],
                    'San Benito' => [], 'San Francisco' => [], 'San Isidro' => [],
                    'Santa Monica' => [], 'Sison' => [], 'Socorro' => [], 'Tagana-an' => [],
                    'Tubod' => [],
                ],
                'Surigao del Sur' => [
                    'Bislig City' => [], 'Tandag City' => [],
                    'Barobo' => [], 'Bayabas' => [], 'Cagwait' => [], 'Cantilan' => [],
                    'Carmen' => [], 'Carrascal' => [], 'Cortes' => [], 'Hinatuan' => [],
                    'Lanuza' => [], 'Lianga' => [], 'Lingig' => [], 'Madrid' => [],
                    'Marihatag' => [], 'San Agustin' => [], 'San Miguel' => [],
                    'Tagbina' => [], 'Tago' => [],
                ],
            ],
            'BARMM (Bangsamoro)' => [
                'Basilan' => [
                    'Isabela City' => [], 'Lamitan City' => [],
                    'Akbar' => [], 'Al-Barka' => [], 'Hadji Mohammad Ajul' => [],
                    'Hadji Muhtamad' => [], 'Lantawan' => [], 'Maluso' => [],
                    'Sumisip' => [], 'Tabuan-Lasa' => [], 'Tipo-Tipo' => [],
                    'Tuburan' => [], 'Ungkaya Pukan' => [],
                ],
                'Lanao del Sur' => [
                    'Marawi City' => [],
                    'Bacolod-Kalawi' => [], 'Balabagan' => [], 'Balindong' => [],
                    'Bayang' => [], 'Binidayan' => [], 'Buadiposo-Buntong' => [],
                    'Bubong' => [], 'Butig' => [], 'Calanogas' => [], 'Ditsaan-Ramain' => [],
                    'Ganassi' => [], 'Kapai' => [], 'Kapatagan' => [], 'Lumba-Bayabao' => [],
                    'Lumbaca-Unayan' => [], 'Lumbatan' => [], 'Lumbayanague' => [],
                    'Madalum' => [], 'Madamba' => [], 'Maguing' => [], 'Malabang' => [],
                    'Marantao' => [], 'Marogong' => [], 'Masiu' => [], 'Mulondo' => [],
                    'Pagayawan' => [], 'Piagapo' => [], 'Picong' => [], 'Poona Bayabao' => [],
                    'Pualas' => [], 'Saguiaran' => [], 'Sultan Dumalondong' => [],
                    'Tagoloan II' => [], 'Tamparan' => [], 'Taraka' => [], 'Tubaran' => [],
                    'Tugaya' => [], 'Wao' => [],
                ],
                'Maguindanao del Norte' => [
                    'Cotabato City' => [],
                    'Barira' => [], 'Buldon' => [], 'Datu Blah T. Sinsuat' => [],
                    'Datu Odin Sinsuat' => [], 'Kabuntalan' => [], 'Matanog' => [],
                    'Northern Kabuntalan' => [], 'Parang' => [], 'Sultan Kudarat' => [],
                    'Sultan Mastura' => [], 'Upi' => [],
                ],
                'Maguindanao del Sur' => [
                    'Ampatuan' => [], 'Buluan' => [], 'Datu Abdullah Sangki' => [],
                    'Datu Anggal Midtimbang' => [], 'Datu Hoffer Ampatuan' => [],
                    'Datu Montawal' => [], 'Datu Paglas' => [], 'Datu Piang' => [],
                    'Datu Saudi-Ampatuan' => [], 'Datu Unsay' => [],
                    'General Salipada K. Pendatun' => [], 'Guindulungan' => [],
                    'Mamasapano' => [], 'Mangudadatu' => [], 'Pagalungan' => [],
                    'Paglat' => [], 'Pandag' => [], 'Rajah Buayan' => [],
                    'Shariff Aguak' => [], 'Shariff Saydona Mustapha' => [],
                    'South Upi' => [], 'Sultan sa Barongis' => [], 'Talayan' => [],
                    'Talitay' => [],
                ],
                'Sulu' => [
                    'Jolo' => [], 'Hadji Panglima Tahil' => [], 'Indanan' => [],
                    'Kalingalan Caluang' => [], 'Lugus' => [], 'Luuk' => [], 'Maimbung' => [],
                    'Old Panamao' => [], 'Omar' => [], 'Pandami' => [], 'Panglima Estino' => [],
                    'Pangutaran' => [], 'Parang' => [], 'Pata' => [], 'Patikul' => [],
                    'Siasi' => [], 'Talipao' => [], 'Tapul' => [], 'Tongkil' => [],
                ],
                'Tawi-Tawi' => [
                    'Bongao' => [], 'Languyan' => [], 'Mapun' => [], 'Panglima Sugala' => [],
                    'Sapa-Sapa' => [], 'Sibutu' => [], 'Simunul' => [],
                    'Sitangkai' => [], 'South Ubian' => [], 'Tandubas' => [], 'Turtle Islands' => [],
                ],
            ],
        ];
    }
}
