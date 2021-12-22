<?php
if ($TEMP['#loggedin'] === false && (Specific::Admin() === false || Specific::Academic() === false || Specific::Student() === false)) {
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
        	$courses = $dba->query('SELECT id FROM courses c WHERE (SELECT id FROM plan WHERE program_id = '.$program_id.' AND id = c.plan_id) = plan_id')->fetchAll(false);
        	if(!empty($courses)){
        		$query .= " AND id IN (".implode(',', $courses).")";
        	}
        }
       	$searchs = $dba->query('SELECT * FROM '.$type.'s'.$query)->fetchAll();

	    if (!empty($searchs)) {
	    	$period_id = $dba->query('SELECT id FROM periods WHERE status = "enabled" AND start < '.time().' AND final > '.time())->fetchArray();
	        foreach ($searchs as $search) {
	            if($type == 'course'){
					$periodc = $dba->query('SELECT name FROM periods p WHERE (SELECT period_id FROM enrolled WHERE type = "course" AND user_id = '.$id.' AND course_id = '.$search['id'].' AND period_id = p.id) = id')->fetchArray();
					$teachers = $dba->query('SELECT names FROM users u WHERE (SELECT user_id FROM teacher WHERE user_id = u.id AND course_id = '.$search['id'].' AND period_id = '.$period_id.') = id')->fetchAll(false);
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
					$TEMP['!name'] = "{$search['name']} ".(!is_array($periodc) ? "($periodc)" : "");
					$TEMP['!plan_id'] = $search['plan_id'];
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
					$periodc = $dba->query('SELECT name FROM periods p WHERE (SELECT period_id FROM enrolled WHERE course_id = '.$enroll['course_id'].' AND period_id = p.id) = id')->fetchArray();
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
					$TEMP['!name'] = "$course ".(!is_array($periodc) ? "($periodc)" : "");
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
					if(Specific::Admin() == false || Specific::Academic() == false){
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
    $plan_id = Specific::Filter($_POST['plan_id']);
    $code = Specific::Filter($_POST['code']);

    if (isset($course_id) && is_numeric($course_id)) {
    	$enrolled_courses = $dba->query('SELECT COUNT(*) FROM enrolled WHERE type = "course" AND user_id = '.$user_id.' AND course_id = '.$course_id)->fetchArray();
    	if($enrolled_courses == 0){
	    	if(Specific::Admin() == true || Specific::Academic() == true || !empty($code)){
	    		$courses = $dba->query('SELECT * FROM courses WHERE id = "'.$course_id.'"')->fetchArray();
		        if(Specific::Admin() == true || Specific::Academic() == true || $courses['code'] === $code){
		        	$prektrues = array();
		        	$period = $dba->query('SELECT id, COUNT(*) AS count FROM periods WHERE status = "enabled" AND start < '.time().' AND final > '.time())->fetchArray();
		        	$plan = $dba->query('SELECT note_mode, program_id, COUNT(*) AS count FROM plan WHERE id = '.$plan_id)->fetchArray();
		        	if(!empty($courses['preknowledge'])){
		        		$preknowledges = explode(',', $courses['preknowledge']);
			        	foreach ($preknowledges as $prek) {
			        		$parameters = $dba->query('SELECT parameters FROM parameter WHERE course_id = '.$prek.' AND period_id = '.$period['id'])->fetchArray();
			        		$courset = $dba->query('SELECT type FROM courses WHERE id = '.$prek)->fetchArray();
			        		$notes = $dba->query('SELECT notes FROM notes WHERE user_id = '.$user_id.' AND course_id = '.$prek)->fetchArray();
			        		$notes = json_decode($notes, true);

			        		if($plan['note_mode'] == '30-30-40'){
							    for ($i=0; $i < 3; $i++) { 
							    	$anotes = array();
								    $rnotes = json_decode($notes[$i], true);
							        $parameters = json_decode($parameters, true)[$i];
							        foreach ($parameters as $key => $param) {
							        	$anotes[] = (($rnotes[$key]/100)*$param['percent']);
							        }
							        $notes[$i] = array_sum($anotes);
							    }
							    $average = round((($notes[0]*0.3)+($notes[1]*0.3)+($notes[2]*0.4)), 2);
						    } else {
						    	$anotes = array();
							    $parameters = json_decode($parameters, true);
							    foreach ($parameters as $key => $param) {
							      	$anotes[] = (($notes[$key]/100)*$param['percent']);
							    }
							    $notes = array_sum($anotes);
						    	$average = round($notes, 2);
						    }

						    $prektrues[] = ($courset == 'practice' && $average >= 3.5) || ($courset == 'theoretical' && $average >= 3.0) ? true : false;
			        	}
		        	}
		        	if(!in_array(false, $prektrues) || empty($prektrues)){
			        	if($plan['count'] > 0){
			        		if($dba->query('SELECT COUNT(*) FROM courses WHERE id = '.$course_id.' AND plan_id = '.$plan_id)->fetchArray() > 0){
			        			if($period['count'] > 0){
			        				if($dba->query('INSERT INTO enrolled (period_id, user_id, course_id, program_id, type, status, `time`) VALUES ('.$period['id'].', '.$user_id.', '.$course_id.', '.$plan['program_id'].', "course", "registered",'.time().')')->returnStatus() && $dba->query('INSERT INTO notes (user_id, course_id, program_id, notes, `time`) VALUES ('.$user_id.','.$course_id.', '.$plan['program_id'].', "'.json_encode(array(0.0, 0.0, 0.0)).'",'.time().')')->returnStatus()){
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
						        	} else {
						        		$deliver = array(
						        			'status' => 400,
						        			'error' => $TEMP['#word']['error']
						        		);
						        	}
			        			} else {
			        				$deliver = array(
			        					'status' => 400,
			        					'error' => $TEMP['#word']['there_no_active_period_moment']
			        				);
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