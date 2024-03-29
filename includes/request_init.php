<?php
$TEMP = array();
$TEMP['#site_url'] = $site_url;
$TEMP['#settings'] = Specific::Settings();
$TEMP['#languages'] = Specific::Languages();
$TEMP['#loggedin'] = Specific::Logged();
if ($TEMP['#loggedin'] === true) {
    $TEMP['#user'] = Specific::Data(null, 3);
}
$language = Specific::Filter($_GET['language']);
if(empty($_GET['language'])){
	$language = $_SESSION['language'];
}
$TEMP['#language'] = $_SESSION['language'] = Specific::Language($language);
$TEMP['#word'] = Specific::Words($TEMP['#language']);
$TEMP['#token_session'] = Specific::TokenSession();
if (isset($_SESSION['_LOGIN_TOKEN'])) {
    if (empty($_COOKIE['_LOGIN_TOKEN'])) {
        setcookie("_LOGIN_TOKEN", $_SESSION['_LOGIN_TOKEN'], time() + 315360000, "/");
    }
}
if (empty($TEMP['#word'])) {
    $TEMP['#word'] = Specific::Words();
}
$TEMP['#rules'] = array(
    'NNEVF',
    'NMTC',
    'NMHEAP',
    'NMDPH',
    'NMAT',
    'NMANT',
    'TMVEA',
    'NVP',
    'NVS',
    'NM',
    'NMV',
    'NMCNT',
    'NMCT',
    'CERS'
);
$TEMP['#rulen'] = array(
    'NNEVF',
    'NMTC',
    'NMDPH',
    'NMAT',
    'NMANT',
    'NMV',
    'NMCNT',
    'NMCT'
);
$TEMP['#nmtc'] = 1.5;
$TEMP['#nm'] = 5.0;
$TEMP['#nmcnt'] = 3.5;
$TEMP['#nmct'] = 3.0;
$TEMP['#cers'] = 4;
//Para aprobar el semestre
$TEMP['#nmcs'] = 3.5;
$rules = $dba->query('SELECT rules FROM rule WHERE status = "enabled"')->fetchArray();
if(!empty($rules)){
    if (preg_match_all('/{\#(.+?)->(.+?)}/i', htmlspecialchars_decode($rules), $rls)) {
        for ($i=0; $i < count($rls[0]); $i++) { 
            if(in_array($rls[1][$i], $TEMP['#rules'])){
                $TEMP["#".strtolower($rls[1][$i])] = $rls[2][$i];
            }
        }
    }
}
$TEMP['#plans'] = $dba->query('SELECT * FROM plan')->fetchAll();
$TEMP['#programs'] = $dba->query('SELECT * FROM program')->fetchAll();
$TEMP['#faculties'] = $dba->query('SELECT * FROM faculty')->fetchAll();
$TEMP['#admin'] = Specific::Admin();
$TEMP['#academic'] = Specific::Academic();
$TEMP['#teacher'] = Specific::Teacher();
$TEMP['#student'] = Specific::Student();
$TEMP['#notifycon'] = array(
  'enroll' => array(
    'title' => $TEMP['#word']['they_enrolled_one_courses'],
    'text' => "{$TEMP['#word']['just_enrolled_in_your_course']}",
    'url' => Specific::Url('courses')
  ), 'note' => array(
    'title' => $TEMP['#word']['they_uploaded_your_note_in'],
    'text' => "{$TEMP['#word']['just_uploaded_your_grade_course']}",
    'url' => Specific::Url('notes')
  ),
  'authorize' => array(
    'title' => $TEMP['#word']['were_asked_permission_upload_grade_in'],
    'text' => "{$TEMP['#word']['just_applied_authorization_in_the_course']}",
    'url' => Specific::Url('more?page=authorizations')
  ),
  'auth_authorized' => array(
    'title' => $TEMP['#word']['were_authorized_upload_note_in'],
    'text' => "{$TEMP['#word']['you_authorized_upload_grades_course_of']}",
    'url' => Specific::Url('authorizations')
  ),
  'auth_denied' => array(
    'title' => $TEMP['#word']['they_responded_request_in'],
    'text' => "{$TEMP['#word']['your_authorization_denied_upload_grades_course_denied']}",
    'url' => Specific::Url('authorizations')
  ),
  'req_qualification' => array(
    'title' => $TEMP['#word']['they_asked_authorization_in'],
    'text' => "{$TEMP['#word']['you_have_just_applied_course']}",
    'url' => Specific::Url('more?page=qualifications')
  ),
  'qualification_note' => array(
    'title' => $TEMP['#word']['they_uploaded_note_qualification_in'],
    'text' => "{$TEMP['#word']['just_uploaded_note_qualification_course']}",
    'url' => Specific::Url('notes')
  ),
  'quate_accepted' => array(
    'title' => $TEMP['#word']['were_authorized_upload_note_in'],
    'text' => "{$TEMP['#word']['authorized_upload_qualification_note_course']}",
    'url' => Specific::Url('notes')
  ),
  'quate_rejected' => array(
    'title' => $TEMP['#word']['they_canceled_authorization_in'],
    'text' => "{$TEMP['#word']['have_withdrawn_authorization_upload_qualification_note']}",
    'url' => Specific::Url('notes')
  ),
  'quast_accepted' => array(
    'title' => $TEMP['#word']['they_answered_request_authorization_in'],
    'text' => "{$TEMP['#word']['accepted_request_authorization_course']}",
    'url' => Specific::Url('notes')
  ),
  'quast_rejected' => array(
    'title' => $TEMP['#word']['they_answered_request_authorization_in'],
    'text' => "{$TEMP['#word']['rejected_request_qualify_course']}",
    'url' => Specific::Url('notes')
  )
);
$TEMP['#provinces'] = array(
	'antioquia' => 'Antioquía',
	'amazonas' => 'Amazonas',
	'arauca' => 'Arauca',
	'atlantico' => 'Atlántico',
	'bolivar' => 'Bolívar',
	'boyaca' => 'Boyacá',
	'caldas' => 'Caldas',
	'caqueta' => 'Caquetá',
	'casanare' => 'Casanare',
	'cauca' => 'Cauca',
	'cesar' => 'Cesar',
	'choco' => 'Chocó',
	'cordoba' => 'Córdoba',
	'cundinamarca' => 'Cundinamarca',
	'guainia' => 'Guainía',
	'guaviare' => 'Guaviare',
	'huila' => 'Huila',
	'guajira' => 'La Guajira',
	'magdalena' => 'Magdalena',
	'meta' => 'Meta',
	'narino' => 'Nariño',
	'norteSantander' => 'Norte de Santander',
	'putumayo' => 'Putumayo',
	'quindio' => 'Quindío',
	'risaralda' => 'Risaralda',
	'sanAndres' => 'San Andrés y Providencia',
	'santander' => 'Santander',
	'sucre' => 'Sucre',
	'tolima' => 'Tolima',
	'cauca' => 'Valle del Cauca',
	'vaupes' => 'Vaupés',
	'vichada' => 'Vichada'
);
$TEMP['#municipalities'] = array(
  "antioquia" => array(
    '1' => 'Abejorral',
    '3' => 'Abriaquí',
    '23' => 'Alejandría',
    '33' => 'Amagá',
    '34' => 'Amalfi',
    '39' => 'Andes',
    '40' => 'Angelópolis',
    '41' => 'Angostura',
    '43' => 'Anorí',
    '46' => 'Anzá',
    '48' => 'Apartadó',
    '60' => 'Arboletes',
    '63' => 'Argelia',
    '68' => 'Armenia',
    '84' => 'Barbosa',
    '99' => 'Bello',
    '100' => 'Belmira',
    '103' => 'Betania',
    '105' => 'Betulia',
    '118' => 'Briceño',
    '130' => 'Buriticá',
    '136' => 'Cáceres',
    '140' => 'Caicedo',
    '149' => 'Caldas',
    '156' => 'Campamento',
    '164' => 'Cañasgordas',
    '168' => 'Caracolí',
    '169' => 'Caramanta',
    '171' => 'Carepa',
    '175' => 'Carolina',
    '182' => 'Caucasia',
    '197' => 'Chigorodó',
    '225' => 'Cisneros',
    '226' => 'Ciudad Bolívar',
    '228' => 'Cocorná',
    '236' => 'Concepción',
    '238' => 'Concordia',
    '246' => 'Copacabana',
    '277' => 'Dabeiba',
    '282' => 'Donmatías',
    '286' => 'Ebéjico',
    '288' => 'El Bagre',
    '297' => 'El Carmen De Viboral',
    '326' => 'El Santuario',
    '335' => 'Entrerríos',
    '336' => 'Envigado',
    '355' => 'Fredonia',
    '357' => 'Frontino',
    '380' => 'Giraldo',
    '382' => 'Girardota',
    '384' => 'Gómez Plata',
    '387' => 'Granada',
    '397' => 'Guadalupe',
    '409' => 'Guarne',
    '411' => 'Guatapé',
    '428' => 'Heliconia',
    '431' => 'Hispania',
    '444' => 'Itagüí',
    '445' => 'Ituango',
    '449' => 'Jardín',
    '451' => 'Jericó',
    '464' => 'La Ceja',
    '471' => 'La Estrella',
    '487' => 'La Pintada',
    '495' => 'La Unión',
    '516' => 'Liborina',
    '530' => 'Maceo',
    '552' => 'Marinilla',
    '559' => 'Medellín',
    '582' => 'Montebello',
    '598' => 'Murindó',
    '599' => 'Mutatá',
    '602' => 'Nariño',
    '607' => 'Nechí',
    '608' => 'Necoclí',
    '628' => 'Olaya',
    '677' => 'Peñol',
    '678' => 'Peque',
    '710' => 'Pueblorrico',
    '717' => 'Puerto Berrío',
    '731' => 'Puerto Nare',
    '741' => 'Puerto Triunfo',
    '760' => 'Remedios',
    '765' => 'Retiro',
    '775' => 'Rionegro',
    '789' => 'Sabanalarga',
    '793' => 'Sabaneta',
    '804' => 'Salgar',
    '813' => 'San Andrés De Cuerquía',
    '825' => 'San Carlos',
    '836' => 'San Francisco',
    '842' => 'San Jerónimo',
    '845' => 'San José De La Montaña',
    '855' => 'San Juan De Urabá',
    '860' => 'San Luis',
    '880' => 'San Pedro De Los Milagros',
    '881' => 'San Pedro De Urabá',
    '883' => 'San Rafael',
    '884' => 'San Roque',
    '890' => 'San Vicente Ferrer',
    '894' => 'Santa Bárbara',
    '899' => 'Santa Fé De Antioquia',
    '909' => 'Santa Rosa De Osos',
    '920' => 'Santo Domingo',
    '929' => 'Segovia',
    '954' => 'Sonsón',
    '955' => 'Sopetrán',
    '986' => 'Támesis',
    '991' => 'Tarazá',
    '993' => 'Tarso',
    '1017' => 'Titiribí',
    '1022' => 'Toledo',
    '1042' => 'Turbo',
    '1053' => 'Uramita',
    '1056' => 'Urrao',
    '1060' => 'Valdivia',
    '1066' => 'Valparaíso',
    '1068' => 'Vegachí',
    '1071' => 'Venecia',
    '1079' => 'Vigía Del Fuerte',
    '1106' => 'Yalí',
    '1107' => 'Yarumal',
    '1109' => 'Yolombó',
    '1110' => 'Yondó',
    '1117' => 'Zaragoza',

  ),
  "amazonas" => array(
    '307' => 'El Encanto',
    '466' => 'La Chorrera',
    '485' => 'La Pedrera',
    '504' => 'La Victoria',
    '514' => 'Leticia',
    '571' => 'Mirití - Paraná',
    '714' => 'Puerto Alegría',
    '715' => 'Puerto Arica',
    '732' => 'Puerto Nariño',
    '739' => 'Puerto Santander',
    '990' => 'Tarapacá',
  ),
  "arauca" => array(
    '55' => 'Arauca',
    '56' => 'Arauquita',
    '260' => 'Cravo Norte',
    '352' => 'Fortul',
    '736' => 'Puerto Rondón',
    '924' => 'Saravena',
    '985' => 'Tame',

  ),
  "atlantico" => array(
    '81' => 'Baranoa',
    '92' => 'Barranquilla',
    '157' => 'Campo De La Cruz',
    '161' => 'Candelaria',
    '369' => 'Galapa',
    '456' => 'Juan De Acosta',
    '527' => 'Luruaco',
    '539' => 'Malambo',
    '541' => 'Manatí',
    '654' => 'Palmar De Varela',
    '689' => 'Piojó',
    '698' => 'Polonuevo',
    '699' => 'Ponedera',
    '721' => 'Puerto Colombia',
    '762' => 'Repelón',
    '788' => 'Sabanagrande',
    '790' => 'Sabanalarga',
    '902' => 'Santa Lucía',
    '921' => 'Santo Tomás',
    '951' => 'Soledad',
    '963' => 'Suan',
    '1034' => 'Tubará',
    '1058' => 'Usiacurí',

  ),


  "bogota" => array(
    '110' => 'Bogotá, D.C.',
  ),

  "bolivar" => array(
    '7' => 'Achí',
    '31' => 'Altos Del Rosario',
    '62' => 'Arenal',
    '67' => 'Arjona',
    '71' => 'Arroyohondo',
    '90' => 'Barranco De Loba',
    '146' => 'Calamar',
    '163' => 'Cantagallo',
    '176' => 'Cartagena De Indias',
    '219' => 'Cicuco',
    '227' => 'Clemencia',
    '248' => 'Córdoba',
    '295' => 'El Carmen De Bolívar',
    '310' => 'El Guamo',
    '316' => 'El Peñón',
    '424' => 'Hatillo De Loba',
    '533' => 'Magangué',
    '535' => 'Mahates',
    '550' => 'Margarita',
    '551' => 'María La Baja',
    '578' => 'Mompós',
    '583' => 'Montecristo',
    '589' => 'Morales',
    '617' => 'Norosí',
    '688' => 'Pinillos',
    '759' => 'Regidor',
    '771' => 'Río Viejo',
    '830' => 'San Cristóbal',
    '833' => 'San Estanislao',
    '835' => 'San Fernando',
    '840' => 'San Jacinto',
    '841' => 'San Jacinto Del Cauca',
    '857' => 'San Juan Nepomuceno',
    '868' => 'San Martín De Loba',
    '874' => 'San Pablo',
    '898' => 'Santa Catalina',
    '906' => 'Santa Rosa',
    '911' => 'Santa Rosa Del Sur',
    '940' => 'Simití',
    '956' => 'Soplaviento',
    '982' => 'Talaigua Nuevo',
    '1016' => 'Tiquisio',
    '1040' => 'Turbaco',
    '1041' => 'Turbaná',
    '1090' => 'Villanueva',
    '1114' => 'Zambrano',

  ),

  "boyaca" => [
    '27' => 'Almeida',
    '51' => 'Aquitania',
    '61' => 'Arcabuco',
    '95' => 'Belén',
    '102' => 'Berbeo',
    '104' => 'Betéitiva',
    '108' => 'Boavita',
    '117' => 'Boyacá',
    '119' => 'Briceño',
    '123' => 'Buenavista',
    '131' => 'Busbanzá',
    '150' => 'Caldas',
    '159' => 'Campohermoso',
    '185' => 'Cerinza',
    '202' => 'Chinavita',
    '207' => 'Chiquinquirá',
    '208' => 'Chíquiza',
    '210' => 'Chiscas',
    '211' => 'Chita',
    '213' => 'Chitaraque',
    '214' => 'Chivatá',
    '216' => 'Chivor',
    '222' => 'Ciénega',
    '235' => 'Cómbita',
    '247' => 'Coper',
    '254' => 'Corrales',
    '257' => 'Covarachía',
    '262' => 'Cubará',
    '264' => 'Cucaita',
    '268' => 'Cuítiva',
    '284' => 'Duitama',
    '301' => 'El Cocuy',
    '308' => 'El Espino',
    '342' => 'Firavitoba',
    '346' => 'Floresta',
    '366' => 'Gachantivá',
    '374' => 'Gámeza',
    '375' => 'Garagoa',
    '391' => 'Guacamayas',
    '414' => 'Guateque',
    '419' => 'Guayatá',
    '421' => 'Güicán De La Sierra',
    '446' => 'Iza',
    '450' => 'Jenesano',
    '452' => 'Jericó',
    '463' => 'La Capilla',
    '499' => 'La Uvita',
    '502' => 'La Victoria',
    '507' => 'Labranzagrande',
    '528' => 'Macanal',
    '553' => 'Maripí',
    '568' => 'Miraflores',
    '579' => 'Mongua',
    '580' => 'Monguí',
    '581' => 'Moniquirá',
    '596' => 'Motavita',
    '601' => 'Muzo',
    '614' => 'Nobsa',
    '620' => 'Nuevo Colón',
    '627' => 'Oicatá',
    '636' => 'Otanche',
    '638' => 'Pachavita',
    '643' => 'Páez',
    '648' => 'Paipa',
    '649' => 'Pajarito',
    '663' => 'Panqueba',
    '670' => 'Pauna',
    '671' => 'Paya',
    '673' => 'Paz De Río',
    '680' => 'Pesca',
    '690' => 'Pisba',
    '718' => 'Puerto Boyacá',
    '753' => 'Quípama',
    '756' => 'Ramiriquí',
    '757' => 'Ráquira',
    '784' => 'Rondón',
    '794' => 'Saboyá',
    '796' => 'Sáchica',
    '805' => 'Samacá',
    '832' => 'San Eduardo',
    '847' => 'San José De Pare',
    '862' => 'San Luis De Gaceno',
    '869' => 'San Mateo',
    '872' => 'San Miguel De Sema',
    '876' => 'San Pablo De Borbur',
    '903' => 'Santa María',
    '910' => 'Santa Rosa De Viterbo',
    '913' => 'Santa Sofía',
    '915' => 'Santana',
    '927' => 'Sativanorte',
    '928' => 'Sativasur',
    '932' => 'Siachoque',
    '945' => 'Soatá',
    '946' => 'Socha',
    '948' => 'Socotá',
    '949' => 'Sogamoso',
    '953' => 'Somondoco',
    '958' => 'Sora',
    '959' => 'Soracá',
    '960' => 'Sotaquirá',
    '976' => 'Susacón',
    '977' => 'Sutamarchán',
    '979' => 'Sutatenza',
    '994' => 'Tasco',
    '1001' => 'Tenza',
    '1006' => 'Tibaná',
    '1007' => 'Tibasosa',
    '1014' => 'Tinjacá',
    '1015' => 'Tipacoque',
    '1018' => 'Toca',
    '1021' => 'Togüí',
    '1026' => 'Tópaga',
    '1030' => 'Tota',
    '1037' => 'Tunja',
    '1038' => 'Tununguá',
    '1043' => 'Turmequé',
    '1044' => 'Tuta',
    '1045' => 'Tutazá',
    '1049' => 'Úmbita',
    '1073' => 'Ventaquemada',
    '1082' => 'Villa De Leyva',
    '1100' => 'Viracachá',
    '1119' => 'Zetaquira',
  ],

  "caldas" => array(
    '12' => 'Aguadas',
    '44' => 'Anserma',
    '53' => 'Aranzazu',
    '94' => 'Belalcázar',
    '203' => 'Chinchiná',
    '340' => 'Filadelfia',
    '469' => 'La Dorada',
    '479' => 'La Merced',
    '545' => 'Manizales',
    '547' => 'Manzanares',
    '554' => 'Marmato',
    '555' => 'Marquetalia',
    '557' => 'Marulanda',
    '609' => 'Neira',
    '616' => 'Norcasia',
    '641' => 'Pácora',
    '651' => 'Palestina',
    '676' => 'Pensilvania',
    '777' => 'Riosucio',
    '779' => 'Risaralda',
    '799' => 'Salamina',
    '806' => 'Samaná',
    '844' => 'San José',
    '973' => 'Supía',
    '1078' => 'Victoria',
    '1089' => 'Villamaría',
    '1102' => 'Viterbo',
  ),

  "casanare" => array(
    '13' => 'Aguazul',
    '192' => 'Chámeza',
    '426' => 'Hato Corozal',
    '491' => 'La Salina',
    '544' => 'Maní',
    '587' => 'Monterrey',
    '621' => 'Nunchía',
    '633' => 'Orocué',
    '672' => 'Paz De Ariporo',
    '701' => 'Pore',
    '758' => 'Recetor',
    '791' => 'Sabanalarga',
    '795' => 'Sácama',
    '863' => 'San Luis De Palenque',
    '984' => 'Támara',
    '995' => 'Tauramena',
    '1032' => 'Trinidad',
    '1093' => 'Villanueva',
    '1111' => 'Yopal',

  ),
  "cauca" => [
    '26' => 'Almaguer',
    '64' => 'Argelia',
    '79' => 'Balboa',
    '113' => 'Bolívar',
    '127' => 'Buenos Aires',
    '144' => 'Cajibío',
    '151' => 'Caldono',
    '155' => 'Caloto',
    '251' => 'Corinto',
    '328' => 'El Tambo',
    '345' => 'Florencia',
    '393' => 'Guachené',
    '406' => 'Guapí',
    '439' => 'Inzá',
    '447' => 'Jambaló',
    '492' => 'La Sierra',
    '500' => 'La Vega',
    '519' => 'López De Micay',
    '565' => 'Mercaderes',
    '570' => 'Miranda',
    '590' => 'Morales',
    '642' => 'Padilla',
    '644' => 'Páez',
    '669' => 'Patía',
    '681' => 'Piamonte',
    '684' => 'Piendamó',
    '700' => 'Popayán',
    '740' => 'Puerto Tejada',
    '745' => 'Puracé',
    '785' => 'Rosas',
    '885' => 'San Sebastián',
    '907' => 'Santa Rosa',
    '916' => 'Santander De Quilichao',
    '937' => 'Silvia',
    '961' => 'Sotara',
    '964' => 'Suárez',
    '968' => 'Sucre',
    '1012' => 'Timbío',
    '1013' => 'Timbiquí',
    '1028' => 'Toribío',
    '1031' => 'Totoró',
    '1085' => 'Villa Rica',

  ],
  "caqueta" => array(
    '18' => 'Albania',
    '97' => 'Belén De Los Andaquíes',
    '177' => 'Cartagena Del Chairá',
    '274' => 'Curillo',
    '304' => 'El Doncello',
    '314' => 'El Paujíl',
    '344' => 'Florencia',
    '481' => 'La Montañita',
    '567' => 'Milán',
    '591' => 'Morelia',
    '734' => 'Puerto Rico',
    '849' => 'San José Del Fragua',
    '889' => 'San Vicente Del Caguán',
    '950' => 'Solano',
    '952' => 'Solita',
    '1067' => 'Valparaíso',
  ),


  "choco" => array(
    '5' => 'Acandí',
    '30' => 'Alto Baudó',
    '74' => 'Atrato',
    '76' => 'Bagadó',
    '77' => 'Bahía Solano',
    '78' => 'Bajo Baudó',
    '112' => 'Bojayá',
    '174' => 'Carmen Del Darién',
    '188' => 'Cértegui',
    '240' => 'Condoto',
    '292' => 'El Cantón Del San Pablo',
    '294' => 'El Carmen De Atrato',
    '311' => 'El Litoral Del San Juan',
    '443' => 'Istmina',
    '458' => 'Juradó',
    '518' => 'Lloró',
    '561' => 'Medio Atrato',
    '562' => 'Medio Baudó',
    '563' => 'Medio San Juan',
    '618' => 'Nóvita',
    '622' => 'Nuquí',
    '750' => 'Quibdó',
    '769' => 'Río Iró',
    '770' => 'Río Quito',
    '778' => 'Riosucio',
    '851' => 'San José Del Palmar',
    '942' => 'Sipí',
    '981' => 'Tadó',
    '1051' => 'Unguía',
    '1052' => 'Unión Panamericana',

  ),
  "cesar" => array(
    '10' => 'Aguachica',
    '14' => 'Agustín Codazzi',
    '72' => 'Astrea',
    '93' => 'Becerril',
    '116' => 'Bosconia',
    '200' => 'Chimichagua',
    '209' => 'Chiriguaná',
    '276' => 'Curumaní',
    '303' => 'El Copey',
    '313' => 'El Paso',
    '372' => 'Gamarra',
    '385' => 'González',
    '473' => 'La Gloria',
    '475' => 'La Jagua De Ibirico',
    '483' => 'La Paz',
    '543' => 'Manaure Balcón Del Cesar',
    '646' => 'Pailitas',
    '675' => 'Pelaya',
    '707' => 'Pueblo Bello',
    '768' => 'Río De Oro',
    '810' => 'San Alberto',
    '831' => 'San Diego',
    '866' => 'San Martín',
    '983' => 'Tamalameque',
    '1065' => 'Valledupar',

  ),


  "cordoba" => array(
    '75' => 'Ayapel',
    '124' => 'Buenavista',
    '160' => 'Canalete',
    '184' => 'Cereté',
    '199' => 'Chimá',
    '204' => 'Chinú',
    '221' => 'Ciénaga De Oro',
    '256' => 'Cotorra',
    '459' => 'La Apartada',
    '520' => 'Lorica',
    '522' => 'Los Córdobas',
    '577' => 'Momil',
    '584' => 'Montelíbano',
    '586' => 'Montería',
    '588' => 'Moñitos',
    '695' => 'Planeta Rica',
    '708' => 'Pueblo Nuevo',
    '724' => 'Puerto Escondido',
    '728' => 'Puerto Libertador',
    '747' => 'Purísima De La Concepción',
    '797' => 'Sahagún',
    '814' => 'San Andrés De Sotavento',
    '816' => 'San Antero',
    '823' => 'San Bernardo Del Viento',
    '826' => 'San Carlos',
    '848' => 'San José De Uré',
    '882' => 'San Pelayo',
    '1010' => 'Tierralta',
    '1035' => 'Tuchín',
    '1061' => 'Valencia',

  ),

  "cundinamarca" => array(
    '9' => 'Agua De Dios',
    '16' => 'Albán',
    '36' => 'Anapoima',
    '42' => 'Anolaima',
    '50' => 'Apulo',
    '57' => 'Arbeláez',
    '101' => 'Beltrán',
    '107' => 'Bituima',
    '111' => 'Bojacá',
    '132' => 'Cabrera',
    '137' => 'Cachipay',
    '145' => 'Cajicá',
    '165' => 'Caparrapí',
    '167' => 'Cáqueza',
    '173' => 'Carmen De Carupa',
    '190' => 'Chaguaní',
    '196' => 'Chía',
    '205' => 'Chipaque',
    '217' => 'Choachí',
    '218' => 'Chocontá',
    '230' => 'Cogua',
    '255' => 'Cota',
    '265' => 'Cucunubá',
    '302' => 'El Colegio',
    '317' => 'El Peñón',
    '324' => 'El Rosal',
    '338' => 'Facatativá',
    '350' => 'Fómeque',
    '353' => 'Fosca',
    '361' => 'Funza',
    '362' => 'Fúquene',
    '363' => 'Fusagasugá',
    '364' => 'Gachalá',
    '365' => 'Gachancipá',
    '367' => 'Gachetá',
    '371' => 'Gama',
    '381' => 'Girardot',
    '388' => 'Granada',
    '394' => 'Guachetá',
    '400' => 'Guaduas',
    '410' => 'Guasca',
    '412' => 'Guataquí',
    '413' => 'Guatavita',
    '417' => 'Guayabal De Síquima',
    '418' => 'Guayabetal',
    '422' => 'Gutiérrez',
    '453' => 'Jerusalén',
    '457' => 'Junín',
    '462' => 'La Calera',
    '480' => 'La Mesa',
    '482' => 'La Palma',
    '486' => 'La Peña',
    '501' => 'La Vega',
    '512' => 'Lenguazaque',
    '531' => 'Machetá',
    '532' => 'Madrid',
    '546' => 'Manta',
    '560' => 'Medina',
    '594' => 'Mosquera',
    '603' => 'Nariño',
    '611' => 'Nemocón',
    '612' => 'Nilo',
    '613' => 'Nimaima',
    '615' => 'Nocaima',
    '639' => 'Pacho',
    '647' => 'Paime',
    '662' => 'Pandi',
    '666' => 'Paratebueno',
    '667' => 'Pasca',
    '737' => 'Puerto Salgar',
    '743' => 'Pulí',
    '748' => 'Quebradanegra',
    '749' => 'Quetame',
    '754' => 'Quipile',
    '766' => 'Ricaurte',
    '818' => 'San Antonio Del Tequendama',
    '821' => 'San Bernardo',
    '828' => 'San Cayetano',
    '837' => 'San Francisco',
    '854' => 'San Juan De Rioseco',
    '926' => 'Sasaima',
    '930' => 'Sesquilé',
    '933' => 'Sibaté',
    '936' => 'Silvania',
    '939' => 'Simijaca',
    '944' => 'Soacha',
    '957' => 'Sopó',
    '967' => 'Subachoque',
    '971' => 'Suesca',
    '972' => 'Supatá',
    '975' => 'Susa',
    '978' => 'Sutatausa',
    '980' => 'Tabio',
    '996' => 'Tausa',
    '998' => 'Tena',
    '1000' => 'Tenjo',
    '1005' => 'Tibacuy',
    '1008' => 'Tibirita',
    '1019' => 'Tocaima',
    '1020' => 'Tocancipá',
    '1027' => 'Topaipí',
    '1046' => 'Ubalá',
    '1047' => 'Ubaque',
    '1050' => 'Une',
    '1059' => 'Útica',
    '1072' => 'Venecia',
    '1074' => 'Vergara',
    '1077' => 'Vianí',
    '1083' => 'Villa De San Diego De Ubaté',
    '1087' => 'Villagómez',
    '1094' => 'Villapinzón',
    '1098' => 'Villeta',
    '1099' => 'Viotá',
    '1103' => 'Yacopí',
    '1120' => 'Zipacón',
    '1121' => 'Zipaquirá',

  ),




  "guajira" => array(
    '19' => 'Albania',
    '89' => 'Barrancas',
    '279' => 'Dibulla',
    '280' => 'Distracción',
    '312' => 'El Molino',
    '351' => 'Fonseca',
    '427' => 'Hatonuevo',
    '476' => 'La Jagua Del Pilar',
    '536' => 'Maicao',
    '542' => 'Manaure',
    '774' => 'Riohacha',
    '856' => 'San Juan Del Cesar',
    '1055' => 'Uribia',
    '1057' => 'Urumita',
    '1091' => 'Villanueva',

  ),
  "guania" => array(
    '91' => 'Barranco Minas',
    '135' => 'Cacahual',
    '438' => 'Inírida',
    '474' => 'La Guadalupe',
    '549' => 'Mapiripana',
    '592' => 'Morichal',
    '661' => 'Pana Pana',
    '722' => 'Puerto Colombia',
    '834' => 'San Felipe',

  ),
  "guaviare" => [
    '147' => 'Calamar',
    '322' => 'El Retorno',
    '569' => 'Miraflores',
    '850' => 'San José Del Guaviare',

  ],

  "huila" => array(
    '6' => 'Acevedo',
    '8' => 'Agrado',
    '15' => 'Aipe',
    '25' => 'Algeciras',
    '29' => 'Altamira',
    '82' => 'Baraya',
    '158' => 'Campoalegre',
    '231' => 'Colombia',
    '332' => 'Elías',
    '376' => 'Garzón',
    '378' => 'Gigante',
    '398' => 'Guadalupe',
    '432' => 'Hobo',
    '441' => 'Íquira',
    '442' => 'Isnos',
    '460' => 'La Argentina',
    '488' => 'La Plata',
    '605' => 'Nátaga',
    '610' => 'Neiva',
    '631' => 'Oporapa',
    '645' => 'Paicol',
    '650' => 'Palermo',
    '652' => 'Palestina',
    '691' => 'Pital',
    '692' => 'Pitalito',
    '780' => 'Rivera',
    '798' => 'Saladoblanco',
    '809' => 'San Agustín',
    '904' => 'Santa María',
    '966' => 'Suaza',
    '992' => 'Tarqui',
    '997' => 'Tello',
    '1003' => 'Teruel',
    '1004' => 'Tesalia',
    '1011' => 'Timaná',
    '1097' => 'Villavieja',
    '1105' => 'Yaguará',

  ),


  "magdalena" => [
    '24' => 'Algarrobo',
    '52' => 'Aracataca',
    '66' => 'Ariguaní',
    '187' => 'Cerro De San Antonio',
    '215' => 'Chivolo',
    '220' => 'Ciénaga',
    '239' => 'Concordia',
    '289' => 'El Banco',
    '319' => 'El Piñón',
    '321' => 'El Retén',
    '359' => 'Fundación',
    '403' => 'Guamal',
    '619' => 'Nueva Granada',
    '674' => 'Pedraza',
    '686' => 'Pijiño Del Carmen',
    '693' => 'Pivijay',
    '696' => 'Plato',
    '711' => 'Puebloviejo',
    '761' => 'Remolino',
    '792' => 'Sabanas De San Ángel',
    '800' => 'Salamina',
    '886' => 'San Sebastián De Buenavista',
    '891' => 'San Zenón',
    '893' => 'Santa Ana',
    '897' => 'Santa Bárbara De Pinto',
    '905' => 'Santa Marta',
    '943' => 'Sitionuevo',
    '999' => 'Tenerife',
    '1116' => 'Zapayán',
    '1122' => 'Zona Bananera',

  ],
  "meta" => array(
    '4' => 'Acacías',
    '87' => 'Barranca De Upía',
    '134' => 'Cabuyaro',
    '181' => 'Castilla La Nueva',
    '263' => 'Cubarral',
    '269' => 'Cumaral',
    '291' => 'El Calvario',
    '298' => 'El Castillo',
    '305' => 'El Dorado',
    '358' => 'Fuente De Oro',
    '389' => 'Granada',
    '404' => 'Guamal',
    '478' => 'La Macarena',
    '511' => 'Lejanías',
    '548' => 'Mapiripán',
    '566' => 'Mesetas',
    '723' => 'Puerto Concordia',
    '725' => 'Puerto Gaitán',
    '729' => 'Puerto Lleras',
    '730' => 'Puerto López',
    '735' => 'Puerto Rico',
    '763' => 'Restrepo',
    '827' => 'San Carlos De Guaroa',
    '852' => 'San Juan De Arama',
    '858' => 'San Juanito',
    '867' => 'San Martín',
    '1054' => 'Uribe',
    '1096' => 'Villavicencio',
    '1101' => 'Vistahermosa',

  ),

  "narino" => [
    '17' => 'Albán',
    '22' => 'Aldana',
    '37' => 'Ancuyá',
    '58' => 'Arboleda',
    '83' => 'Barbacoas',
    '96' => 'Belén',
    '128' => 'Buesaco',
    '189' => 'Chachagüí',
    '232' => 'Colón',
    '242' => 'Consacá',
    '243' => 'Contadero',
    '249' => 'Córdoba',
    '261' => 'Cuaspúd',
    '271' => 'Cumbal',
    '272' => 'Cumbitara',
    '300' => 'El Charco',
    '315' => 'El Peñol',
    '325' => 'El Rosario',
    '327' => 'El Tablón De Gómez',
    '329' => 'El Tambo',
    '354' => 'Francisco Pizarro',
    '360' => 'Funes',
    '395' => 'Guachucal',
    '401' => 'Guaitarilla',
    '402' => 'Gualmatán',
    '436' => 'Iles',
    '437' => 'Imués',
    '440' => 'Ipiales',
    '467' => 'La Cruz',
    '472' => 'La Florida',
    '477' => 'La Llanada',
    '494' => 'La Tola',
    '496' => 'La Unión',
    '510' => 'Leiva',
    '517' => 'Linares',
    '521' => 'Los Andes',
    '534' => 'Magüí',
    '540' => 'Mallama',
    '595' => 'Mosquera',
    '604' => 'Nariño',
    '629' => 'Olaya Herrera',
    '635' => 'Ospina',
    '668' => 'Pasto',
    '697' => 'Policarpa',
    '702' => 'Potosí',
    '705' => 'Providencia',
    '713' => 'Puerres',
    '744' => 'Pupiales',
    '767' => 'Ricaurte',
    '781' => 'Roberto Payán',
    '807' => 'Samaniego',
    '815' => 'San Andrés De Tumaco',
    '822' => 'San Bernardo',
    '859' => 'San Lorenzo',
    '875' => 'San Pablo',
    '879' => 'San Pedro De Cartago',
    '892' => 'Sandoná',
    '895' => 'Santa Bárbara',
    '914' => 'Santacruz',
    '923' => 'Sapuyes',
    '987' => 'Taminango',
    '988' => 'Tangua',
    '1039' => 'Túquerres',
    '1104' => 'Yacuanquer',
  ],
  "norteSantander" => [
    '2' => 'Ábrego',
    '59' => 'Arboledas',
    '109' => 'Bochalema',
    '121' => 'Bucarasica',
    '138' => 'Cáchira',
    '139' => 'Cácota',
    '201' => 'Chinácota',
    '212' => 'Chitagá',
    '245' => 'Convención',
    '266' => 'Cúcuta',
    '267' => 'Cucutilla',
    '285' => 'Durania',
    '293' => 'El Carmen',
    '330' => 'El Tarra',
    '331' => 'El Zulia',
    '386' => 'Gramalote',
    '423' => 'Hacarí',
    '429' => 'Herrán',
    '470' => 'La Esperanza',
    '489' => 'La Playa',
    '506' => 'Labateca',
    '524' => 'Los Patios',
    '526' => 'Lourdes',
    '600' => 'Mutiscua',
    '625' => 'Ocaña',
    '659' => 'Pamplona',
    '660' => 'Pamplonita',
    '738' => 'Puerto Santander',
    '755' => 'Ragonvalia',
    '801' => 'Salazar',
    '824' => 'San Calixto',
    '829' => 'San Cayetano',
    '917' => 'Santiago',
    '925' => 'Sardinata',
    '935' => 'Silos',
    '1002' => 'Teorama',
    '1009' => 'Tibú',
    '1023' => 'Toledo',
    '1081' => 'Villa Caro',
    '1084' => 'Villa Del Rosario',

  ],

  "putumayo" => array(
    '233' => 'Colón',
    '574' => 'Mocoa',
    '632' => 'Orito',
    '716' => 'Puerto Asís',
    '719' => 'Puerto Caicedo',
    '726' => 'Puerto Guzmán',
    '727' => 'Puerto Leguízamo',
    '838' => 'San Francisco',
    '871' => 'San Miguel',
    '918' => 'Santiago',
    '934' => 'Sibundoy',
    '1064' => 'Valle Del Guamuez',
    '1086' => 'Villagarzón',

  ),
  "quindio" => array(
    '69' => 'Armenia',
    '125' => 'Buenavista',
    '148' => 'Calarcá',
    '224' => 'Circasia',
    '250' => 'Córdoba',
    '341' => 'Filandia',
    '377' => 'Génova',
    '493' => 'La Tebaida',
    '585' => 'Montenegro',
    '685' => 'Pijao',
    '751' => 'Quimbaya',
    '803' => 'Salento',

  ),

  "risaralda" => [
    '49' => 'Apía',
    '80' => 'Balboa',
    '98' => 'Belén De Umbría',
    '283' => 'Dosquebradas',
    '415' => 'Guática',
    '465' => 'La Celia',
    '505' => 'La Virginia',
    '556' => 'Marsella',
    '572' => 'Mistrató',
    '679' => 'Pereira',
    '709' => 'Pueblo Rico',
    '752' => 'Quinchía',
    '908' => 'Santa Rosa De Cabal',
    '922' => 'Santuario',

  ],

  "sanAndres" => [
    '706' => 'Providencia',
    '812' => 'San Andrés',
  ],
  "santander" => array(
    '11' => 'Aguada',
    '20' => 'Albania',
    '54' => 'Aratoca',
    '85' => 'Barbosa',
    '86' => 'Barichara',
    '88' => 'Barrancabermeja',
    '106' => 'Betulia',
    '114' => 'Bolívar',
    '120' => 'Bucaramanga',
    '133' => 'Cabrera',
    '153' => 'California',
    '166' => 'Capitanejo',
    '170' => 'Carcasí',
    '183' => 'Cepitá',
    '186' => 'Cerrito',
    '194' => 'Charalá',
    '195' => 'Charta',
    '198' => 'Chima',
    '206' => 'Chipatá',
    '223' => 'Cimitarra',
    '237' => 'Concepción',
    '241' => 'Confines',
    '244' => 'Contratación',
    '252' => 'Coromoro',
    '275' => 'Curití',
    '296' => 'El Carmen De Chucurí',
    '309' => 'El Guacamayo',
    '318' => 'El Peñón',
    '320' => 'El Playón',
    '333' => 'Encino',
    '334' => 'Enciso',
    '347' => 'Florián',
    '349' => 'Floridablanca',
    '368' => 'Galán',
    '373' => 'Gámbita',
    '383' => 'Girón',
    '390' => 'Guaca',
    '399' => 'Guadalupe',
    '407' => 'Guapotá',
    '416' => 'Guavatá',
    '420' => 'Güepsa',
    '425' => 'Hato',
    '454' => 'Jesús María',
    '455' => 'Jordán',
    '461' => 'La Belleza',
    '484' => 'La Paz',
    '508' => 'Landázuri',
    '509' => 'Lebrija',
    '525' => 'Los Santos',
    '529' => 'Macaravita',
    '538' => 'Málaga',
    '558' => 'Matanza',
    '575' => 'Mogotes',
    '576' => 'Molagavita',
    '624' => 'Ocamonte',
    '626' => 'Oiba',
    '630' => 'Onzaga',
    '653' => 'Palmar',
    '655' => 'Palmas Del Socorro',
    '665' => 'Páramo',
    '682' => 'Piedecuesta',
    '687' => 'Pinchote',
    '712' => 'Puente Nacional',
    '733' => 'Puerto Parra',
    '742' => 'Puerto Wilches',
    '776' => 'Rionegro',
    '787' => 'Sabana De Torres',
    '811' => 'San Andrés',
    '819' => 'San Benito',
    '839' => 'San Gil',
    '843' => 'San Joaquín',
    '846' => 'San José De Miranda',
    '870' => 'San Miguel',
    '888' => 'San Vicente De Chucurí',
    '896' => 'Santa Bárbara',
    '900' => 'Santa Helena Del Opón',
    '938' => 'Simacota',
    '947' => 'Socorro',
    '962' => 'Suaita',
    '969' => 'Sucre',
    '974' => 'Suratá',
    '1025' => 'Tona',
    '1062' => 'Valle De San José',
    '1069' => 'Vélez',
    '1076' => 'Vetas',
    '1092' => 'Villanueva',
    '1115' => 'Zapatoca',
  ),

  "sucre" => array(
    '126' => 'Buenavista',
    '142' => 'Caimito',
    '191' => 'Chalán',
    '234' => 'Colosó',
    '253' => 'Corozal',
    '258' => 'Coveñas',
    '323' => 'El Roble',
    '370' => 'Galeras',
    '408' => 'Guaranda',
    '497' => 'La Unión',
    '523' => 'Los Palmitos',
    '537' => 'Majagual',
    '593' => 'Morroa',
    '637' => 'Ovejas',
    '657' => 'Palmito',
    '808' => 'Sampués',
    '820' => 'San Benito Abad',
    '853' => 'San Juan De Betulia',
    '864' => 'San Luis De Sincé',
    '865' => 'San Marcos',
    '873' => 'San Onofre',
    '877' => 'San Pedro',
    '919' => 'Santiago De Tolú',
    '941' => 'Sincelejo',
    '970' => 'Sucre',
    '1024' => 'Tolú Viejo',

  ),

  "tolima" => [
    '28' => 'Alpujarra',
    '32' => 'Alvarado',
    '35' => 'Ambalema',
    '47' => 'Anzoátegui',
    '70' => 'Armero Guayabal',
    '73' => 'Ataco',
    '143' => 'Cajamarca',
    '172' => 'Carmen De Apicalá',
    '180' => 'Casabianca',
    '193' => 'Chaparral',
    '229' => 'Coello',
    '259' => 'Coyaima',
    '273' => 'Cunday',
    '281' => 'Dolores',
    '337' => 'Espinal',
    '339' => 'Falan',
    '343' => 'Flandes',
    '356' => 'Fresno',
    '405' => 'Guamo',
    '430' => 'Herveo',
    '433' => 'Honda',
    '434' => 'Ibagué',
    '435' => 'Icononzo',
    '513' => 'Lérida',
    '515' => 'Líbano',
    '564' => 'Melgar',
    '597' => 'Murillo',
    '606' => 'Natagaima',
    '634' => 'Ortega',
    '658' => 'Palocabildo',
    '683' => 'Piedras',
    '694' => 'Planadas',
    '704' => 'Prado',
    '746' => 'Purificación',
    '772' => 'Rioblanco',
    '783' => 'Roncesvalles',
    '786' => 'Rovira',
    '802' => 'Saldaña',
    '817' => 'San Antonio',
    '861' => 'San Luis',
    '887' => 'San Sebastián De Mariquita',
    '901' => 'Santa Isabel',
    '965' => 'Suárez',
    '1063' => 'Valle De San Juan',
    '1070' => 'Venadillo',
    '1088' => 'Villahermosa',
    '1095' => 'Villarrica',
  ],


  "vaupes" => array(
    '179' => 'Carurú',
    '573' => 'Mitú',
    '640' => 'Pacoa',
    '664' => 'Papunaua',
    '989' => 'Taraira',
    '1108' => 'Yavaraté',

  ),


  "valleCauca" => [
    '21' => 'Alcalá',
    '38' => 'Andalucía',
    '45' => 'Ansermanuevo',
    '65' => 'Argelia',
    '115' => 'Bolívar',
    '122' => 'Buenaventura',
    '129' => 'Bugalagrande',
    '141' => 'Caicedonia',
    '152' => 'Cali',
    '154' => 'Calima',
    '162' => 'Candelaria',
    '178' => 'Cartago',
    '278' => 'Dagua',
    '287' => 'El Águila',
    '290' => 'El Cairo',
    '299' => 'El Cerrito',
    '306' => 'El Dovio',
    '348' => 'Florida',
    '379' => 'Ginebra',
    '392' => 'Guacarí',
    '396' => 'Guadalajara De Buga',
    '448' => 'Jamundí',
    '468' => 'La Cumbre',
    '498' => 'La Unión',
    '503' => 'La Victoria',
    '623' => 'Obando',
    '656' => 'Palmira',
    '703' => 'Pradera',
    '764' => 'Restrepo',
    '773' => 'Riofrío',
    '782' => 'Roldanillo',
    '878' => 'San Pedro',
    '931' => 'Sevilla',
    '1029' => 'Toro',
    '1033' => 'Trujillo',
    '1036' => 'Tuluá',
    '1048' => 'Ulloa',
    '1075' => 'Versalles',
    '1080' => 'Vijes',
    '1112' => 'Yotoco',
    '1113' => 'Yumbo',
    '1118' => 'Zarzal',

  ],
  "vichada" => array(
    '270' => 'Cumaribo',
    '490' => 'La Primavera',
    '720' => 'Puerto Carreño',
    '912' => 'Santa Rosalía',
  ),
);
?>