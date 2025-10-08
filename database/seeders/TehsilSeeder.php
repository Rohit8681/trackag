<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TehsilSeeder extends Seeder
{
    public function run()
    {
        

        $data = [
            'AndhraPradesh' => [
                'Anantapur' => [
                    'D.Hirehal', 'Rayadurg', 'Kanekal', 'Bommanahal', 'Vidapanakal', 'Guntakal', 'Gooty',
                    'Peddavadugur', 'Yadiki', 'Tadpatri', 'Peddapappur', 'Pamidi', 'Vijrakarur', 'Uravakonda',
                    'Beluguppa', 'Gummagatta', 'Brahmasamudram', 'Kalyandurg', 'Atmakur', 'Kudair', 'Garladinne',
                    'Singanamala', 'Putlur', 'Yellanur', 'Narpala', 'Bukkarayasamudram', 'Anantapur', 'Raptadu',
                    'Settur', 'Kundurpi', 'Kambadur', 'Kanaganapalle', 'Dharmavaram', 'Bathalapalle', 'Tadimarri',
                    'Mudigubba', 'Talupula', 'Nambulapulakunta', 'Gandlapenta', 'Kadiri', 'Nallamada',
                    'Bukkapatnam', 'Kothacheruvu', 'Chennekothapalle', 'Ramagiri', 'Roddam', 'Madakasira',
                    'Amarapuram', 'Gudibanda', 'Rolla', 'Agali', 'Parigi', 'Penukonda', 'Puttaparthi',
                    'Obuladevarecheruvu', 'Nallacheruvu', 'Tanakallu', 'Amadagur', 'Gorantla', 'Somandepalle',
                    'Hindupur', 'Lepakshi', 'Chilamathur'
                ],
                'Chittoor' => [
                    'Gangavaram', 'Thavanampalle', 'Srirangarajapuram', 'Gangadhara Nellore', 'Chittoor',
                    'Palamaner', 'Baireddipalle', 'Venkatagirikota', 'Santhipuram', 'Gudupalle', 'Kuppam',
                    'Ramakuppam', 'Bangarupalyam', 'Yadamari', 'Gudipala', 'Palasamudram', 'Mulakalacheruvu',
                    'Thamballapalle', 'Peddamandyam', 'Gurramkonda', 'Kalakada', 'Kambhamvaripalle',
                    'Rompicherla', 'Yerravaripalem', 'Tirupathi (Rural)', 'Renigunta', 'Yerpedu',
                    'Srikalahasti', 'Thottambedu', 'Buchinaidu Khandriga', 'Varadaiahpalem', 'K.V.B.Puram',
                    'Tirupati (Urban)', 'Chandragiri', 'Chinnagottigallu', 'Piler', 'Kalikiri', 'Vayalpad',
                    'Kurabalakota', 'Peddathippa samudram', 'B.Kothakota', 'Madanapalle', 'Nimmanapalle',
                    'Sodum', 'Pulicherla', 'Pakala', 'Vedurukuppam', 'Ramachandra puram', 'Vadamalapeta',
                    'Narayanavanam', 'Pitchatur', 'Satyavedu', 'Nagalapuram', 'Nindra', 'Vijayapuram',
                    'Nagari', 'Puttur', 'Karvetinagar', 'Penumur', 'Puthalapattu', 'Irala', 'Somala',
                    'Chowdepalle', 'Ramasamudram', 'Punganur', 'Peddapanjani'
                ],
                'Cuddapah' => [
                    'Kondapuram', 'Mylavaram', 'Peddamudium', 'Rajupalem', 'Duvvur', 'S.Mydukur',
                    'Brahmamgarimattam', 'Sri Avadhuth kasinayana', 'Kalasapadu', 'Porumamilla', 'B.Kodur',
                    'Badvel', 'Gopavaram', 'Khajipet', 'Chapadu', 'Proddatur', 'Jammalamadugu', 'Muddanur',
                    'Simhadripuram', 'Lingala', 'Pulivendla', 'Vemula', 'Thondur', 'Veerapunayunipalle',
                    'Yerraguntla', 'Kamalapuram', 'Vallur', 'Chennur', 'Atlur', 'Vontimitta', 'Sidhout',
                    'Cuddapah', 'Chinthakommadinne', 'Pendlimarry', 'Vempalle', 'Chakarayapet', 'Galiveedu',
                    'Chinnamudiam', 'Sambepalle', 'T.Sundupalle', 'Rayachoti', 'Lakkireddipalle', 'Ramapuram',
                    'Veeraballe', 'Nandalur', 'Penagalur', 'Chitvel', 'Rajampet', 'Pullampet',
                    'Obulavaripalle', 'Rly.kodur'
                ],
                'East Godavari' => [
                    'Maredumilli', 'Devipatnam', 'Y. Ramavaram', 'Addateegala', 'Rajavommangi', 'Kotananduru',
                    'Tuni', 'Sankhavaram', 'Yeleswaram', 'Kunavaram', 'Chintur', 'Gangavaram',
                    'Rampachodavaram', 'Vararamachandrapuram', 'Seethanagaram', 'Gokavaram', 'Jaggampeta',
                    'Kirlampudi', 'Prathipadu', 'Thondangi', 'Gollaprolu', 'Peddapuram', 'Gandepalle',
                    'Korukonda', 'Rajahmundry (U)', 'Rajahmundry Rural', 'Rajanagaram', 'Rangampeta',
                    'Samalkota', 'Pithapuram', 'Kothapalle', 'Kakinada Rural', 'Kakinada (U)', 'Pedapudi',
                    'Biccavolu', 'Anaparthy', 'Kadiam', 'Atreyapuram', 'Mandapeta', 'Rayavaram', 'Karapa',
                    'Kajuluru', 'Ramachandrapuram', 'Alamuru', 'Ravulapalem', 'Kothapeta', 'Kapileswarapuram',
                    'Pamarru', 'Thallarevu', 'I. Polavaram', 'Mummidivaram', 'Ainavilli', 'P.Gannavaram',
                    'Ambajipeta', 'Mamidikuduru', 'Razole', 'Malikipuram', 'Sakhinetipalle', 'Allavaram',
                    'Amalapuram', 'Uppalaguptam', 'Katrenikona'
                ],
                'Guntur' => [
                    'Macherla', 'Veldurthy', 'Durgi', 'Rentachintala', 'Gurazala', 'Dachepalle', 'Karempudi',
                    'Piduguralla', 'Machavaram', 'Bellamkonda', 'Atchampet', 'Krosuru', 'Amaravathi',
                    'Thullur', 'Tadepalle', 'Mangalagiri', 'Tadikonda', 'Pedakurapadu', 'Sattenapalle',
                    'Rajupalem', 'Nekarikallu', 'Bollapalle', 'Vinukonda', 'Nuzendla',
                    'Savalyapuram Kanumarlapudi', 'Ipur', 'Rompicherla', 'Narasaraopeta', 'Muppalla',
                    'Nadendla', 'Chilakaluripet H/o.Purushotha Patnam', 'Edlapadu', 'Phirangipuram',
                    'Medikonduru', 'Guntur', 'Pedakakani', 'Duggirala', 'Kollipara', 'Tenali', 'Chebrolu',
                    'Vatticherukuru', 'Prathipadu', 'Pedanandipadu', 'Kakumanu', 'Ponnur', 'Tsundur',
                    'Amruthalur', 'Vemuru', 'Kollur', 'Bhattiprolu'
                ],
                'Krishna' => [
                    'Vatsavai', 'Jaggayyapeta', 'Penuganchiprolu', 'Mylavaram', 'Gampalagudem', 'Tiruvuru',
                    'A.Konduru', 'Reddigudem', 'Vissannapeta', 'Chatrai', 'Musunuru', 'Nuzvid', 'Bapulapadu',
                    'Agiripalle', 'G.Konduru', 'Kanchikacherla', 'Chandarlapadu', 'Ibrahimpatnam',
                    'Vijayawada (Urban)', 'Vijayawada (Rural)', 'Gannavaram', 'Unguturu', 'Nandivada',
                    'Mandavalli', 'Kalidindi', 'Kruthivennu', 'Bantumilli', 'Mudinepalle', 'Gudivada',
                    'Pedaparupudi', 'Kankipadu', 'Penamaluru', 'Thotlavalluru', 'Pamidimukkala', 'Vuyyuru',
                    'Pamarru', 'Gudlavalleru', 'Pedana', 'Guduru', 'Movva', 'Ghantasala', 'Machilipatnam',
                    'Challapalle', 'Mopidevi', 'Avanigadda', 'Nagayalanka', 'Koduru', 'Nandigama',
                    'Veerullapadu', 'Kaikalur'
                ],
                'Kurnool' => [
                    'Mantralayam', 'Kosigi', 'Kowthalam', 'Pedda kadabur', 'Yemmiganur', 'Nandavaram',
                    'C.Belagal', 'Gudur', 'Kallur', 'Kurnool', 'Nandikotkur', 'Pagidyala', 'Jupadu bungalow',
                    'Kothapalle', 'Srisailam', 'Atmakur', 'Pamulapadu', 'Midthur', 'Orvakal', 'Kodumur',
                    'Gonegandla', 'Adoni', 'Holagunda', 'Halaharvi', 'Alur', 'Aspari', 'Devanakonda',
                    'Krishnagiri', 'Veldurthi', 'Bethamcherla', 'Panyam', 'Gadivemula', 'Velgode',
                    'Bandi Atmakur', 'Nandyal', 'Mahanandi', 'Sirvel', 'Gospadu', 'Banaganapalle', 'Dhone',
                    'Pathikonda', 'Chippagiri', 'Maddikera (East)', 'Tuggali', 'Peapally', 'Owk',
                    'Koilkuntla', 'Rudravaram', 'Allagadda', 'Dorni', 'Sanjamala', 'Kolimigundla',
                    'Uyyalawada', 'Chagalamarri'
                ],
                'Nellore' => [
                    'Seetharamapuram', 'Udayagiri', 'Varikuntapadu', 'Kondapuram', 'Jaladanki', 'Kavali',
                    'Bogole', 'Kaligiri', 'Vinjamur', 'Duttalur', 'Marripadu', 'Atmakur',
                    'Anumasamudrampeta', 'Dagadarthi', 'Allur', 'Vidavalur', 'Kodavalur', 'Buchireddipalem',
                    'Sangam', 'Chejerla', 'Ananthasagaram', 'Kaluvoya', 'Rapur', 'Podalakur', 'Nellore',
                    'Kovur', 'Indukurpet', 'Thotapalligudur', 'Muthukur', 'Venkatachalam', 'Manubolu',
                    'Gudur', 'Sydapuram', 'Dakkili', 'Venkatagiri', 'Balayapalle', 'Ozili', 'Chillakur',
                    'Kota', 'Vakadu', 'Chittamur', 'Naidupet', 'Pellakur', 'Doravarisatram', 'Sullurpeta',
                    'Tada'
                ],
                'Prakasam' => [
                    'Yerragondapalem', 'Pullalacheruvu', 'Tripuranthakam', 'Dornala', 'Pedda Raveedu',
                    'Donakonda', 'Kurichedu', 'Santhamaguluru', 'Ballikurava', 'Martur', 'Yeddana pudi',
                    'Parchur', 'Karamchedu', 'Inkollu', 'Janakavarampanguluru', 'Addanki', 'Mundlamuru',
                    'Darsi', 'Markapur', 'Ardhaveedu', 'Cumbum', 'Tarlupadu', 'Konakanamitla', 'Podili',
                    'Thallur', 'Korisapadu', 'Chirala', 'Vetapalem', 'Chinaganjam', 'Naguluppala padu',
                    'Maddipadu', 'Chimakurthy', 'Marripudi', 'Hanumanthuni padu', 'Bestavaripeta',
                    'Racherla', 'Giddaluru', 'Komarolu', 'Veligandla', 'Kanigiri', 'Kondapi',
                    'Santhanuthala padu', 'Ongole', 'Kotha patnam', 'Tangutur', 'Zarugumilli', 'Ponnaluru',
                    'Pedacherlo palle', 'Chandra sekhara puram', 'Voletivaripalem', 'Kandukur',
                    'Singarayakonda', 'Lingasamudram', 'Gudlur', 'Ulavapadu'
                ],
                'Srikakulam' => [
                    'Veeraghattam', 'Seethampeta', 'Bhamini', 'Kothuru', 'Pathapatnam', 'Meliaputti',
                    'Palasa', 'Mandasa', 'Kanchili', 'Ichchapuram', 'Kaviti', 'Sompeta', 'Vajrapukothuru',
                    'Nandigam', 'Hiramandalam', 'Palakonda', 'Vangara', 'Regidi Amadalavalasa',
                    'Laxminarasupeta', 'Saravakota', 'Tekkali', 'Santhabommali', 'Kotabommali', 'Jalumuru',
                    'Sarubujjili', 'Burja', 'Amadalavalasa', 'Srikakulam', 'Gara', 'Polaki', 'Narasannapeta',
                    'Ponduru', 'Laveru', 'Ranastalam', 'Etcherla', 'Ganguvarisigadam'
                ],
                'Visakhapatnam' => [
                    'Munchingiputtu', 'Peda Bayalu', 'Hukumpeta', 'Paderu', 'G.Madugula', 'Chintapalle',
                    'G.K.Veedhi', 'Koyyuru', 'Dumbriguda', 'Araku Valley', 'Ananthagiri', 'Sunkarametta',
                    'Pedagantyada', 'Sabbavaram', 'Pendurthi', 'Kothavalasa', 'Narayanapatnam', 'Narsipatnam',
                    'Ravikamatham', 'Rolugunta', 'Kotauratla', 'Makavarapalem', 'Nathavaram', 'Golugonda',
                    'Cheedikada', 'Madugula', 'Devarapalle', 'K.Kotapadu', 'Bheemunipatnam', 'Anandapuram',
                    'Padmanabham', 'S.Rayavaram', 'Paravada', 'Visakhapatnam Rural', 'Visakhapatnam Urban',
                    'Chodavaram', 'Butchayyapeta', 'Yelamanchili', 'Payakaraopeta', 'Atchutapuram', 'Kasimkota',
                    'Anakapalle'
                ],
                'Vizianagaram' => [
                    'Makkuva', 'Salur', 'Pachipenta', 'Mentada', 'Komarada', 'Jiyyammavalasa', 'Kurupam',
                    'Gummalakshmipuram', 'Therlam', 'Bobbili', 'Ramabhadrapuram', 'Badangi', 'Garividi',
                    'Cheepurupalle', 'Merakamudidam', 'Dattirajeru', 'Gajapathinagaram', 'Bondapalle',
                    'Gurla', 'Nellimarla', 'Pusapatirega', 'Bhogapuram', 'Denkada', 'Lakkavarapukota',
                    'Srungavarapukota', 'Vepada', 'Kothavalasa', 'Jami', 'Vizianagaram', 'Gantyada'
                ],
                'West Godavari' => [
                    'Chintalapudi', 'Kamavarapukota', 'T.Narasapuram', 'Polavaram', 'Buttayagudem',
                    'Jeelugumilli', 'Koyyalagudem', 'Jangareddigudem', 'Dwaraka Tirumala', 'Nallajerla',
                    'Pedapadu', 'Eluru', 'Denduluru', 'Pedavegi', 'Bhimadole', 'Gopalapuram', 'Kalla',
                    'Peravali', 'Nidadavole', 'Undrajavaram', 'Kovvur', 'Chagallu', 'Devarapalle',
                    'Tadepalligudem', 'Pentapadu', 'Tanuku', 'Attili', 'Ganapavaram', 'Akividu', 'Undi',
                    'Palacoderu', 'Yelamanchili', 'Poduru', 'Achanta', 'Narasapuram', 'Mogalthur',
                    'Bhimavaram', 'Veeravasaram', 'Palacole', 'Kalla'
                ]
            ],
            'WestBengal' => [
                'Bankura' => [
                    'Bankura - I', 'Bankura - II', 'Barjora', 'Chhatna', 'Gangajalghati', 'Hirbandh',
                    'Indpur', 'Khatra', 'Kotulpur', 'Mejia', 'Onda', 'Patrasayer', 'Raipur', 'Ranibundh',
                    'Saltora', 'Sarenga', 'Simlapal', 'Sonamukhi', 'Taldangra', 'Vishnupur', 'Indas',
                    'Joypur'
                ],
                'Barddhaman' => [
                    'Ausgram - I', 'Ausgram - II', 'Bhatar', 'Burdwan - I', 'Burdwan - II', 'Galsi - I',
                    'Galsi - II', 'Jamalpur', 'Kalna - I', 'Kalna - II', 'Katwa - I', 'Katwa - II',
                    'Ketugram - I', 'Ketugram - II', 'Khandaghosh', 'Mangolkote', 'Manteswar', 'Memari - I',
                    'Memari - II', 'Purbasthali - I', 'Purbasthali - II', 'Raina - I', 'Raina - II'
                ],
                'Birbhum' => [
                    'Bolpur Sriniketan', 'Dubrajpur', 'Illambazar', 'Khoyrasole', 'Labpur', 'Mayureswar - I',
                    'Mayureswar - II', 'Murarai - I', 'Murarai - II', 'Nalhati - I', 'Nalhati - II',
                    'Nanoor', 'Rampurhat - I', 'Rampurhat - II', 'Sainthia', 'Suri - I', 'Suri - II',
                    'Rajnagar', 'Mohammad Bazar'
                ],
                'Dakshin Dinajpur' => [
                    'Balurghat', 'Banshihari', 'Gangarampur', 'Harirampur', 'Hili', 'Kumarganj',
                    'Kushmundi', 'Tapan'
                ],
                'Darjiling' => [
                    'Darjeeling Pulbazar', 'Jorebunglow Sukiapokhri', 'Kurseong', 'Matigara', 'Mirik',
                    'Naxalbari', 'Phansidewa', 'Rangli Rangliot', 'Siliguri'
                ],
                'Hugli' => [
                    'Arambag', 'Balagarh', 'Chanditala - I', 'Chanditala - II', 'Chinsurah Magra',
                    'Dhaniakhali', 'Goghat - I', 'Goghat - II', 'Haripal', 'Jangipara', 'Khanakul - I',
                    'Khanakul - II', 'Polba - Dadpur', 'Pursura', 'Serampur Uttarpara', 'Singur',
                    'Tarakeswar'
                ],
                'Haora' => [
                    'Amta - I', 'Amta - II', 'Bagnan - I', 'Bagnan - II', 'Bally Jagachha', 'Domjur',
                    'Jagatballavpur', 'Panchla', 'Sankrail', 'Shyampur - I', 'Shyampur - II', 'Udaynarayanpur',
                    'Uluberia - I', 'Uluberia - II'
                ],
                'Jalpaiguri' => [
                    'Dhupguri', 'Jalpaiguri', 'Mal', 'Matiali', 'Maynaguri', 'Nagrakata', 'Rajganj'
                ],
                'Koch Bihar' => [
                    'Cooch Behar - I', 'Cooch Behar - II', 'Dinhata - I', 'Dinhata - II', 'Haldibari',
                    'Mathabhanga - I', 'Mathabhanga - II', 'Mekliganj', 'Sitai', 'Sitalkuchi', 'Tufanganj - I',
                    'Tufanganj - II'
                ],
                'Maldah' => [
                    'Bamangola', 'Chanchal - I', 'Chanchal - II', 'English Bazar', 'Gazole', 'Habibpur',
                    'Harischandrapur - I', 'Harischandrapur - II', 'Kaliachak - I', 'Kaliachak - II',
                    'Kaliachak - III', 'Maldah (Old)', 'Manikchak', 'Ratua - I', 'Ratua - II'
                ],
                'Murshidabad' => [
                    'Beldanga - I', 'Beldanga - II', 'Berhampore', 'Bhagawangola - I', 'Bhagawangola - II',
                    'Bharatpur - I', 'Bharatpur - II', 'Burwan', 'Domkal', 'Farakka', 'Hariharpara',
                    'Jalangi', 'Kandi', 'Khargram', 'Lalgola', 'Murshidabad Jiaganj', 'Nabagram',
                    'Nawda', 'Raghunathganj - I', 'Raghunathganj - II', 'Raninagar - I', 'Raninagar - II',
                    'Sagardighi', 'Samsherganj', 'Suti - I', 'Suti - II'
                ],
                'Nadia' => [
                    'Chapra', 'Haringhata', 'Kaliganj', 'Karimpur - I', 'Karimpur - II', 'Krishnaganj',
                    'Krishnagar - I', 'Krishnagar - II', 'Nabadwip', 'Nakashipara', 'Ranaghat - I',
                    'Ranaghat - II', 'Santipur', 'Tehatta - I', 'Tehatta - II'
                ],
                'North 24 Parganas' => [
                    'Amdanga', 'Baduria', 'Bagda', 'Barasat - I', 'Barasat - II', 'Barrackpur - I',
                    'Barrackpur - II', 'Basirhat - I', 'Basirhat - II', 'Bongaon', 'Deganga', 'Gaidighata',
                    'Habra - I', 'Habra - II', 'Haroa', 'Hasnabad', 'Hingalganj', 'Minakhan', 'Rajarhat',
                    'Sandeshkhali - I', 'Sandeshkhali - II', 'Swarupnagar'
                ],
                'Paschim Medinipur' => [
                    'Binpur - I', 'Binpur - II', 'Chandrakona - I', 'Chandrakona - II', 'Dantan - I',
                    'Dantan - II', 'Daspur - I', 'Daspur - II', 'Debra', 'Garbeta - I', 'Garbeta - II',
                    'Garbeta - III', 'Ghatal', 'Gopiballavpur - I', 'Gopiballavpur - II', 'Jamboni',
                    'Jhargram', 'Keshiary', 'Keshpur', 'Kharagpur - I', 'Kharagpur - II', 'Midnapore',
                    'Mohanpur', 'Narayangarh', 'Nayagram', 'Pingla', 'Sabang', 'Salbani', 'Sankrail'
                ],
                'Purba Medinipur' => [
                    'Bhagawanpur - I', 'Bhagawanpur - II', 'Chandipur', 'Contai - I', 'Contai - III',
                    'Deshopran', 'Egra - I', 'Egra - II', 'Haldia', 'Khejuri - I', 'Khejuri - II',
                    'Kolaghat', 'Mahisadal', 'Moyna', 'Nanda Kumar', 'Nandigram - I', 'Nandigram - II',
                    'Panskura', 'Potashpur - I', 'Potashpur - II', 'Ramnagar - I', 'Ramnagar - II',
                    'Sahid Matangini', 'Sutahata', 'Tamluk'
                ],
                'Puruliya' => [
                    'Arsha', 'Bagmundi', 'Balarampur', 'Barabazar', 'Bundwan', 'Hura', 'Jaipur',
                    'Jhalda - I', 'Jhalda - II', 'Kashipur', 'Manbazar - I', 'Manbazar - II', 'Neturia',
                    'Para', 'Puncha', 'Purulia - I', 'Purulia - II', 'Raghunathpur - I', 'Raghunathpur - II',
                    'Santuri'
                ],
                'South 24 Parganas' => [
                    'Baruipur', 'Basanti', 'Bhangar - I', 'Bhangar - II', 'Bishnupur - I', 'Bishnupur - II',
                    'Budge Budge - I', 'Budge Budge - II', 'Canning - I', 'Canning - II', 'Diamond Harbour - I',
                    'Diamond Harbour - II', 'Falta', 'Gosaba', 'Jaynagar - I', 'Jaynagar - II', 'Kakdwip',
                    'Kulpi', 'Kultali', 'Magrahat - I', 'Magrahat - II', 'Mandirbazar', 'Mathurapur - I',
                    'Mathurapur - II', 'Namkhana', 'Patharpratima', 'Sagar', 'Sonarpur', 'Thakurpukur Mahestola'
                ],
                'Uttar Dinajpur' => [
                    'Chopra', 'Goalpokhar - I', 'Goalpokhar - II', 'Hemtabad', 'Islampur', 'Itahar',
                    'Kaliaganj', 'Karandighi', 'Raiganj'
                ],
                'Alipurduar' => [
                    'Alipurduar', 'Falakata', 'Kalchini', 'Kumargram', 'Madarihat'
                ],
                'Jhargram' => [
                    'Jhargram', 'Jamboni', 'Binpur I', 'Binpur II', 'Gopiballavpur I', 'Gopiballavpur II',
                    'Sankrail', 'Nayagram'
                ],
                'Kolkata' => [
                    'Kolkata'
                ],
                'Kalimpong' => [
                    'Kalimpong', 'Kalimpong I', 'Kalimpong II', 'Gorubathan'
                ]
            ]
        ];

        foreach ($data as $stateName => $districts) {
            // Fetch state_id by name
            $state = DB::table('states')->where('name', $stateName)->first();
            if (!$state) {
                // Handle if state not found (optional: skip or log)
                continue;
            }
            $stateId = $state->id;

            foreach ($districts as $districtName => $talukas) {
                // Fetch district_id by name and state_id
                $district = DB::table('districts')
                    ->where('name', $districtName)
                    ->where('state_id', $stateId)
                    ->first();
                if (!$district) {
                    // Handle if district not found (optional: skip or log)
                    continue;
                }
                $districtId = $district->id;

                // Prepare bulk insert for talukas
                $insertData = array_map(function ($taluka) use ($stateId, $districtId) {
                    return [
                        'country_id' => 1,
                        'state_id' => $stateId,
                        'district_id' => $districtId,
                        'name' => $taluka,
                    ];
                }, $talukas);

                // Insert into tehsils table
                DB::table('tehsils')->insert($insertData);
            }
        }
    }
}