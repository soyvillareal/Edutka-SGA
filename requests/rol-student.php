<?php
if ($TEMP['#loggedin'] === true && (Specific::Admin() === true || Specific::Academic() === true || Specific::Student() === true)) {
	if($one == 'search-enrolled'){
		$keyword = Specific::Filter($_POST['keyword']);
		$id = Specific::Filter($_POST['id']);
		$type = Specific::Filter($_POST['typet']);
		$program_id = Specific::Filter($_POST['program_id']);
		$working_day = Specific::Filter($_POST['working_day']);
		$status = Specific::Filter($_POST['status']);
	    $html = '';
		$query = '';
		$sql = '';
		$TEMP['#enrolled'] = false;
		$programs = $dba->query('SELECT program_id FROM enrolled WHERE type = "program" AND user_id = '.$id)->fetchAll(false);
		
		if($status == 'registered'){
			if($type == 'course'){
				$plan_id = $dba->query('SELECT id FROM plan WHERE program_id = '.$program_id)->fetchArray();
				if(!empty($program_id)){
					$sql .= ' AND program_id = '.$program_id;
				}
			    if(!empty($keyword)){
			    	if(in_array($working_day, array('daytime', 'nightly'))){
				        $sql .= ' AND schedule = "'.$working_day.'"';
				    }
			    	$query = " AND (SELECT id FROM courses WHERE (id LIKE '%$keyword%' OR name LIKE '%$keyword%')".$sql." AND id = e.course_id) = course_id";
			    } else {
			    	$query = $sql;
			    	if(in_array($working_day, array('daytime', 'nightly'))){
			    		$query .= " AND (SELECT id FROM courses WHERE schedule = '".$working_day."' AND id = e.course_id) = course_id";
			    	}
			    }
			} else {	
				if(!empty($keyword)){
					$query = " AND (SELECT id FROM programs WHERE (id LIKE '%$keyword%' OR name LIKE '%$keyword%')".$sql." AND id = e.program_id) = program_id";
				}
			}
			$searchs = $dba->query('SELECT * FROM enrolled e WHERE user_id = '.$id.' AND type = "'.$type.'"'.$query)->fetchAll();
							$deliver['XD'] = 'XD2';
			if(!empty($searchs)){
				$TEMP['#enrolled'] = true;
				foreach ($searchs as $enroll) {
					if($enroll['type'] == 'course'){
						$estatus = $dba->query('SELECT status FROM enrolled e WHERE user_id = '.$id.' AND type = "program" AND program_id = '.$program_id)->fetchArray();
						$course = $dba->query('SELECT name FROM courses WHERE id = '.$enroll['course_id'])->fetchArray();
						$periodc = $dba->query('SELECT name, final FROM periods WHERE id = '.$enroll['period_id'])->fetchArray();

						$teachers = $dba->query('SELECT names FROM users u WHERE (SELECT user_id FROM teacher WHERE user_id = u.id AND course_id = '.$enroll['course_id'].' AND period_id = '.$enroll['period_id'].') = id')->fetchAll(false);
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

						$TEMP['!name'] = "$course  ({$periodc['name']})";
						$TEMP['!color'] = 'purple';
						$TEMP['!plan_id'] = $plan_id;
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
						if(Specific::Student() == true){
							$TEMP['!class_event'] = 'cursor-disabled" disabled';
						}
					} else {
						$TEMP['!class_event'] = 'cursor-disabled" disabled';
						if(time() < $periodc['final'] || Specific::Academic() == true || Specific::Admin() == true || $type == 'program'){
							$TEMP['!class_event'] = 'show_cmodal"';
						}
					}	
					if($type == 'course'){
						if($estatus == 'cancelled'){
							$TEMP['!class_event'] = 'cursor-disabled" disabled';
						}
					}
					$TEMP['!status'] = $enroll['status'];
					$TEMP['!type'] = "{$TEMP['#word'][$enroll['type']]} ({$TEMP['#word'][$enroll['status']]})";
				    $TEMP['!typet'] = $enroll['type'];
				    $TEMP['!time'] = Specific::DateFormat($enroll['time']);
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
			if($type == 'course'){
				if(in_array($program_id, $programs)){
					if(!empty($keyword)){
						$query = " AND (id LIKE '%$keyword%' OR name LIKE '%$keyword%')";
					}
					$plan_id = $dba->query('SELECT id FROM plan WHERE program_id = '.$program_id)->fetchArray();
					if(in_array($working_day, array('daytime', 'nightly'))){
				       	$query .= ' AND schedule = "'.$working_day.'"';
				    }
				    if($status == 'without_registering'){
				    	$enrolled = $dba->query('SELECT course_id FROM enrolled WHERE user_id = '.$id.' AND type = "course" AND program_id = '.$program_id)->fetchAll(false);
				    	if(!empty($enrolled)){
				    		$query .= ' AND id NOT IN ('.implode(',', $enrolled).')';
				    	}
				    }
					$courses = $dba->query("SELECT * FROM courses c WHERE (SELECT course_id FROM curriculum WHERE plan_id = ".$plan_id." AND course_id = c.id) = id".$query)->fetchAll();
					if(!empty($courses)){
						foreach ($courses as $course) {
							$approved = false;
							$enrolled = $dba->query('SELECT COUNT(*) FROM enrolled e WHERE user_id = '.$id.' AND type = "course" AND course_id = '.$course['id'].' AND (SELECT id FROM periods WHERE status = "enabled" AND start < '.time().' AND final > '.time().' AND id =e.period_id) = period_id AND program_id = '.$program_id)->fetchArray();
							$enroll = $dba->query('SELECT * FROM enrolled WHERE user_id = '.$id.' AND type = "course" AND course_id = '.$course['id'].' AND program_id = '.$program_id)->fetchArray();
							$estatus = $dba->query('SELECT status FROM enrolled WHERE user_id = '.$id.' AND type = "program" AND program_id = '.$program_id)->fetchArray();

							if($status == 'registered' || $status == 'reprobate'){
								if(!empty($enroll)){
									$note = $dba->query('SELECT * FROM notes WHERE user_id = '.$enroll['user_id'].' AND course_id = '.$enroll['course_id'].' AND period_id = '.$enroll['period_id'].' AND program_id = '.$program_id)->fetchArray();
									$parameters = $dba->query('SELECT parameters FROM parameter p WHERE (SELECT id FROM teacher WHERE course_id = '.$note['course_id'].' AND period_id = '.$note['period_id'].' AND id = p.teacher_id) = teacher_id')->fetchArray();
									if(!empty($parameters)){
										$notes = json_decode($note['notes'], true);
										$qualification = $dba->query('SELECT note, status, COUNT(*) as count FROM qualification WHERE note_id = '.$note['id'])->fetchArray();
										$note_mode = $dba->query('SELECT note_mode FROM plan WHERE program_id = '.$program_id)->fetchArray();
										   if($note_mode == '30-30-40'){
										   	for ($i=0; $i < 3; $i++) { 
										    	$anotes = array();
										        $params = json_decode($parameters, true)[$i];
										        foreach ($params as $key => $param) {
										        	$anotes[] = (($notes[$i][$key]/100)*$param['percent']);
										        }
										        $notes[$i] = array_sum($anotes);
											}
										    $average = $qualification['note'];
										    if($qualification['note'] == NULL){
										    	$average = round((($notes[0]*0.3)+($notes[1]*0.3)+($notes[2]*0.4)), 2);
												if($average >= 0 && $notes[2] < $TEMP['#nmtc']){
											    	$average = round($notes[2], 2);
											    }
											}
										} else {
										    $anotes = array();
										    $parameters = json_decode($parameters, true);
										    foreach ($parameters as $key => $param) {
										      	$anotes[] = (($notes[$key]/100)*$param['percent']);
										    }
											$notes = array_sum($anotes);

											$average = $qualification['note'];
											if($qualification['note'] == NULL){
											    $average = round($notes, 2);
											}
										}

										$approved = ($course['type'] == 'practice' && $average >= $TEMP['#nmcnt']) || ($course['type'] == 'theoretical' && $average >= $TEMP['#nmct']) ? true : false;
									}
								}
							}

							if(($status == 'registered' && !empty($enroll)) || ($status == 'without_registering' && empty($enroll)) || ($status == 'reprobate' && !empty($enroll) && $approved == false && $enrolled == 0)){
								$TEMP['!id'] = $course['id'];
								$TEMP['!color'] = 'purple';
								$TEMP['!plan_id'] = $plan_id;
								$TEMP['!name'] = $course['name'];
								if(($status == 'registered' && !empty($enroll)) || ($status == 'reprobate' && !empty($enroll) && $approved == false)){
									$teachers = $dba->query('SELECT names FROM users u WHERE (SELECT user_id FROM teacher WHERE user_id = u.id AND course_id = '.$course['id'].' AND period_id = '.$enroll['period_id'].') = id')->fetchAll(false);
									$TEMP['#enrolled'] = true;
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
									$periodc = $dba->query('SELECT name, final FROM periods WHERE id = '.$enroll['period_id'])->fetchArray();
									$TEMP['!name'] = "{$course['name']} ({$periodc['name']})";
									$TEMP['!text'] = $TEMP['#word']['enroll'];
									$TEMP['!class_event'] = 'show_rcmodal"';
									if($approved == true){
										$TEMP['!text'] = $TEMP['#word']['cancel'];
										$TEMP['!class_event'] = 'cursor-disabled" disabled';
										if(time() < $periodc['final'] || Specific::Academic() == true || Specific::Admin() == true){
											$TEMP['!class_event'] = 'show_cmodal"';
										}
									} else if($status != 'reprobate'){
										if($enroll['status'] == 'cancelled'){
											if(Specific::Student() == true){
												$TEMP['!class_event'] = 'cursor-disabled" disabled';
											}
										} else {
											$TEMP['!text'] = $TEMP['#word']['cancel'];
											$TEMP['!class_event'] = 'cursor-disabled" disabled';
											if(time() < $periodc['final'] || Specific::Academic() == true || Specific::Admin() == true){
												$TEMP['!text'] = $TEMP['#word']['cancel'];
												$TEMP['!class_event'] = 'show_cmodal"';
											}
										}
									}
									if($estatus == 'cancelled'){
										$TEMP['!class_event'] = 'cursor-disabled" disabled';
									}
								    $TEMP['!status'] = $enroll['status'];
								    $TEMP['!type'] = "{$TEMP['#word'][$enroll['type']]} ({$TEMP['#word'][$enroll['status']]})";
									$TEMP['!typet'] = $enroll['type'];
								    $TEMP['!time'] = Specific::DateFormat($enroll['time']);
								} else {
									$TEMP['!status'] = "";
								    $TEMP['!type'] = "{$TEMP['#word'][$type]}";
									$TEMP['!typet'] = $type;
								    $TEMP['!time'] = "";
									$TEMP['!text'] = $TEMP['#word']['enroll'];
									$TEMP['!class_event'] = 'show_rcmodal"';	
									if($estatus == 'cancelled'){
										$TEMP['!class_event'] = 'cursor-disabled" disabled';
									}
								}
								$html .= Specific::Maket("enroll/includes/enroll-list");
							} else {
								if(empty($html)){
									if(!empty($keyword)){
									    $TEMP['keyword'] = $keyword;
									   	$html .= Specific::Maket('not-found/result-for');
									} else {
								        $html .= Specific::Maket('not-found/enroll');
									}
								}
							}
						}
					} else {
						if(!empty($keyword)){
						    $TEMP['keyword'] = $keyword;
						   	$html .= Specific::Maket('not-found/result-for');
						} else {
					        $html .= Specific::Maket('not-found/enroll');
						}
					}
					Specific::DestroyMaket();
				   	$deliver['status'] = 200;
				}
			} else {
				if($status == 'without_registering'){
					$query = " AND id NOT IN (".implode(',', $programs).")";
				}
				$programs = $dba->query("SELECT * FROM programs WHERE (id LIKE '%$keyword%' OR name LIKE '%$keyword%')".$query)->fetchAll();

				if(!empty($programs)){
					foreach ($programs as $program) {
						
						$enroll = $dba->query('SELECT * FROM enrolled WHERE user_id = '.$id.' AND type = "program" AND program_id = '.$program['id'])->fetchArray();	
						if($status == 'registered'){
							$TEMP['#enrolled'] = true;
							$TEMP['!name'] = $program['name'];
							$TEMP['!color'] = 'green';
							$TEMP['!typet'] = 'program';
							if($enroll['status'] == 'registered'){
								$TEMP['!id'] = $enroll['id'];
								$TEMP['!text'] = $TEMP['#word']['cancel'];
							} else {
								$TEMP['!id'] = $enroll['program_id'];
								$TEMP['!text'] = $TEMP['#word']['enroll'];
							}
							$TEMP['!class_event'] = 'show_rpmodal"';
							if($enroll['status'] == 'cancelled'){
								if(Specific::Student() == true){
									$TEMP['!class_event'] = 'cursor-disabled" disabled';
								}
							} else {
								$TEMP['!class_event'] = 'cursor-disabled" disabled';
								$TEMP['!class_event'] = 'show_cmodal"';
							}
							$TEMP['!status'] = $enroll['status'];
						    $TEMP['!type'] = "{$TEMP['#word'][$enroll['type']]} ({$TEMP['#word'][$enroll['status']]})";
						    $TEMP['!time'] = Specific::DateFormat($enroll['time']);
						    $html .= Specific::Maket("enroll/includes/enroll-list");
						} else {
							if(empty($enroll)){
								$TEMP['!name'] = $program['name'];
								$TEMP['!color'] = 'green';
								$TEMP['!typet'] = 'program';

								$TEMP['!id'] = $program['id'];
								$TEMP['!text'] = $TEMP['#word']['enroll'];
								$TEMP['!type'] = $TEMP['#word'][$type];
								$TEMP['!class_event'] = 'show_rpmodal"';
							    unset($TEMP['!status']);
							    $html .= Specific::Maket("enroll/includes/enroll-list");
							} else {
								if(empty($html)){
									if(!empty($keyword)){
									    $TEMP['keyword'] = $keyword;
									   	$html .= Specific::Maket('not-found/result-for');
									} else {
								        $html .= Specific::Maket('not-found/enroll');
									}
								}
							}
						}
					}
				} else {
					if(!empty($keyword)){
					    $TEMP['keyword'] = $keyword;
					   	$html .= Specific::Maket('not-found/result-for');
					} else {
					    $html .= Specific::Maket('not-found/enroll');
					}
				}
				Specific::DestroyMaket();
				$deliver['status'] = 200;
			} 
		}
		$deliver['html'] = $html;
	} else if($one == 'cancel-enroll'){
	    $id = Specific::Filter($_POST['id']);
	    $user_id = Specific::Filter($_POST['user_id']);
	    $type = Specific::Filter($_POST['typet']);
	    if (isset($id) && is_numeric($id) && !empty($user_id) && is_numeric($user_id) && !empty($type)) {
	    	if(Specific::IsOwner($user_id) || Specific::Academic() == true || Specific::Admin() == true){
		    	if($type == 'program'){
		    		if(Specific::Teacher() == false){
				        if($dba->query('UPDATE enrolled e SET status = "cancelled" WHERE user_id = '.$user_id.' AND (SELECT program_id FROM enrolled WHERE id = '.$id.' AND program_id = e.program_id) = program_id')->returnStatus()){
				            $deliver['status'] = 200;
				        }
				    }
		    	} else {
		    		$final = $dba->query('SELECT final FROM periods WHERE (SELECT period_id FROM enrolled WHERE id = '.$id.') = id')->fetchArray();
		    		if(time() < $final || Specific::Academic() == true || Specific::Admin() == true){
				        if($dba->query('UPDATE enrolled SET status = "cancelled" WHERE id = '.$id)->returnStatus()){
				            $deliver['status'] = 200;
				        }
				    }
		    	}
			}
	    }
	} else if($one == 'register-ecnroll'){
	    $course_id = Specific::Filter($_POST['course_id']);
	    $user_id = Specific::Filter($_POST['user_id']);
	    $plan_id = Specific::Filter($_POST['plan_id']);
	    $period_id = Specific::Filter($_POST['period_id']);
	    $code = Specific::Filter($_POST['code']);

	    if (isset($course_id) && is_numeric($course_id)) {
	    	if(empty($period_id)){
				$period_id = $dba->query('SELECT id FROM periods WHERE status = "enabled" AND start < '.time().' AND final > '.time())->fetchArray();
			}
	    	$enrolled_courses = $dba->query('SELECT COUNT(*) FROM enrolled WHERE type = "course" AND user_id = '.$user_id.' AND course_id = '.$course_id.' AND period_id = '.$period_id)->fetchArray();
	    	$estatus = $dba->query('SELECT status FROM enrolled e WHERE user_id = '.$user_id.' AND type = "program" AND (SELECT program_id FROM plan WHERE id = '.$plan_id.' AND program_id = e.program_id) = program_id')->fetchArray();
	    	if($enrolled_courses == 0 && $estatus == 'registered'){
		    	if(Specific::Admin() == true || Specific::Academic() == true || !empty($code)){
		    		$course = $dba->query('SELECT * FROM courses WHERE id = "'.$course_id.'"')->fetchArray();
				    if(($course['quota']-1) > 0){
				        if(Specific::Admin() == true || Specific::Academic() == true || $course['code'] === $code){
				        	$prektrues = array();
				        	$plan = $dba->query('SELECT note_mode, program_id, COUNT(*) AS count FROM plan WHERE id = '.$plan_id)->fetchArray();
				        	if(!empty($course['preknowledge'])){
				        		$preknowledges = explode(',', $course['preknowledge']);
					        	foreach ($preknowledges as $prek) {
					        		$teacher_id = $dba->query('SELECT id FROM teacher t WHERE course_id = '.$prek.' AND (SELECT period_id FROM enrolled WHERE type = "course" AND user_id = '.$user_id.' AND course_id = '.$prek.' AND period_id = t.period_id) = period_id')->fetchArray();
					        		if(!empty($teacher_id)){
						        		$parameters = $dba->query('SELECT parameters FROM parameter WHERE teacher_id = '.$teacher_id)->fetchArray();
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
				        	}
				        	if(!in_array(false, $prektrues) || empty($prektrues)){
					        	if($plan['count'] > 0){
					        		if($dba->query('SELECT COUNT(*) FROM courses c WHERE id = '.$course_id.' AND (SELECT course_id FROM curriculum WHERE plan_id = '.$plan_id.' AND course_id = c.id) = id')->fetchArray() > 0){
					        			if(!empty($period_id)){
					        				if($dba->query('INSERT INTO enrolled (period_id, user_id, course_id, program_id, type, status, `time`) VALUES ('.$period_id.', '.$user_id.', '.$course_id.', '.$plan['program_id'].', "course", "registered",'.time().')')->returnStatus() && $dba->query('INSERT INTO notes (user_id, course_id, period_id, program_id, notes, `time`) VALUES ('.$user_id.','.$course_id.', '.$period_id.', '.$plan['program_id'].', "'.json_encode(array(0.0, 0.0, 0.0)).'",'.time().')')->returnStatus()){
								        		$deliver['status'] = 200;
								        		$teachers = $dba->query('SELECT user_id FROM teacher WHERE course_id = '.$course_id)->fetchAll(false);
								        		foreach ($teachers as $teacher) {
								        			Specific::SendNotification(array(
									                    'from_id' => $user_id,
									                    'to_id' => $teacher,
									                    'course_id' => $course_id,
									                    'type' => "'enroll'",
									                    'time' => time()
									                ));
								        		}
								        	}
					        			} else {
					        				$deliver = array(
					        					'status' => 400,
					        					'error' => $TEMP['#word']['there_no_active_period_moment']
					        				);
					        			}
						        	}
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
				        	'error' => $TEMP['#word']['there_no_seats_available_this_course']
				       	);
				    }
		    	} else {
		    		$deliver = array(
			       		'status' => 400,
			       		'error' => $TEMP['#word']['please_enter_code_this_course']
			       	);
		    	}
		    } else {
		    	$enrolled_courses = $dba->query('SELECT * FROM enrolled WHERE user_id = '.$user_id.' AND course_id = '.$course_id)->fetchArray();
		    	if(!empty($enrolled_courses) && $enrolled_courses['status'] == 'cancelled'){
		    		$query = '';
		    		if(Specific::Admin() == true || Specific::Academic() == true){
		    			$query = ', period_id = '.$period_id;
		    		}
		    		if($dba->query('UPDATE enrolled SET status = "registered"'.$query.' WHERE id = '.$enrolled_courses['id'])->returnStatus()){
		    			$deliver['status'] = 200;
		    		}
		    	}
		    }
	    }
	} else if($one == 'register-epnroll'){
	    $id = Specific::Filter($_POST['id']);
	    $user_id = Specific::Filter($_POST['user_id']);
	    if (isset($id) && is_numeric($id)) {
	    	$period = $dba->query('SELECT id FROM periods WHERE status = "enabled" AND start < '.time().' AND final > '.time())->fetchArray();
	    	$enrolled_programs = $dba->query('SELECT COUNT(*) FROM enrolled WHERE type = "course" AND user_id = '.$user_id.' AND program_id = '.$id)->fetchArray();
	    	if($enrolled_programs == 0){
		        if($dba->query('INSERT INTO enrolled (user_id, period_id, program_id, type, status, `time`) VALUES ('.$user_id.', '.$period.', '.$id.', "program", "registered",'.time().')')->returnStatus()){
		        	$deliver['status'] = 200;
		        }
		    } else {
		    	if(Specific::Academic() == true || Specific::Admin() == true){
		    		if($dba->query('UPDATE enrolled SET status = "registered" WHERE user_id = '.$user_id.' AND program_id = '.$id)->returnStatus()){
					    $deliver['status'] = 200;
					}
		    	}
		    }
	    }
	} else if($one == 'get-citems'){
	    $id = Specific::Filter($_POST['id']);
	    $period_id = Specific::Filter($_POST['period_id']);
	    $type = Specific::Filter($_POST['type']);
	    $pos = Specific::Filter($_POST['pos']);
	    if(!empty($id) && is_numeric($id) && !empty($period_id) && is_numeric($period_id)){
	        if($type == 'notes'){
	            $note = $dba->query('SELECT * FROM notes WHERE id = '.$id)->fetchArray();
	            $id = $note['course_id'];
	            $items = array();
				$parameters = $dba->query('SELECT parameters FROM parameter p WHERE (SELECT id FROM teacher WHERE course_id = '.$id.' AND period_id = '.$period_id.' AND id = p.teacher_id) = teacher_id')->fetchArray();
	            $items['parameters'] = json_decode($parameters, true);
	            $items['notes'] = json_decode($note['notes'], true);
	            if(isset($pos) && is_numeric($pos)){
	                $items['parameters'] = json_decode($parameters, true)[$pos];
	                $notes = json_decode($note['notes'], true)[$pos];
	                $items['notes'] = $notes;
	            }
	        }
	        if (!empty($items)) {
	            $deliver['status'] = 200;
	            $deliver['items'] = $items;
	        }
	    }
	} else if($one == 'get-settings'){
		$id = Specific::Filter($_POST['id']);
		$note_id = Specific::Filter($_POST['note_id']);

		if(!empty($id) && is_numeric($id) && !empty($note_id) && is_numeric($note_id)){
			if(Specific::IsOwner($id)){
				$cellphone = $dba->query('SELECT cellphone FROM users WHERE id = '.$id)->fetchArray();
				$qualification_exists = $dba->query('SELECT COUNT(*) FROM qualification WHERE note_id = '.$note_id)->fetchArray();

				if($qualification_exists == 0){
					$qualification = $dba->query('SELECT qualification FROM courses c WHERE (SELECT course_id FROM notes WHERE id = '.$note_id.' AND course_id = c.id) = id')->fetchArray();
					if($qualification == 'activated'){
						$deliver = array(
							'status' => 200,
							'cell' => $cellphone
						);
					}
				}
			}
		}
	} else if($one == 'request-qualifications'){
		$id = Specific::Filter($_POST['id']);
		$note_id = Specific::Filter($_POST['note_id']);
		$period_id = Specific::Filter($_POST['period_id']);

		if(!empty($id) && is_numeric($id) && !empty($note_id) && is_numeric($note_id)){
			if(Specific::IsOwner($id)){
				$final = $dba->query('SELECT final FROM periods WHERE id = '.$period_id)->fetchArray();
				if(Specific::ValidateDates($period_id, 17, 2) == true && Specific::ValidateDates($period_id, 12) == false && time() < $final){
					$cellphone = $dba->query('SELECT cellphone FROM users WHERE id = '.$id)->fetchArray();
					$qualification_exists = $dba->query('SELECT COUNT(*) FROM qualification WHERE note_id = '.$note_id)->fetchArray();

					if($qualification_exists == 0){
						$course = $dba->query('SELECT id, qualification FROM courses c WHERE (SELECT course_id FROM notes WHERE id = '.$note_id.' AND course_id = c.id) = id')->fetchArray();
						if(!empty($cellphone) && $course['qualification'] == 'activated'){
							if($dba->query('INSERT INTO qualification (user_id, note_id, `time`) VALUES('.$id.', '.$note_id.', '.time().')')->returnStatus()){
								$deliver['status'] = 200;
								$users = $dba->query('SELECT id FROM users WHERE role = "admin" OR role = "academic"')->fetchAll(false);
							    foreach ($users as $user) {
									Specific::SendNotification(array(
									    'from_id' => $TEMP['#user']['id'],
									    'to_id' => $user,
									    'course_id' => $course['id'],
									    'type' => "'req_qualification'",
									    'time' => time()
									));
								}
							}
						}
					}
				}
			}
		}
	}
}
?>