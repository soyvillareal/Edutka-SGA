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
    $html = '';
    $query = '';
    if(!empty($keyword)){
        $query .= " WHERE name LIKE '%$keyword%'";
        if($type == 'courses'){
        	$enrolled_programs = $dba->query('SELECT program_id FROM enrolled WHERE user_id = '.$id.' AND type = "program"')->fetchAll(false);
        	$enrolled_programs = !empty($enrolled_programs) ? implode(',', $enrolled_programs) : 0;
        	$query .= " AND program_id IN (".$enrolled_programs.")";
        }
       	$searchs = $dba->query('SELECT * FROM '.$type.$query)->fetchAll();

       	$type = substr($type, 0, -1);
	    if (!empty($searchs)) {
	        foreach ($searchs as $search) {
	            if($type == 'course'){
					$periodc = $dba->query('SELECT name FROM periods p WHERE (SELECT period_id FROM courses WHERE id = '.$search['id'].' AND period_id = p.id)')->fetchArray();
					$TEMP['!name'] = "{$search['name']} ($periodc)";
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
				$html .= Specific::Maket("enroll/includes/enrolled");
	        }
	        Specific::DestroyMaket();
	        $deliver['status'] = 200;
	    } else {
            if(!empty($keyword)){
	            $TEMP['keyword'] = $keyword;
	   	        $html .= Specific::Maket('not-found/result-for');
	        } else {
	            $html .= Specific::Maket('not-found/enrolled');
	        }
	    }
    } else {
   		$TEMP['#enrolled'] = $dba->query('SELECT * FROM enrolled WHERE user_id = '.$id)->fetchAll();
        if(!empty($TEMP['#enrolled'])){
			foreach ($TEMP['#enrolled'] as $enroll) {
				if($enroll['type'] == 'course'){
					$course = $dba->query('SELECT name FROM courses WHERE id = '.$enroll['course_id'])->fetchArray();
					$periodc = $dba->query('SELECT name FROM periods p WHERE (SELECT period_id FROM courses WHERE id = '.$enroll['course_id'].' AND period_id = p.id)')->fetchArray();
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
			    $html .= Specific::Maket("enroll/includes/enrolled");
			}
			Specific::DestroyMaket();
		} else {
			$html .= Specific::Maket("not-found/enrolled");
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
    $id = Specific::Filter($_POST['id']);
    $user_id = Specific::Filter($_POST['user_id']);
    $code = Specific::Filter($_POST['code']);

    if (isset($id) && is_numeric($id)) {
    	$enrolled_courses = $dba->query('SELECT COUNT(*) FROM enrolled WHERE user_id = '.$user_id.' AND course_id = '.$id)->fetchArray();
    	if($enrolled_courses == 0){
	    	if(Specific::Academic() == true || !empty($code)){
	    		$courses = $dba->query('SELECT * FROM courses WHERE id = "'.$id.'"')->fetchArray();
		        if(Specific::Academic() == true || $courses['code'] === $code){
		        	if($dba->query('INSERT INTO enrolled (user_id, course_id, program_id, type, status, `time`) VALUES ('.$user_id.', '.$id.', 0, "course", "registered",'.time().')')->returnStatus()){
		        		$deliver['status'] = 200;
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
	    	$enrolled_courses = $dba->query('SELECT * FROM enrolled WHERE user_id = '.$user_id.' AND course_id = '.$id)->fetchArray();
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
    	$enrolled_programs = $dba->query('SELECT COUNT(*) FROM user_id = '.$user_id.' AND enrolled WHERE program_id = '.$id)->fetchArray();
    	if(count($enrolled_programs) == 0){
	        if($dba->query('INSERT INTO enrolled (user_id, course_id, program_id, type, status, `time`) VALUES ('.$user_id.', 0, '.$id.', "program", "registered",'.time().')')->returnStatus()){
	        	$deliver['status'] = 200;
	        } else {
	        	$deliver['status'] = 400;
	        }
	    }
    }
}
?>