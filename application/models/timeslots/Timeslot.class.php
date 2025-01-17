<?php

/**
 * Timeslot class
 *
 * @author Carlos Palma <chonwil@gmail.com>
 */
class Timeslot extends BaseTimeslot {

	/**
	 * Timeslot # for specific object
	 *
	 * @var integer
	 */
	protected $timeslot_num = null;
	
	protected $assigned_user = null;
	
	protected $rel_object = null;
	
	protected $force_recalculate_billing = false;
	
	function setForceRecalculateBilling($force) {
		$this->force_recalculate_billing = $force;
	}
	function getForceRecalculateBilling() {
		return $this->force_recalculate_billing;
	}
	
	/**
	 * Return object connected with this action
	 *
	 * @access public
	 * @param void
	 * @return ContentDataObject
	 */
	function getRelObject() {
		if(is_null($this->rel_object)) {
			$this->rel_object = Objects::findObject($this->getRelObjectId());
		}
		return $this->rel_object;
	} // getObject

	
	/**
	 * Return timeslot #
	 *
	 * @param void
	 * @return integer
	 */
	function getTimeslotNum(Timeslot $timeslot) {
		if(is_null($this->timeslot_num)) {
			$object = $this->getRelObject();
			$this->timeslot_num = $object instanceof ContentDataObject ? $object->getTimeslotNum($this) : 0;
		} // if
		return $this->timeslot_num;
	} // getTimeslotNum

	/**
    * Return user assigned to this timeslot
    *
    * @access public
    * @param void
    * @return User
    */
    function getUser() {
      if(is_null($this->assigned_user)) {
        $this->assigned_user = Contacts::findById($this->getContactId());
      }
      return $this->assigned_user;
    }
    
    function isOpen() {
    	return $this->getEndTime() == null;
    }
	
    function getMinutes(){
    	if (!$this->getStartTime())
    		return 0;
    		
    	$endTime = $this->getEndTime();
    	if (!$endTime)
    		$endTime = $this->isPaused() ? $this->getPausedOn() : DateTimeValueLib::now();
    	$timeDiff = DateTimeValueLib::get_time_difference($this->getStartTime()->getTimestamp(),$endTime->getTimestamp(), $this->getSubtract());
    	
    	return $timeDiff['days'] * 1440 + $timeDiff['hours'] * 60 + $timeDiff['minutes'];
    }

    function getSeconds(){
    	if (!$this->getStartTime())
    		return 0;
    		
    	$endTime = $this->getEndTime();
    	if (!$endTime)
    		if ($this->getPausedOn())
    			$endTime = $this->getPausedOn();
    		else
    			$endTime = DateTimeValueLib::now();
    	$timeDiff = DateTimeValueLib::get_time_difference($this->getStartTime()->getTimestamp(),$endTime->getTimestamp(), $this->getSubtract());
    	
    	return $timeDiff['days'] * 86400 + $timeDiff['hours'] * 3600  + $timeDiff['minutes']* 60 + $timeDiff['seconds'];
    }

    function getSecondsSincePause(){
    	if (!$this->getPausedOn())
    		return 0;
    		
    	$endTime = DateTimeValueLib::now();
    	$timeDiff = DateTimeValueLib::get_time_difference($this->getPausedOn()->getTimestamp(),$endTime->getTimestamp());
    	
    	return $timeDiff['days'] * 86400 + $timeDiff['hours'] * 3600  + $timeDiff['minutes']* 60 + $timeDiff['seconds'];
    }
    
    function isPaused(){
    	return $this->getPausedOn() != null;
    }
    
    function pause(){
    	if ($this->isPaused())
    		throw new Error('Timeslot is already paused');
    	$dt = DateTimeValueLib::now();
		$this->setPausedOn($dt);
    }
    
    function resume(){
    	if (!$this->isPaused())
    		throw new Error('Timeslot is not paused');
    	$dt = DateTimeValueLib::now();
    	$timeToSubtract = $dt->getTimestamp() - $this->getPausedOn()->getTimestamp();
		$this->setPausedOn(null);
		$this->setSubtract($this->getSubtract() + $timeToSubtract);

		$allOpenTimeslot = Timeslots::getAllOpenTimeslotByObjectByUser(logged_user());
		// Handle open timeslots
		$t_controller = new TimeslotController();
		foreach ($allOpenTimeslot as $time){
			if($time->getId() != $this->getId()){
				$t_controller->handle_open_timeslot($time);
			}
		 }
    }
    
    function close($description = null){
     
    	if ($this->isPaused()) {
    		$this->setEndTime($this->getPausedOn());
    	} else {
    	  	$dt = DateTimeValueLib::now();
			$this->setEndTime($dt);
    	}
        	
    	//Billing
		$user = Contacts::findById(array_var($timeslot_data, 'contact_id', logged_user()->getId()));
		$billing_category_id = $user->getDefaultBillingId();
		$bc = BillingCategories::findById($billing_category_id);
		if ($bc instanceof BillingCategory) {
			$this->setBillingId($billing_category_id);
			$hourly_billing = $bc->getDefaultValue();
			$this->setHourlyBilling($hourly_billing);
			$this->setFixedBilling(number_format($hourly_billing * $hours, 2));
			$this->setIsFixedBilling(false);
		}
		
		if ($description)
			$this->setDescription($description);
    }
    
    
	/**
	 * Return user who completed this timeslot
	 *
	 * @access public
	 * @param void
	 * @return User
	 */
	function getCompletedBy() {
		return $this->getUser();
	} // getCompletedBy
    
    // ---------------------------------------------------
	//  URLs
	// ---------------------------------------------------

	/**
	 * Return tag URL
	 *
	 * @param void
	 * @return string
	 */
	function getViewUrl() {
		$object = $this->getRelObject();
		return $object instanceof ContentDataObject ? $object->getObjectUrl() : '';
	} // getViewUrl

	/**
	 * Return add timeslot URL for specific object
	 *
	 * @param ContentDataObject $object
	 * @return string
	 */
	static function getOpenUrl(ContentDataObject $object) {
		return get_url('timeslot', 'open', array(
			'object_id' => $object->getId()
		));
	}
	
	static function getAddTimespanUrl(ContentDataObject $object) {
		return get_url('timeslot', 'add_timespan', array(
			'object_id' => $object->getId()
		));
	}
	
	/**
	 * Return close timeslot URL for specific object
	 *
	 * @param ContentDataObject $object
	 * @return string
	 */
	function getCloseUrl() {
		return get_url('timeslot', 'close', array(
			'id' => $this->getId()
		));
	}
	
	/**
	 * Return pause timeslot URL for specific object
	 *
	 * @param ContentDataObject $object
	 * @return string
	 */
	function getPauseUrl() {
		return get_url('timeslot', 'pause', array(
			'id' => $this->getId()
		));
	}
	
	/**
	 * Return resume timeslot URL for specific object
	 *
	 * @param ContentDataObject $object
	 * @return string
	 */
	function getResumeUrl() {
		return get_url('timeslot', 'resume', array(
			'id' => $this->getId()
		));
	}

	/**
	 * Return edit URL
	 *
	 * @param void
	 * @return string
	 */
	function getEditUrl() {
		return get_url('timeslot', 'edit', array('id' => $this->getId()));
	}

	/**
	 * Return delete URL
	 *
	 * @param void
	 * @return string
	 */
	function getDeleteUrl() {
		return get_url('timeslot', 'delete', array('id' => $this->getId()));
	}

	// ---------------------------------------------------
	//  Permissions
	// ---------------------------------------------------

	/**
	 * Can $user view this object
	 *
	 * @param User $user
	 * @return boolean
	 */
	function canView(Contact $user) {
		return can_read($user, $this->getMembers(), $this->getObjectTypeId());
		/*if ($this->getRelObject() instanceof ContentDataObject) {
			return can_read($user, $this->getRelObject()->getMembers(), $this->getRelObject()->getObjectTypeId());
		} else {
			return can_read($user, $this->getMembers(), $this->getObjectTypeId());
		}*/
	}

	/**
	 * Empty implementation of static method.
	 *
	 * Add tag permissions are done through ContentDataObject::canTimeslot() method. This
	 * will return timeslot permissions for specified object
	 *
	 * @param User $user
	 * @param array $context
	 * @return boolean
	 */
	function canAdd(Contact $user, $context, &$notAllowedMember = '') {
		return can_add($user, $context, Timeslots::instance()->getObjectTypeId(), $notAllowedMember );
	}

	/**
	 * Empty implementation of static method. Update tag permissions are check by the taggable
	 * object, not tag itself
	 *
	 * @param Contact $user
	 * @return boolean
	 */
	function canEdit(Contact $user) {
		return can_write($user, $this->getMembers(), $this->getObjectTypeId());
		//return ($user->getId() == $this->getContactId() || can_manage_time($user));
	}

	/**
	 * Empty implementation of static method. Update tag permissions are check by the taggable
	 * object, not tag itself
	 *
	 * @param Contact $user
	 * @return boolean
	 */
	function canDelete(Contact $user) {
		return can_delete($user, $this->getMembers(), $this->getObjectTypeId());
		//return ($user->getId() == $this->getContactId() || can_manage_time($user));
	}

	// ---------------------------------------------------
	//  System
	// ---------------------------------------------------

	/**
	 * Save the timeslot
	 * It requires a parameter, which is not compatible with ContentDataObject function save() (Which has no parameteres)
	 * Why modify the task when saving a timeslot? It should be modified in the calling function.
	 * 
	 * Commenting to see if we can replace all instances and simplify.
	 *
	 * @param $old_object_id It refers to the timeslot  
	 * @return boolean
	 */
	/**
	function save($old_object_id) {
		$is_new = $this->isNew();
		$saved = parent::save();
		if($saved) {
            if ($old_object_id){
                $old_task =  Objects::findObject($old_object_id);
                if($old_task instanceof ContentDataObject) {
                    $total_worked_time = $old_task->calculateTotalWorkedTime();
                    $old_task->saveTotalWorkedTime($total_worked_time,'total_worked_time');
                }
            }
		    
			$object = $this->getRelObject();
			if($object instanceof ContentDataObject) {
				if($is_new) {
					$object->onAddTimeslot($this);
				} else {
					$object->onEditTimeslot($this);
				}
			}
		}
		DB::execute("UPDATE ".TABLE_PREFIX."timeslots 
				SET worked_time=GREATEST(TIMESTAMPDIFF(MINUTE,start_time,end_time),0) - (subtract/60)
				WHERE object_id=".$this->getId());
		return $saved;
	}
	
	*/
	
	/**
	 * Save the timeslot
	 *
	 * @return boolean
	 */
	function save() {
	    $is_new = $this->isNew();
	    $saved = parent::save();
	    
	    if($saved) {	        
	        $object = $this->getRelObject();
	        if($object instanceof ContentDataObject) {
	            if($is_new) {
	                $object->onAddTimeslot($this);
	            } else {
	                $object->onEditTimeslot($this);
	            }
	        }
	    }
	    DB::execute("UPDATE ".TABLE_PREFIX."timeslots
				SET worked_time=(GREATEST(TIMESTAMPDIFF(SECOND,start_time,end_time),0) - subtract)/60
				WHERE object_id=".$this->getId());
	    return $saved;
	}

	/**
	 * Delete timeslot
	 *
	 * @param void
	 * @return null
	 */
	function delete() {
		$deleted = parent::delete();
		if($deleted) {
			$object = $this->getRelObject();
			if($object instanceof ContentDataObject) {
				$object->onDeleteTimeslot($this);
			}
		}
		return $deleted;
	}
	
	function trash($trashDate = NULL, $fire_hook = true) {
		parent::trash($trashDate, $fire_hook);

		$object = $this->getRelObject();
		if ($object instanceof ProjectTask){
			$object->calculatePercentComplete();
			Hook::fire('calculate_executed_cost_and_price', array(), $object);
		}
	}

	// ---------------------------------------------------
	//  ApplicationDataObject implementation
	// ---------------------------------------------------

	/**
	 * Return object name
	 *
	 * @param void
	 * @return string
	 */
	function getObjectName() {
		$object = $this->getRelObject();
		return $object instanceof ContentDataObject ? lang('timeslot on object', $object->getObjectName()) : lang($this->getObjectTypeName());
	}
	

	/**
	 * Return view tag URL
	 *
	 * @param void
	 * @return string
	 */
	function getObjectUrl() {
		return $this->getViewUrl();
	}

	function getArrayInfo($return_billing = false, $time_detail = false, $permissions_detail = false, $mem_path = true) {
		$task_name = '';
		
		$user = Contacts::findById($this->getContactId());
		if ($user instanceof Contact) {
			$displayname = $user->getObjectName();
		} else {
			$displayname = lang("n/a");
		}
		
		$general_info = $this->getObject()->getArrayInfo();
		
		$result = array(
			'id' => $this->getId(),
			'date' => $this->getStartTime()->getTimestamp(),
			'time' => $this->getSeconds(),
			'mids' => $this->getMemberIds(),
			'uid' => $this->getContactId(),
			'uname' => $displayname,
			'lastupdated' => $general_info['dateUpdated'],
			'lastupdatedby' => $general_info['updatedBy'],
			'otid' => Timeslots::instance()->getObjectTypeId(),
			'description' => $this->getDescription(),
			'rel_object_id' => $this->getRelObjectId(),
			'rel_object_name' => '',
			'worked_time' => $this->getColumnValue('worked_time'),
			'worked_hours' => round($this->getColumnValue('worked_time') / 60, 2),
		);
		
		if ($mem_path) {
			$result['memPath'] = json_encode($this->getMembersIdsToDisplayPath());
		}
		
		if ($time_detail) {
			$result['start_time'] = $this->getStartTime() instanceof DateTimeValue ? format_datetime($this->getStartTime()) : '';
			$result['start_time_ts'] = $this->getStartTime() instanceof DateTimeValue ? $this->getStartTime()->getTimestamp() : '';
			$result['end_time'] = $this->getEndTime() instanceof DateTimeValue ? format_datetime($this->getEndTime()) : '';
			if ($this->getEndTime() instanceof DateTimeValue) {
				$result['worked_time'] = DateTimeValue::FormatTimeDiff($this->getStartTime(), $this->getEndTime(), "hm", 60, $this->getSubtract());
			}
			$result['subtract'] = '';
			if ($this->getSubtract() > 0) {
				$now = DateTimeValueLib::now();
				$now_sub = DateTimeValueLib::now();
				$now_sub->add('s', $this->getSubtract());
				$result['subtract'] = DateTimeValue::FormatTimeDiff($now, $now_sub, "hm", 60, 0);
				$result['paused_time_sec'] = $this->getSubtract();
			}
			if ($this->getPausedOn() instanceof DateTimeValue) {
				$result['paused_on'] = format_datetime($this->getPausedOn());
				$result['paused_on_ts'] = $this->getPausedOn()->getTimestamp();
			}
		}
		if ($permissions_detail) {
			$result['can_edit'] = $this->canEdit(logged_user());
			$result['can_delete'] = $this->canDelete(logged_user());
		}
		if ($return_billing) {
			$result['is_fixed_billing'] = $this->getColumnValue('is_fixed_billing');
			$result['hourlybilling'] = $this->getHourlyBilling();
			$result['totalbilling'] = $this->getFixedBilling();
			$result['uses_modified_keys'] = true;
			$result['fixed_billing'] = "";
			if ($this->getFixedBilling() > 0) {
				$c = Currencies::getCurrency($this->getRateCurrencyId());
				$c_symbol = $c instanceof Currency ? $c->getSymbol() : '';
				$result['rate_currency_id'] = $this->getRateCurrencyId();
				$result['rate_currency_sym'] = $c_symbol;
				$result['fixed_billing'] = format_money_amount($this->getFixedBilling(), $c_symbol);
			}
		}
		
		if ($this->getDescription() != '')
			$result['desc'] = $this->getDescription();
			
		if ($task_name != '')
			$result['tn'] = $task_name;
		
		Hook::fire('timeslot_info_additional_data', $this, $result);
		
		return $result;
	}


	function getChangedRelations($old_content_object) {
		$changed_relations = array();

		// task
		if ($this->getRelObjectId() != $old_content_object->getRelObjectId()) {

			// log the relation added with the new task
			if ($this->getRelObjectId() > 0) {
				$changed_relations['relation_added'][] = $this->getRelObjectId();
			}
			// log the removed relation with the old task
			if ($old_content_object->getRelObjectId() > 0) {
				$changed_relations['relation_removed'][] = $old_content_object->getRelObjectId();
			}

		} else if ($this->getRelObjectId() > 0) {
			// only log that the related object to the task has been edited
			$changed_relations['relation_edited'][] = $this->getRelObjectId();
		}

		return $changed_relations;
	}



	
} // Timeslot

?>