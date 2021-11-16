<?php
if ($TEMP['#loggedin'] === false && (Specific::Academic() === false || Specific::Student() === false)) {
    $deliver = array(
        'status' => 400,
        'error' => $TEMP['#word']['error']
    );
    echo json_encode($deliver);
    exit();
}


if($one == 'search-enrolled'){
	$keyword = Specific::Filter($_POST['keyword']);
	$id = Specific::Filter($_POST['id']);
	$type = Specific::Filter($_POST['type']);
	$program_id = Specific::Filter($_POST['program_id']);
    $html = '';
    $query = '';
    if(!empty($keyword)){
        $query .= " WHERE (id LIKE '%$keyword%' OR name LIKE '%$keyword%')";
        if($type == 'course'){
        	$enrolled_plans = array();
        	$courses = $dba->query('SELECT course_id FROM assigned a WHERE (SELECT id FROM plan WHERE program_id = '.$program_id.' AND id = a.plan_id) = plan_id')->fetchAll(false);
        	if(!empty($courses)){
        		$query .= " AND id IN (".implode(',', $courses).")";
        	}
        }
       	$searchs = $dba->query('SELECT * FROM '.$type.'s'.$query)->fetchAll();

	    if (!empty($searchs)) {
	        foreach ($searchs as $search) {
	            if($type == 'course'){
					$periodc = $dba->query('SELECT name FROM periods p WHERE (SELECT period_id FROM courses WHERE id = '.$search['id'].' AND period_id = p.id)')->fetchArray();
					$teachers = $dba->query('SELECT names FROM users u WHERE (SELECT user_id FROM teacher WHERE user_id = u.id AND course_id = '.$search['id'].') = id')->fetchAll(false);
					if(count($teachers) == 2){
						$teachers = "{$teachers[0]} {$TEMP['#word']['and']} {$teachers[1]}";
					} else if(count($teachers) > 2){
						$end = end($teachers);
						array_pop($teachers);
						$teachers = implode(', ', $teachers)." {$TEMP['#word']['and']} $end";
					} else {
						$teachers = $teachers[0];
					}
					$TEMP['!teacher'] = $teachers;
					$TEMP['!name'] = "{$search['name']} ($periodc)";
					$TEMP['!program_id'] = $program_id;
					$TEMP['!color'] = 'purple';
					$search_id = 'course_id';
				} else {
					$TEMP['!name'] = $search['name'];
					$TEMP['!color'] = 'green';
					$search_id = 'program_id';
				}
				$TEMP['!id'] = $search['id'];
				$TEMP['#enrolled'] = $dba->query('SELECT * FROM enrolled WHERE user_id = '.$id.' AND '.$search_id.' = '.$search['id'])->fetchArray();
				$TEMP['!text'] = $TEMP['#word']['enroll'];
				$TEMP['!status'] = 'unregistered';
				$TEMP['!type'] = $TEMP['#word'][$type];
				$TEMP['!typet'] = $type;
				$TEMP['!class_event'] = $type == 'course' ? 'show_rcmodal"' : 'show_rpmodal"';
				if(!empty($TEMP['#enrolled'])){
					if($TEMP['#enrolled']['status'] == 'registered'){
						$TEMP['!id'] = $TEMP['#enrolled']['id'];
						$TEMP['!text'] = $TEMP['#word']['cancel'];
					}
					$TEMP['!text'] = $TEMP['#enrolled']['status'] == 'registered' ? $TEMP['#word']['cancel'] : $TEMP['#word']['enroll'];
					$TEMP['!status'] = $TEMP['#enrolled']['status'];
				    $TEMP['!type'] = "{$TEMP['#word'][$type]} ({$TEMP['#word'][$TEMP['#enrolled']['status']]})";
				    $TEMP['!time'] = Specific::DateFormat($TEMP['#enrolled']['time']);
				}
				$html .= Specific::Maket("enroll/includes/enroll-list");
	        }
	        Specific::DestroyMaket();
	        $deliver['status'] = 200;
	    } else {
            if(!empty($keyword)){
	            $TEMP['keyword'] = $keyword;
	   	        $html .= Specific::Maket('not-found/result-for');
	        } else {
	            $html .= Specific::Maket('not-found/enroll');
	        }
	    }
    } else {
    	if(isset($program_id)){
    		$query .= ' AND program_id = '.$program_id;
    	}
   		$TEMP['#enrolled'] = $dba->query('SELECT * FROM enrolled WHERE user_id = '.$id.' AND type = "'.$type.'"'.$query)->fetchAll();
        if(!empty($TEMP['#enrolled'])){
			foreach ($TEMP['#enrolled'] as $enroll) {
				if($enroll['type'] == 'course'){
					$course = $dba->query('SELECT name FROM courses WHERE id = '.$enroll['course_id'])->fetchArray();
					$periodc = $dba->query('SELECT name FROM periods p WHERE (SELECT period_id FROM courses WHERE id = '.$enroll['course_id'].' AND period_id = p.id)')->fetchArray();
					$teachers = $dba->query('SELECT names FROM users u WHERE (SELECT user_id FROM teacher WHERE user_id = u.id AND course_id = '.$enroll['course_id'].') = id')->fetchAll(false);
					if(count($teachers) == 2){
						$teachers = "{$teachers[0]} {$TEMP['#word']['and']} {$teachers[1]}";
					} else if(count($teachers) > 2){
						$end = end($teachers);
						array_pop($teachers);
						$teachers = implode(', ', $teachers)." {$TEMP['#word']['and']} $end";
					} else {
						$teachers = $teachers[0];
					}
					$TEMP['!teacher'] = $teachers;
					$TEMP['!name'] = "{$course} ($periodc)";
					$TEMP['!color'] = 'purple';
				} else {
					$program = $dba->query('SELECT * FROM programs WHERE id = '.$enroll['program_id'])->fetchArray();
					$TEMP['!name'] = $program['name'];
					$TEMP['!color'] = 'green';
				}

				if($enroll['status'] == 'registered'){
					$TEMP['!id'] = $enroll['id'];
					$TEMP['!text'] = $TEMP['#word']['cancel'];
				} else {
					if($enroll['type'] == 'course'){
						$TEMP['!id'] = $enroll['course_id'];
					} else {
						$TEMP['!id'] = $enroll['program_id'];
					}
					$TEMP['!text'] = $TEMP['#word']['enroll'];
				}
				$TEMP['!class_event'] = $enroll['type'] == 'course' ? 'show_rcmodal"' : 'show_rpmodal"';
				if($enroll['status'] == 'cancelled'){
					if(Specific::Academic() == false){
						$TEMP['!class_event'] = 'cursor-disabled" disabled';
					}
				} else {
					$TEMP['!class_event'] = 'show_cmodal"';
				}
				$TEMP['!status'] = $enroll['status'];
			    $TEMP['!type'] = "{$TEMP['#word'][$enroll['type']]} ({$TEMP['#word'][$enroll['status']]})";
			    $TEMP['!typet'] = $enroll['type'];
			    $TEMP['!time'] = Specific::DateFormat($enroll['time']);
			    $html .= Specific::Maket("enroll/includes/enroll-list");
			}
			Specific::DestroyMaket();
		} else {
			$html .= Specific::Maket("not-found/enroll");
		}
    }
    $deliver['html'] = $html;
} else if($one == 'cancel-enroll'){
	$deliver['status'] = 400;
    $id = Specific::Filter($_POST['id']);
    if (isset($id) && is_numeric($id)) {
        if($dba->query('UPDATE enrolled SET status = "cancelled" WHERE id = '.$id)->returnStatus()){
            $deliver['status'] = 200;
        };
    }
} else if($one == 'register-ecnroll'){
	$deliver['status'] = 400;
    $course_id = Specific::Filter($_POST['course_id']);
    $user_id = Specific::Filter($_POST['user_id']);
    $program_id = Specific::Filter($_POST['program_id']);
    $code = Specific::Filter($_POST['code']);

    if (isset($course_id) && is_numeric($course_id)) {
    	$enrolled_courses = $dba->query('SELECT COUNT(*) FROM enrolled WHERE type = "course" AND user_id = '.$user_id.' AND course_id = '.$course_id)->fetchArray();
    	if($enrolled_courses == 0){
	    	if(Specific::Academic() == true || !empty($code)){
	    		$courses = $dba->query('SELECT * FROM courses WHERE id = "'.$course_id.'"')->fetchArray();
		        if(Specific::Academic() == true || $courses['code'] === $code){
		        	$prektrues = array();
		        	if(!empty($courses['preknowledge'])){
		        		$preknowledges = explode(',', $courses['preknowledge']);
			        	foreach ($preknowledges as $prek) {
			        		$course = $dba->query('SELECT * FROM courses WHERE id = '.$prek)->fetchArray();
			        		$notes = $dba->query('SELECT notes FROM notes WHERE user_id = '.$user_id.' AND course_id = '.$prek)->fetchArray();
			        		$notes = json_decode($notes, true);
			        		for ($i=0; $i < 3; $i++) { 
						    	$anotes = array();
							    $rnotes = json_decode($notes[$i], true);
						        $parameters = json_decode($course['parameters'], true)[$i];
						        foreach ($parameters as $key => $param) {
						        	$anotes[] = (($rnotes[$key]/100)*$param['percent']);
						        }
						        $notes[$i] = array_sum($anotes);
						    }
						    $average = round((($notes[0]*0.3)+($notes[1]*0.3)+($notes[2]*0.4)), 2);
						    $prektrues[] = ($course['type'] == 'practice' && $average >= 3.5) || ($course['type'] == 'theoretical' && $average >= 3.0) ? true : false;
			        	}
		        	}
		        	if(!in_array(false, $prektrues) || empty($prektrues)){
			        	$plan = $dba->query('SELECT id, COUNT(*) AS count FROM plan WHERE program_id = '.$program_id)->fetchArray();
			        	if($plan['count']){
			        		if($dba->query('SELECT COUNT(*) FROM assigned WHERE course_id = '.$course_id.' AND plan_id = '.$plan['id'])->fetchArray() > 0){
				        		if($dba->query('INSERT INTO enrolled (user_id, course_id, program_id, type, status, `time`) VALUES ('.$user_id.', '.$course_id.', '.$program_id.', "course", "registered",'.time().')')->returnStatus() && $dba->query('INSERT INTO notes (user_id, course_id, program_id, notes, `time`) VALUES ('.$user_id.','.$course_id.', '.$program_id.', "'.json_encode(array(0.0, 0.0, 0.0)).'",'.time().')')->returnStatus()){
					        		$deliver['status'] = 200;
					        		$teachers = $dba->query('SELECT user_id FROM teacher WHERE course_id = '.$course_id)->fetchAll(false);
					        		foreach ($teachers as $teacher) {
					        			Specific::SendNotification(array(
						                    'from_id' => $TEMP['#user']['id'],
						                    'to_id' => $teacher,
						                    'course_id' => $course_id,
						                    'type' => "'enroll'",
						                    'time' => time()
						                ));
					        		}
					        	}
				        	}
			        	} else {
			        		$deliver = array(
			        			'status' => 400,
			        			'error' => $TEMP['#word']['error']
			        		);
			        	}	
		        	} else {
		        		$deliver = array(
		        			'status' => 400,
		        			'error' => $TEMP['#word']['you_must_first_approve_prequalifications']
		        		);
		        	}
		        } else {
		        	$deliver = array(
		        		'status' => 400,
		        		'error' => $TEMP['#word']['wrong_code']
		        	);
		        }
	    	} else {
	    		$deliver = array(
		       		'status' => 400,
		       		'error' => $TEMP['#word']['please_enter__code_this_course']
		       	);
	    	}
	    } else {
	    	$enrolled_courses = $dba->query('SELECT * FROM enrolled WHERE user_id = '.$user_id.' AND course_id = '.$course_id)->fetchArray();
	    	if(!empty($enrolled_courses) && $enrolled_courses['status'] == 'cancelled'){
	    		if($dba->query('UPDATE enrolled SET status = "registered" WHERE id = '.$enrolled_courses['id'])->returnStatus()){
	    			$deliver['status'] = 200;
	    		}
	    	} else {
		   		$deliver = array(
		       		'status' => 400,
		       		'error' => $TEMP['#word']['error']
			    );
	    	}
	    }
    } else {
   		$deliver = array(
       		'status' => 400,
       		'error' => $TEMP['#word']['error']
        );
    }
} else if($one == 'register-epnroll'){
	$deliver['status'] = 400;
    $id = Specific::Filter($_POST['id']);
    $user_id = Specific::Filter($_POST['user_id']);
    if (isset($id) && is_numeric($id)) {
    	$enrolled_programs = $dba->query('SELECT COUNT(*) FROM enrolled WHERE type = "course" AND user_id = '.$user_id.' AND program_id = '.$id)->fetchArray();
    	if($enrolled_programs == 0){
	        if($dba->query('INSERT INTO enrolled (user_id, course_id, program_id, type, status, `time`) VALUES ('.$user_id.', 0, '.$id.', "program", "registered",'.time().')')->returnStatus()){
	        	$deliver['status'] = 200;
	        } else {
	        	$deliver['status'] = 400;
	        }
	    }
    }
} else if($one == 'get-citems'){
    $id = Specific::Filter($_POST['id']);
    $type = Specific::Filter($_POST['type']);
    $pos = Specific::Filter($_POST['pos']);
    if(isset($id) && is_numeric($id)){
        if($type == 'notes'){
            $note = $dba->query('SELECT * FROM notes WHERE id = '.$id)->fetchArray();
            $id = $note['course_id'];
            $items = array();
            $course = $dba->query('SELECT * FROM courses WHERE id = '.$id)->fetchArray();
                $items['parameters'] = json_decode($course['parameters'], true);
                $items['notes'] = json_decode($note['notes'], true);
                if(isset($pos) && is_numeric($pos)){
                    $items['parameters'] = json_decode($course['parameters'], true)[$pos];
                    $notes = json_decode($note['notes'], true)[$pos];
                    $items['notes'] = $notes;
                }
            }
        if (!empty($items)) {
            $deliver['status'] = 200;
            $deliver['items'] = $items;
        }
    }
}
?>