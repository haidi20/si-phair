<?php

namespace Database\Seeders;

use App\Models\Employee;
use Illuminate\Database\Seeder;

class GajiRealSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $datas = json_decode('[
            {
              "id": 40,
              "name": "RUSLI NUR",
              "jabatan": "MANAGER",
              "basic_salaray": 20000000,
              "allowance": 0,
              "meal_allowance_per_attend": 0
            },
            {
              "id": 44,
              "name": "EVI FANA H",
              "jabatan": "HRD",
              "basic_salaray": 3394000,
              "allowance": 500000,
              "meal_allowance_per_attend": 12000
            },
            {
              "id": 41,
              "name": "IMANSYAH GO",
              "jabatan": "PENGAWAS",
              "basic_salaray": 10000000,
              "allowance": 5000000,
              "meal_allowance_per_attend": 12000
            },
            {
              "id": 46,
              "name": "MARWAS",
              "jabatan": "PENGAWAS",
              "basic_salaray": 5194000,
              "allowance": "",
              "meal_allowance_per_attend": 12000
            },
            {
              "id": 45,
              "name": "SAMIAN",
              "jabatan": "PENGAWAS",
              "basic_salaray": 5194000,
              "allowance": 1000000,
              "meal_allowance_per_attend": 12000
            },
            {
              "id": 42,
              "name": "M. FADLIILAH NOOR",
              "jabatan": "QC",
              "basic_salaray": 3394000,
              "allowance": "",
              "meal_allowance_per_attend": 12000
            },
            {
              "id": 43,
              "name": "YULIUS ALDI",
              "jabatan": "QC",
              "basic_salaray": 3394000,
              "allowance": 0,
              "meal_allowance_per_attend": 12000
            },
            {
              "id": 59,
              "name": "SITI LATIFAH",
              "jabatan": "MARKETING",
              "basic_salaray": 3394000,
              "allowance": "",
              "meal_allowance_per_attend": 12000
            },
            {
              "id": 56,
              "name": "SHEPIA SARASWATI",
              "jabatan": "ADMIN A/R",
              "basic_salaray": 3394000,
              "allowance": 500000,
              "meal_allowance_per_attend": 12000
            },
            {
              "id": 47,
              "name": "SOLA PUGORIA G",
              "jabatan": "ACCOUNTING",
              "basic_salaray": 10194000,
              "allowance": 0,
              "meal_allowance_per_attend": 12000
            },
            {
              "id": 48,
              "name": "POPPY EKA ALFIATIN",
              "jabatan": "ADMIN A/R",
              "basic_salaray": 3394000,
              "allowance": 300000,
              "meal_allowance_per_attend": 12000
            },
            {
              "id": 49,
              "name": "BUDIYANTO",
              "jabatan": "PURCHASING",
              "basic_salaray": 6194000,
              "allowance": 0,
              "meal_allowance_per_attend": 12000
            },
            {
              "id": 50,
              "name": "EFRASI U",
              "jabatan": "PURCHASING",
              "basic_salaray": 3394000,
              "allowance": 0,
              "meal_allowance_per_attend": 12000
            },
            {
              "id": 53,
              "name": "DESI MUNAWARAH",
              "jabatan": "CASHIER",
              "basic_salaray": 3394000,
              "allowance": 0,
              "meal_allowance_per_attend": 12000
            },
            {
              "id": 52,
              "name": "MILDA YANTI",
              "jabatan": "CASHIER",
              "basic_salaray": 3394000,
              "allowance": 500000,
              "meal_allowance_per_attend": 12000
            },
            {
              "id": 51,
              "name": "NOVI YULIANTI",
              "jabatan": "CASHIER",
              "basic_salaray": 8194000,
              "allowance": "",
              "meal_allowance_per_attend": 12000
            },
            {
              "id": 55,
              "name": "TIRTA DEWI M",
              "jabatan": "ADMIN",
              "basic_salaray": 3394000,
              "allowance": 300000,
              "meal_allowance_per_attend": 12000
            },
            {
              "id": 54,
              "name": "RIYA JULI YANTI",
              "jabatan": "ADMIN",
              "basic_salaray": 3394000,
              "allowance": "",
              "meal_allowance_per_attend": 12000
            },
            {
              "id": 58,
              "name": "DESKA",
              "jabatan": "MARKETING",
              "basic_salaray": 4194000,
              "allowance": 0,
              "meal_allowance_per_attend": 12000
            },
            {
              "id": 60,
              "name": "SANDRA",
              "jabatan": "CLEANING",
              "basic_salaray": 3394000,
              "allowance": 0,
              "meal_allowance_per_attend": 12000
            },
            {
              "id": 64,
              "name": "ADISTIANI",
              "jabatan": "LOGISTIC/GUDANG",
              "basic_salaray": 3394000,
              "allowance": 0,
              "meal_allowance_per_attend": 0
            },
            {
              "id": 62,
              "name": "DEDDY GOEIJ",
              "jabatan": "LOGISTIC/GUDANG",
              "basic_salaray": 4194000,
              "allowance": 0,
              "meal_allowance_per_attend": 0
            },
            {
              "id": 63,
              "name": "DIANA",
              "jabatan": "LOGISTIC/GUDANG",
              "basic_salaray": 3394000,
              "allowance": 0,
              "meal_allowance_per_attend": 0
            },
            {
              "id": 65,
              "name": "FERRIANSYAH",
              "jabatan": "LOGISTIC/GUDANG",
              "basic_salaray": 3394000,
              "allowance": 800000,
              "meal_allowance_per_attend": 0
            },
            {
              "id": 61,
              "name": "MASNA",
              "jabatan": "LOGISTIC/GUDANG",
              "basic_salaray": 3394000,
              "allowance": 0,
              "meal_allowance_per_attend": 0
            },
            {
              "id": 13,
              "name": "YULINAR RAHMAD",
              "jabatan": "LOGISTIC/GUDANG",
              "basic_salaray": 3394000,
              "allowance": 300000,
              "meal_allowance_per_attend": 0
            },
            {
              "id": 109,
              "name": "SUDARTO",
              "jabatan": "KEBUN",
              "basic_salaray": 3394000,
              "allowance": 0,
              "meal_allowance_per_attend": 0
            },
            {
              "id": 110,
              "name": "ROHANI",
              "jabatan": "KEBUN",
              "basic_salaray": 3394000,
              "allowance": 0,
              "meal_allowance_per_attend": 0
            },
            {
              "id": 75,
              "name": "ARI JUNAIDI",
              "jabatan": "ELECTRIC",
              "basic_salaray": 3794000,
              "allowance": 1400000,
              "meal_allowance_per_attend": 12000
            },
            {
              "id": 76,
              "name": "M. RAHMATULLAH",
              "jabatan": "ELECTRIC",
              "basic_salaray": 3394000,
              "allowance": 300000,
              "meal_allowance_per_attend": 12000
            },
            {
              "id": 77,
              "name": "MUJIONO",
              "jabatan": "ELECTRIC",
              "basic_salaray": 3394000,
              "allowance": 300000,
              "meal_allowance_per_attend": 12000
            },
            {
              "id": 67,
              "name": "JAMHURI ARIFIN",
              "jabatan": "BUBUT",
              "basic_salaray": 6194000,
              "allowance": 500000,
              "meal_allowance_per_attend": 12000
            },
            {
              "id": 68,
              "name": "RENDRA S",
              "jabatan": "BUBUT",
              "basic_salaray": 3394000,
              "allowance": 0,
              "meal_allowance_per_attend": 12000
            },
            {
              "id": 69,
              "name": "EDI NUGROHO",
              "jabatan": "BUBUT",
              "basic_salaray": 3394000,
              "allowance": 0,
              "meal_allowance_per_attend": 12000
            },
            {
              "id": 70,
              "name": "SUKOYO",
              "jabatan": "BUBUT",
              "basic_salaray": 4500000,
              "allowance": 0,
              "meal_allowance_per_attend": 12000
            },
            {
              "id": 74,
              "name": "ADITIYA",
              "jabatan": "HELP. MEKANIK",
              "basic_salaray": 3394000,
              "allowance": 0,
              "meal_allowance_per_attend": 0
            },
            {
              "id": 71,
              "name": "RUSDI",
              "jabatan": "MEKANIK",
              "basic_salaray": 3794000,
              "allowance": 900000,
              "meal_allowance_per_attend": 12000
            },
            {
              "id": 73,
              "name": "SURATNO",
              "jabatan": "MEKANIK",
              "basic_salaray": 5194000,
              "allowance": 0,
              "meal_allowance_per_attend": 12000
            },
            {
              "id": 79,
              "name": "AKMAL",
              "jabatan": "OPERATOR",
              "basic_salaray": 3394000,
              "allowance": 800000,
              "meal_allowance_per_attend": 12000
            },
            {
              "id": 81,
              "name": "BUDI",
              "jabatan": "REGER",
              "basic_salaray": 3394000,
              "allowance": 200000,
              "meal_allowance_per_attend": 0
            },
            {
              "id": 78,
              "name": "SURDI",
              "jabatan": "OPERATOR",
              "basic_salaray": 3394000,
              "allowance": 300000,
              "meal_allowance_per_attend": 0
            },
            {
              "id": 80,
              "name": "TAKDIR",
              "jabatan": "OPERATOR",
              "basic_salaray": 3394000,
              "allowance": 300000,
              "meal_allowance_per_attend": 12000
            },
            {
              "id": 82,
              "name": "SYAMSUDDIN",
              "jabatan": "OPERATOR",
              "basic_salaray": 3394000,
              "allowance": 200000,
              "meal_allowance_per_attend": 12000
            },
            {
              "id": 84,
              "name": "ASEP SURIANTO",
              "jabatan": "WELDER",
              "basic_salaray": 3394000,
              "allowance": 300000,
              "meal_allowance_per_attend": 12000
            },
            {
              "id": 83,
              "name": "DJUMIYADI",
              "jabatan": "WELDER",
              "basic_salaray": 3394000,
              "allowance": 550000,
              "meal_allowance_per_attend": 12000
            },
            {
              "id": 85,
              "name": "MUHLIS",
              "jabatan": "WELDER",
              "basic_salaray": 3394000,
              "allowance": 300000,
              "meal_allowance_per_attend": 12000
            },
            {
              "id": 86,
              "name": "RIKO AHMAD RINALDI",
              "jabatan": "WELDER",
              "basic_salaray": 3394000,
              "allowance": 200000,
              "meal_allowance_per_attend": 12000
            },
            {
              "id": 87,
              "name": "EKO NURWANTORO",
              "jabatan": "FITTER",
              "basic_salaray": 3394000,
              "allowance": 550000,
              "meal_allowance_per_attend": 12000
            },
            {
              "id": 91,
              "name": "FERIYANUFFRI",
              "jabatan": "FITTER",
              "basic_salaray": 3394000,
              "allowance": 200000,
              "meal_allowance_per_attend": 12000
            },
            {
              "id": 92,
              "name": "ISKANDAR",
              "jabatan": "FITTER",
              "basic_salaray": 3394000,
              "allowance": 300000,
              "meal_allowance_per_attend": 12000
            },
            {
              "id": 88,
              "name": "RIKI APRI YANSYA",
              "jabatan": "FITTER",
              "basic_salaray": 3394000,
              "allowance": 500000,
              "meal_allowance_per_attend": 12000
            },
            {
              "id": 89,
              "name": "ROMANSYAH",
              "jabatan": "FITTER",
              "basic_salaray": 3394000,
              "allowance": 300000,
              "meal_allowance_per_attend": 12000
            },
            {
              "id": 90,
              "name": "RUSLIYADI",
              "jabatan": "FITTER",
              "basic_salaray": 3394000,
              "allowance": 300000,
              "meal_allowance_per_attend": 12000
            },
            {
              "id": 93,
              "name": "ACHMAD NOOR",
              "jabatan": "HELP. PROPELLER",
              "basic_salaray": 3394000,
              "allowance": 0,
              "meal_allowance_per_attend": 12000
            },
            {
              "id": 101,
              "name": "AHMAD",
              "jabatan": "HELPER",
              "basic_salaray": 3394000,
              "allowance": 0,
              "meal_allowance_per_attend": 0
            },
            {
              "id": 96,
              "name": "ALDILAH IKBAL",
              "jabatan": "HELPER",
              "basic_salaray": 3394000,
              "allowance": 0,
              "meal_allowance_per_attend": 12000
            },
            {
              "id": 106,
              "name": "AMIRUDIN",
              "jabatan": "HELP. PROPELLER",
              "basic_salaray": 3394000,
              "allowance": 0,
              "meal_allowance_per_attend": 12000
            },
            {
              "id": 94,
              "name": "ELMI RIYADI",
              "jabatan": "HELPER",
              "basic_salaray": 3394000,
              "allowance": 0,
              "meal_allowance_per_attend": 12000
            },
            {
              "id": 107,
              "name": "ERWIN",
              "jabatan": "HELPER",
              "basic_salaray": 3394000,
              "allowance": 0,
              "meal_allowance_per_attend": 12000
            },
            {
              "id": 99,
              "name": "FAISAL",
              "jabatan": "HELPER",
              "basic_salaray": 3394000,
              "allowance": 0,
              "meal_allowance_per_attend": 12000
            },
            {
              "id": 97,
              "name": "HARLY SUDIANTYO",
              "jabatan": "HELPER",
              "basic_salaray": 3394000,
              "allowance": 0,
              "meal_allowance_per_attend": 12000
            },
            {
              "id": 103,
              "name": "AIRA INDRA",
              "jabatan": "HELPER",
              "basic_salaray": 3394000,
              "allowance": 0,
              "meal_allowance_per_attend": 0
            },
            {
              "id": 105,
              "name": "JUMRAN",
              "jabatan": "HELPER",
              "basic_salaray": 3394000,
              "allowance": 0,
              "meal_allowance_per_attend": 12000
            },
            {
              "id": 102,
              "name": "M. FATWA",
              "jabatan": "HELPER",
              "basic_salaray": 3394000,
              "allowance": 0,
              "meal_allowance_per_attend": 12000
            },
            {
              "id": 100,
              "name": "M. SUKRI",
              "jabatan": "HELPER",
              "basic_salaray": 3394000,
              "allowance": 0,
              "meal_allowance_per_attend": 12000
            },
            {
              "id": 98,
              "name": "RAHUL",
              "jabatan": "HELPER",
              "basic_salaray": 3394000,
              "allowance": 0,
              "meal_allowance_per_attend": 12000
            },
            {
              "id": 104,
              "name": "SATRIA AGUNG",
              "jabatan": "HELPER",
              "basic_salaray": 3394000,
              "allowance": 0,
              "meal_allowance_per_attend": 12000
            },
            {
              "id": 95,
              "name": "SUNARTO",
              "jabatan": "HELPER",
              "basic_salaray": 3394000,
              "allowance": 0,
              "meal_allowance_per_attend": 12000
            },
            {
              "id": 9,
              "name": "AHOK",
              "jabatan": "",
              "basic_salaray": 500000,
              "allowance": 0,
              "meal_allowance_per_attend": 0
            },
            {
              "id": 108,
              "name": "WISNU",
              "jabatan": "HELP",
              "basic_salaray": 3394000,
              "allowance": 0,
              "meal_allowance_per_attend": 12000
            },
            {
              "id": 111,
              "name": "ARDIAN",
              "jabatan": "DRIVER",
              "basic_salaray": 3394000,
              "allowance": 0,
              "meal_allowance_per_attend": 0
            },
            {
              "id": 26,
              "name": "M. NASRULLAH",
              "jabatan": "REP. BALON",
              "basic_salaray": 3394000,
              "allowance": 500000,
              "meal_allowance_per_attend": 0
            },
            {
              "id": 27,
              "name": "HENDRIC A",
              "jabatan": "REP. BALON",
              "basic_salaray": 3394000,
              "allowance": 0,
              "meal_allowance_per_attend": 0
            },
            {
              "id": 31,
              "name": "MARWANTO",
              "jabatan": "AIRBAG",
              "basic_salaray": 3394000,
              "allowance": 0,
              "meal_allowance_per_attend": 0
            },
            {
              "id": 29,
              "name": "SAMSURI",
              "jabatan": "AIRBAG",
              "basic_salaray": 5694000,
              "allowance": 0,
              "meal_allowance_per_attend": 0
            },
            {
              "id": 30,
              "name": "TURYADI",
              "jabatan": "AIRBAG",
              "basic_salaray": 3394000,
              "allowance": 0,
              "meal_allowance_per_attend": 0
            },
            {
              "id": 21,
              "name": "MISRANSYAH",
              "jabatan": "KEBUN",
              "basic_salaray": 3394000,
              "allowance": 0,
              "meal_allowance_per_attend": 0
            },
            {
              "id": 22,
              "name": "SONI",
              "jabatan": "KEBUNAIRBAG",
              "basic_salaray": 3394000,
              "allowance": 0,
              "meal_allowance_per_attend": 0
            },
            {
              "id": 23,
              "name": "SUYADI",
              "jabatan": "KEBUN",
              "basic_salaray": 3394000,
              "allowance": 0,
              "meal_allowance_per_attend": 0
            },
            {
              "id": 8,
              "name": "SYARIFUDDIN",
              "jabatan": "HRD",
              "basic_salaray": 5194000,
              "allowance": 0,
              "meal_allowance_per_attend": 0
            },
            {
              "id": 116,
              "name": "BARLIANSYAH NUR",
              "jabatan": "HSE",
              "basic_salaray": 3394000,
              "allowance": 0,
              "meal_allowance_per_attend": 300000
            },
            {
              "id": 6,
              "name": "ABADI SITORUS",
              "jabatan": "QC",
              "basic_salaray": 3394000,
              "allowance": 0,
              "meal_allowance_per_attend": 540000
            },
            {
              "id": 7,
              "name": "ALFRED MICHAEL",
              "jabatan": "QC",
              "basic_salaray": 3394000,
              "allowance": 0,
              "meal_allowance_per_attend": 408000
            },
            {
              "id": 3,
              "name": "BHINARNO",
              "jabatan": "PENGAWAS",
              "basic_salaray": 7194000,
              "allowance": 1000000,
              "meal_allowance_per_attend": 0
            },
            {
              "id": 1,
              "name": "M. ADAM",
              "jabatan": "PENGAWAS",
              "basic_salaray": 3794000,
              "allowance": 2000000,
              "meal_allowance_per_attend": 12000
            },
            {
              "id": 2,
              "name": "YOSEF",
              "jabatan": "PENGAWAS",
              "basic_salaray": 6194000,
              "allowance": 0,
              "meal_allowance_per_attend": 0
            },
            {
              "id": 5,
              "name": "JANTORO GULTOM",
              "jabatan": "ASS MEKANIK",
              "basic_salaray": 5000000,
              "allowance": "",
              "meal_allowance_per_attend": 12000
            },
            {
              "id": 9,
              "name": "ARDIANSJAH JOHAN",
              "jabatan": "LOGISTIC/GUDANG",
              "basic_salaray": 4694000,
              "allowance": 500000,
              "meal_allowance_per_attend": 12000
            },
            {
              "id": 10,
              "name": "AYONG/ LO PUNGUONG",
              "jabatan": "LOGISTIC/GUDANG",
              "basic_salaray": 3394000,
              "allowance": 0,
              "meal_allowance_per_attend": 12000
            },
            {
              "id": 11,
              "name": "JAPRI",
              "jabatan": "LOGISTIC/GUDANG",
              "basic_salaray": 3394000,
              "allowance": 0,
              "meal_allowance_per_attend": 12000
            },
            {
              "id": 12,
              "name": "MELLA",
              "jabatan": "LOGISTIC/GUDANG",
              "basic_salaray": 3394000,
              "allowance": 0,
              "meal_allowance_per_attend": 12000
            },
            {
              "id": 33,
              "name": "AVET ATAN",
              "jabatan": "DRIVER",
              "basic_salaray": 3394000,
              "allowance": 0,
              "meal_allowance_per_attend": 0
            },
            {
              "id": 32,
              "name": "M. YUNUS",
              "jabatan": "DRIVER",
              "basic_salaray": 3394000,
              "allowance": 0,
              "meal_allowance_per_attend": 0
            },
            {
              "id": 14,
              "name": "AGUS BAGIO",
              "jabatan": "MEKANIK",
              "basic_salaray": 5194000,
              "allowance": 1000000,
              "meal_allowance_per_attend": 0
            },
            {
              "id": 15,
              "name": "HARIYADI",
              "jabatan": "MEKANIK",
              "basic_salaray": 3394000,
              "allowance": 800000,
              "meal_allowance_per_attend": 0
            },
            {
              "id": 16,
              "name": "SAMSUL BASO",
              "jabatan": "HELP MEKANIK",
              "basic_salaray": 3394000,
              "allowance": 0,
              "meal_allowance_per_attend": 0
            },
            {
              "id": 34,
              "name": "AMIRUDIN",
              "jabatan": "OPERATOR",
              "basic_salaray": 3394000,
              "allowance": 200000,
              "meal_allowance_per_attend": 0
            },
            {
              "id": 35,
              "name": "ASARI",
              "jabatan": "OPERATOR",
              "basic_salaray": 3394000,
              "allowance": 606000,
              "meal_allowance_per_attend": 0
            },
            {
              "id": 37,
              "name": "RABIAL FITRI",
              "jabatan": "OPERATOR",
              "basic_salaray": 3394000,
              "allowance": 0,
              "meal_allowance_per_attend": 0
            },
            {
              "id": 38,
              "name": "SEKO",
              "jabatan": "OPERATOR",
              "basic_salaray": 3394000,
              "allowance": 500000,
              "meal_allowance_per_attend": 12000
            },
            {
              "id": 36,
              "name": "AMIRUDDIN /SAMIR",
              "jabatan": "OPERATOR",
              "basic_salaray": 3394000,
              "allowance": 0,
              "meal_allowance_per_attend": 0
            },
            {
              "id": 39,
              "name": "IRWAN",
              "jabatan": "OPERATOR",
              "basic_salaray": 3394000,
              "allowance": 0,
              "meal_allowance_per_attend": 0
            },
            {
              "id": 117,
              "name": "ABDUL RAHMAN",
              "jabatan": "HELP OPERATOR",
              "basic_salaray": 2030000,
              "allowance": 0,
              "meal_allowance_per_attend": 0
            },
            {
              "id": 18,
              "name": "RYAN JULIAN",
              "jabatan": "ELECTRIC",
              "basic_salaray": 3394000,
              "allowance": 300000,
              "meal_allowance_per_attend": 0
            },
            {
              "id": 19,
              "name": "SUWITO",
              "jabatan": "ELECTRIC",
              "basic_salaray": 3394000,
              "allowance": 800000,
              "meal_allowance_per_attend": 12000
            },
            {
              "id": 20,
              "name": "AZIS",
              "jabatan": "ELECTRIC",
              "basic_salaray": 2030000,
              "allowance": 0,
              "meal_allowance_per_attend": 0
            },
            {
              "id": 112,
              "name": "ROHMANI",
              "jabatan": "ELECTRIC",
              "basic_salaray": 2100000,
              "allowance": 0,
              "meal_allowance_per_attend": 0
            },
            {
              "id": 24,
              "name": "M. IRAWAN",
              "jabatan": "HELPER",
              "basic_salaray": 3394000,
              "allowance": 0,
              "meal_allowance_per_attend": 0
            },
            {
              "id": 28,
              "name": "AHMAD ARDIANSYAH",
              "jabatan": "REP. BALON",
              "basic_salaray": 2600000,
              "allowance": 0,
              "meal_allowance_per_attend": 0
            }
          ]');


          foreach ($datas as $key => $d) {
            // Karyawa

            $overtime_rate_per_hour = \round(($d->basic_salaray+$d->allowance)/173);

            if($d->meal_allowance_per_attend > 0){
                $d->meal_allowance_per_attend = 12000;
            }
            Employee::where('id',$d->id)
            ->update([
                'basic_salary'=>$d->basic_salaray,
                'allowance'=>$d->allowance,
                'meal_allowance_per_attend'=>$d->meal_allowance_per_attend,
                'transport_allowance_per_attend'=>0,
                'attend_allowance_per_attend'=>0,
                'overtime_rate_per_hour'=>$overtime_rate_per_hour
            ]);
            print("UPDATE karyawan id=>".$d->id." \n");
          }
    }
}
