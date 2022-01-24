<?php
if ($TEMP['#loggedin'] === false) {
	header("Location: ".Specific::ReturnUrl());
	exit();
}

$TEMP['#user_id'] = $TEMP['#user']['id'];
if(isset($_GET['user']) && (Specific::Admin() == true || Specific::Academic() == true || Specific::Teacher() == true)){
	$TEMP['#user_id'] = Specific::Filter($_GET['user']);
}

$params = "";
$sqls = '';
$TEMP['average'] = '- -';

$TEMP['#type'] = Specific::Filter($_GET['type']);
$TEMP['#course_id'] = Specific::Filter($_GET['course_id']);
$TEMP['#keyword_notes'] = Specific::Filter($_GET['keyword']);
if(empty($_GET['type'])){
	$TEMP['#type'] = 'student';
}
$TEMP['#role'] = 'teacher';
if(Specific::Student() == true){
	$TEMP['#role'] = 'student';
}

if($TEMP['#type'] == 'student'){
	$user = Specific::Data($TEMP['#user_id']);
	$TEMP['#program_id'] = Specific::Filter($_GET['program']);
	if(empty($_GET['program'])){
		$TEMP['#program_id'] = $user['program'];
	}
	$TEMP['#period_id'] = Specific::Filter($_GET['period']);
	$TEMP['#programs'] = $dba->query('SELECT * FROM programs p WHERE (SELECT program_id FROM enrolled WHERE user_id = '.$TEMP['#user_id'].' AND program_id = p.id AND type = "program") = id')->fetchAll();

	if(!empty($TEMP['#keyword_notes']) || Specific::Student() == true){
		if(empty($_GET['period'])){
			$TEMP['#period_id'] = $dba->query('SELECT max(period_id) FROM enrolled WHERE type = "course" AND user_id = '.$TEMP['#user_id'].' AND program_id = '.$TEMP['#program_id'])->fetchArray();
		}

		$periods = $dba->query('SELECT period_id FROM enrolled WHERE type = "course" AND user_id = '.$TEMP['#user_id'].' AND program_id = '.$TEMP['#program_id'])->fetchAll(false);

		if(!empty($periods)){
			$TEMP['#periods'] = $dba->query('SELECT * FROM periods WHERE id IN ('.implode(',', $periods).')')->fetchAll();
		}

		if(Specific::Admin() == true || Specific::Academic() == true || Specific::Teacher() == true){
			if(isset($_GET['keyword'])){
				$params .= "?keyword=".$TEMP['#keyword_notes'];
			}
			if(isset($_GET['user'])){
				$params .= "&user={$TEMP['#user_id']}";
			}
		}

		if(Specific::Teacher() == true){
		    if(!empty($TEMP['#keyword_notes'])){
		    	$my_courses = $dba->query('SELECT course_id FROM teacher WHERE user_id = '.$TEMP['#user_id'].' AND period_id = '.$TEMP['#period_id'])->fetchAll(false);
		    	if(!empty($my_courses)){
				    $sqls .= ' AND course_id IN ('.implode(',', $my_courses).')';
				    if(empty($_GET['period'])){
				    	$TEMP['#period_id'] = $dba->query('SELECT max(period_id) FROM enrolled WHERE type = "course" AND user_id = '.$TEMP['#user_id'].' AND course_id = '.$my_courses[0].' AND program_id = '.$TEMP['#program_id'])->fetchArray();
				    }
				}
			    $periods = $dba->query('SELECT period_id FROM enrolled WHERE user_id = '.$TEMP['#user_id'].' AND course_id IN ((SELECT course_id FROM teacher WHERE user_id = '.$TEMP['#user_id'].')) AND program_id = '.$TEMP['#program_id'])->fetchAll(false);
			    if(!empty($periods)){
			    	$TEMP['#periods'] =  $dba->query('SELECT * FROM periods WHERE id IN ('.implode(',', $periods).')')->fetchAll();
			    }
			}
		}

		if(!empty($TEMP['#program_id'])){
			$plan = $dba->query('SELECT * FROM plan WHERE program_id = '.$TEMP['#program_id'])->fetchArray();
			$TEMP['#note_mode'] = $plan['note_mode'];
			$courses = $dba->query('SELECT course_id FROM curriculum WHERE plan_id = '.$plan['id'])->fetchAll(false);
			$sqls .= ' AND course_id IN ('.implode(',', $courses).') AND program_id = '.$TEMP['#program_id'];
			$params .= (!empty($params) ? "&" : "?")."program={$TEMP['#program_id']}";
		}
		if(!empty($TEMP['#period_id'])){
			$sqls .= ' AND period_id = '.$TEMP['#period_id'];
			$params .= "&period={$TEMP['#period_id']}";
		}
		$TEMP['#notes'] = $dba->query('SELECT * FROM notes'.(!empty($TEMP['#period_id']) ? ' n ' : ' ').'WHERE user_id = '.$TEMP['#user_id'].$sqls)->fetchAll();
	}
} else {
	if(Specific::Admin() == true || Specific::Academic() == true || Specific::Teacher() == true){
		if(Specific::Admin() == true || Specific::Academic() == true){
			if(isset($_GET['keyword'])){
				$params .= "?keyword=".$TEMP['#keyword_notes'];
			}
			if(isset($_GET['user'])){
				$params .= "&user={$TEMP['#user_id']}";
			}
		}
		$params .= (!empty($params) ? "&" : "?")."type={$TEMP['#type']}";
		$TEMP['#courses'] = $dba->query('SELECT * FROM courses c WHERE id IN ((SELECT course_id FROM teacher WHERE user_id = '.$TEMP['#user_id'].' AND period_id IN ((SELECT period_id FROM enrolled)) AND course_id = c.id))')->fetchAll();
		if(!empty($TEMP['#course_id'])){
			$TEMP['#program_id'] =  Specific::Filter($_GET['program']);
			$TEMP['#period_id'] =  Specific::Filter($_GET['period']);
			if(!empty($TEMP['#course_id'])){
				if(empty($_GET['program'])){
					$TEMP['#program_id'] = $dba->query('SELECT max(program_id) FROM enrolled WHERE course_id = '.$TEMP['#course_id'])->fetchArray();
				}
				if(empty($_GET['period'])){
					$TEMP['#period_id'] = $dba->query('SELECT max(period_id) FROM enrolled WHERE course_id = '.$TEMP['#course_id'])->fetchArray();
				}
			}

			if(!empty($TEMP['#course_id'])){
				$sqls = ' WHERE course_id = '.$TEMP['#course_id'];
				$params .= "&course_id={$TEMP['#course_id']}";
			} else {
				$sqls = ' WHERE course_id = 0';
			}

			$periods = $dba->query('SELECT period_id FROM enrolled e WHERE type = "course" AND (SELECT period_id FROM teacher WHERE user_id = '.$TEMP['#user_id'].' AND course_id = '.$TEMP['#course_id'].' AND period_id = e.period_id) = period_id AND program_id = '.$TEMP['#program_id'])->fetchAll(false);

			$TEMP['#periods'] = $dba->query('SELECT * FROM periods WHERE id IN ('.implode(',', $periods).')')->fetchAll();
			
			if(!empty($TEMP['#program_id'])){
				$plan = $dba->query('SELECT * FROM plan WHERE program_id = '.$TEMP['#program_id'])->fetchArray();
				$TEMP['#note_mode'] = $plan['note_mode'];
				$courses = $dba->query('SELECT course_id FROM curriculum WHERE plan_id = '.$plan['id'])->fetchAll(false);
				$sqls .= ' AND course_id IN ('.implode(',', $courses).') AND program_id = '.$TEMP['#program_id'];
				$params .= (!empty($params) ? "&" : "?")."program={$TEMP['#program_id']}";
			}
			if(!empty($TEMP['#period_id'])){
				$sqls .= ' AND period_id = '.$TEMP['#period_id'];
				$params .= "&period={$TEMP['#period_id']}";
			}
			$TEMP['#notes'] = $dba->query('SELECT * FROM notes'.(!empty($TEMP['#period_id']) ? ' n ' : ' ').$sqls)->fetchAll();
		}
	}
}

$screen = Specific::Filter($_GET['screen']);
if(empty($screen)){
	$TEMP['#screen'] = "twoforone";
	$TEMP['#twoforone_content'] = '';
	$TEMP['#twoforone_item'] = 'display-flex w-100 ';
} else {	
	if($screen == 'twoforone'){
		$TEMP['#screen'] = "oneforone";
		if(count($TEMP['#notes']) > 1){
			$TEMP['#twoforone_content'] = 'twoforone-content ';
			$TEMP['#twoforone_item'] = 'twoforone-item ';
		}
	} else {
		$TEMP['#screen'] = "twoforone";
		$TEMP['#twoforone_content'] = '';
		$TEMP['#twoforone_item'] = 'display-flex w-100 ';
	}
}



$params .= "&screen=$screen";

$TEMP['#url_params'] = str_replace('?', '&', "&one=notes$params");
$TEMP['#load_url'] = Specific::Url("notes$params");

if(!empty($TEMP['#notes'])){
	$qua_arr = array();
	foreach ($TEMP['#notes'] as $note) {
		$qua_arr[] = 'false';
		$course = $dba->query('SELECT * FROM courses WHERE id = '.$note['course_id'])->fetchArray();
		if($course['qualification'] == 'activated' && round((($notes[0]*0.3)+($notes[1]*0.3)+($notes[2]*0.4)), 2) <= $TEMP['#nmdph']){
			$qua_arr[] = 'true';
		}
	}
	$atrues = array_count_values($qua_arr)['true'];

	foreach ($TEMP['#notes'] as $note) {
		if(is_numeric($TEMP['#period_id'])){
			$parameters = $dba->query('SELECT parameters FROM parameter p WHERE (SELECT id FROM teacher WHERE course_id = '.$note['course_id'].' AND period_id = '.$TEMP['#period_id'].' AND id = p.teacher_id) = teacher_id')->fetchArray();
		}
		$notes = json_decode($note['notes'], true);
		$course = $dba->query('SELECT * FROM courses WHERE id = '.$note['course_id'])->fetchArray();
		$period = $dba->query('SELECT * FROM periods WHERE id = '.$TEMP['#period_id'])->fetchArray();
		$TEMP['!period_final'] = time() > $period['final'];
		$authorization = $dba->query('SELECT court FROM authorization WHERE status = "authorized" AND period_id = '.$period['id'].' AND course_id = '.$note['course_id'])->fetchAll(false);
		$TEMP['!authorization'] = Specific::Admin() == true || Specific::Academic() == true ? array('first', 'second', 'third') : $authorization;

		if($TEMP['#type'] == 'course'){
			$user = Specific::Data($note['user_id']);
			$TEMP['!student'] = $user['full_name'];
		}

		$teachers = $dba->query('SELECT names FROM users u WHERE (SELECT user_id FROM teacher WHERE user_id = u.id AND course_id = '.$note['course_id'].' AND period_id = '.$TEMP['#period_id'].') = id')->fetchAll(false);
		if(!empty($teachers)){
			if(count($teachers) == 2){
				$TEMP['!teacher'] = "{$teachers[0]} {$TEMP['#word']['and']} {$teachers[1]}";
			} else if(count($teachers) > 2){
				$end = end($teachers);
				array_pop($teachers);
				$TEMP['!teacher'] = implode(', ', $teachers)." {$TEMP['#word']['and']} $end";
			} else {
				$TEMP['!teacher'] = $teachers[0];
			}
		} else {
			$TEMP['!teacher'] = $TEMP['#word']['pending'];
		}

		$TEMP['!id'] = $note['id'];
	    $TEMP['!period'] = $period['name'];
	    $TEMP['!course'] = $course['name'];
	    $TEMP['!parameters'] = $parameters;

	    $TEMP['!qcstatus'] = $course['qualification'];

		$qualification = $dba->query('SELECT note, status, COUNT(*) as count FROM qualification WHERE note_id = '.$note['id'])->fetchArray();

		$fa = Specific::ValidateDates($TEMP['#period_id'], 17, 2);
		$TEMP['!can_qua'] = false;
		if(Specific::Student() == true && $fa == true){
			$TEMP['!can_qua'] = true;
		}
		$TEMP['!qexists'] = false;
		if(Specific::Student() == true){
			$TEMP['!fah'] = true;
		}

		if($qualification['count'] > 0){
			$TEMP['!qualification_note'] = 0;
			$TEMP['!qualification_val'] = $qualification['note'];
			$TEMP['!qualification_text'] = $TEMP['#word']['upload_note'];
			if($qualification['note'] != NULL){
				$TEMP['!qualification_text'] = $TEMP['!qualification_note'] = $qualification['note'];
			}
			$TEMP['!qexists'] = true;
			$TEMP['!qstatus'] = $qualification['status'];
			if(($qualification['status'] == 'accepted' || (Specific::Student() == true && $fa == true)) && $fa == true && ($atrues == 1 || $atrues == 2) || Specific::Admin() == true || Specific::Academic() == true){
			    $TEMP['!can_qua'] = true;
			}
			$TEMP['!fah'] = Specific::ValidateDates($TEMP['#period_id'], 10, 1);
			$TEMP['!frh_2'] = Specific::ValidateDates($TEMP['#period_id'], 12);
			$TEMP['!flrnh'] = Specific::ValidateDates($TEMP['#period_id'], 13);
		}



	    if($TEMP['#note_mode'] == '30-30-40'){
	    	for ($i=0; $i < 3; $i++) { 
		    	$anotes = array();
		        $parameters = json_decode($TEMP['!parameters'], true)[$i];
		        foreach ($parameters as $key => $param) {
		        	$anotes[] = (($notes[$i][$key]/100)*$param['percent']);
		        }
		        $notes[$i] = array_sum($anotes);
		    }
			$TEMP['!first'] = $notes[0];
		    $TEMP['!second'] = $notes[1];
		    $TEMP['!third'] = $notes[2];

		    $TEMP['!last_eval'] = false;
		    if((($notes[0]*0.3)+($notes[1]*0.3)) >= $TEMP['#nnevf']){
		    	$TEMP['!last_eval'] = true;
		    }

		    unset($TEMP['!evalfinal']);
			unset($TEMP['!article']);
		    $TEMP['!average'] = $average[] = $qualification['note'];
		    if($qualification['note'] == NULL){
		    	$TEMP['!average'] = $average[] = round((($notes[0]*0.3)+($notes[1]*0.3)+($notes[2]*0.4)), 2);
				if($TEMP['!average'] >= 0 && $notes[2] < $TEMP['#nmtc']){
			    	$TEMP['!evalfinal'] = true;
			    	$TEMP['!article'] = 43;
			    	$TEMP['!average'] = $average[] = round($notes[2], 2);
			    }
		    }
	    } else {
	    	$anotes = array();
		    $parameters = json_decode($TEMP['!parameters'], true);
		    foreach ($parameters as $key => $param) {
		      	$anotes[] = (($notes[$key]/100)*$param['percent']);
		    }
		    $notes = array_sum($anotes);
			$TEMP['!first'] = $notes;

		    $TEMP['!average'] = $average[] = $qualification['note'];
		    if($qualification['note'] == NULL){
		    	$TEMP['!average'] = $average[] = round($notes, 2);
		    }
	    }

	    $TEMP['!approved'] = ($course['type'] == 'practice' && $TEMP['!average'] >= $TEMP['#nmcnt']) || ($course['type'] == 'theoretical' && $TEMP['!average'] >= $TEMP['#nmct']) ? true : false;
	    if($TEMP['!approved'] == false){
	    	$avekey[] = $TEMP['!average'];
	    } else if($notes[2] > $TEMP['#nmtc']){
	    	$TEMP['!article'] = 60;
	    }
	    $TEMP['!status'] = $TEMP['!period_final'] == true ? $TEMP['#word']['finalized'] : $TEMP['#word']['in_progress'];
	    $TEMP['notes'] .= Specific::Maket("notes/includes/notes-list");
	}
	Specific::DestroyMaket();

	if(Specific::Teacher() == false){
		$TEMP['#semesbad'] = Specific::ValidateDates($TEMP['#period_id'], 17, 2) == true && count($avekey) >= $TEMP['#cers'];
	}
	$TEMP['average'] = round(array_sum($average)/count($average), 2);
} else {
	$TEMP['notes'] .= Specific::Maket("not-found/notes");
}


$TEMP['#page']        = 'notes';
$TEMP['#title']       = $TEMP['#word']['notes'] . ' - ' . $TEMP['#settings']['title'];
$TEMP['#description'] = $TEMP['#settings']['description'];
$TEMP['#keyword']     = $TEMP['#settings']['keyword'];

$TEMP['#content']     = Specific::Maket('notes/content');
?>