<?php
$period = $dba->query('SELECT * FROM periods WHERE status = "enabled"')->fetchArray();

$dates = Specific::ComposeDates($period['dates']);
if(!empty($period)){
	$TEMP['name'] = $period['name'];
	$start = Specific::DateFormat($period['start'], true);
	$final = Specific::DateFormat($period['final'], true);
	$TEMP['period'] = "{$TEMP['#word']['from_the']} $start {$TEMP['#word']['until_the']} $final";

	$TEMP['dates_0'] = $dates[0];
	$TEMP['dates_1'] = $TEMP['#word']['undefined'];
	if($dates[1][0] != $TEMP['#word']['undefined'] && $dates[1][1] != $TEMP['#word']['undefined']){
		$TEMP['dates_1'] = "{$TEMP['#word']['from_the']} {$dates[1][0]} {$TEMP['#word']['until_the']} {$dates[1][1]}";
	}
	$TEMP['dates_2'] = $TEMP['#word']['undefined'];
	if($dates[2][0] != $TEMP['#word']['undefined'] && $dates[2][1] != $TEMP['#word']['undefined']){
		$TEMP['dates_2'] = "{$TEMP['#word']['from_the']} {$dates[2][0]} {$TEMP['#word']['until_the']} {$dates[2][1]}";
	}
	$TEMP['dates_3'] = $dates[3];
	$TEMP['dates_4'] = $dates[4];
	$TEMP['dates_5'] = $dates[5];
	$TEMP['dates_6'] = $TEMP['#word']['undefined'];
	if($dates[6][0] != $TEMP['#word']['undefined'] && $dates[6][1] != $TEMP['#word']['undefined']){
		$TEMP['dates_6'] = "{$TEMP['#word']['from_the']} {$dates[6][0]} {$TEMP['#word']['until_the']} {$dates[6][1]}";
	}
	$TEMP['dates_7'] = $dates[7];
	$TEMP['dates_8'] = $TEMP['#word']['undefined'];
	if($dates[8][0] != $TEMP['#word']['undefined'] && $dates[8][1] != $TEMP['#word']['undefined']){
		$TEMP['dates_8'] = "{$TEMP['#word']['from_the']} {$dates[8][0]} {$TEMP['#word']['until_the']} {$dates[8][1]}";
	}
	$TEMP['dates_9'] = $dates[9];
	$TEMP['dates_10'] = $dates[10];
	$TEMP['dates_11'] = $dates[11];
	$TEMP['dates_12'] = $dates[12];
	$TEMP['dates_13'] = $dates[13];
} else {
	$TEMP['period'] = $TEMP['#word']['undefined'];
	$TEMP['dates_0'] = $TEMP['#word']['undefined'];
	$TEMP['dates_1'] = $TEMP['#word']['undefined'];
	$TEMP['dates_2'] = $TEMP['#word']['undefined'];
	$TEMP['dates_3'] = $TEMP['#word']['undefined'];
	$TEMP['dates_4'] = $TEMP['#word']['undefined'];
	$TEMP['dates_5'] = $TEMP['#word']['undefined'];
	$TEMP['dates_6'] = $TEMP['#word']['undefined'];
	$TEMP['dates_7'] = $TEMP['#word']['undefined'];
	$TEMP['dates_8'] = $TEMP['#word']['undefined'];
	$TEMP['dates_9'] = $TEMP['#word']['undefined'];
	$TEMP['dates_10'] = $TEMP['#word']['undefined'];
	$TEMP['dates_11'] = $TEMP['#word']['undefined'];
	$TEMP['dates_12'] = $TEMP['#word']['undefined'];
	$TEMP['dates_13'] = $TEMP['#word']['undefined'];
}

$TEMP['#page']   	   = 'academic-calendar';
$TEMP['#title'] 	   = $TEMP['#settings']['title'] . ' - ' . $TEMP['#word']['academic_calendar'];
$TEMP['#description']  = $TEMP['#settings']['description'];
$TEMP['#keyword']      = $TEMP['#settings']['keyword'];
$TEMP['#load_url'] 	   = Specific::Url('academic-calendar');
$TEMP['#content'] = Specific::Maket("academic-calendar/content");