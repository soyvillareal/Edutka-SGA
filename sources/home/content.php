<?php

$TEMP['rol'] = Specific::Academic() == true ? 'Academico' : (Specific::Teacher() == true ? 'Profesor' : 'Alumno');

$TEMP['#page']         = 'home';
$TEMP['#title']        = $TEMP['#settings']['title'].' - '.$TEMP['#word']['home'];
$TEMP['#description']  = $TEMP['#settings']['description'];
$TEMP['#keyword']      = $TEMP['#settings']['keyword'];
$TEMP['#load_url']     = Specific::Url();
$TEMP['#content']      = Specific::Maket('home/content');
?>