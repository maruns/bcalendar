<?php
/**
 * eGroupWare - Calendar's shared base-class of all UI classes
 *
 * @link http://www.egroupware.org
 * @package bcalendar
 * @author Ralf Becker <RalfBecker-AT-outdoor-training.de>
 * @copyright (c) 2004-9 by RalfBecker-At-outdoor-training.de
 * @license http://opensource.org/licenses/gpl-license.php GPL - GNU General Public License
 * @version $Id: class.bcalendar_ui.inc.php 38786 2012-04-04 13:58:16Z ralfbecker $
 */

/**
 * Shared base-class of all bcalendar UserInterface classes
 *
 * It manages eg. the state of the controls in the UI and generated the bcalendar navigation (sidebox-menu)
 *
 * The new UI, BO and SO classes have a strikt definition, in which time-zone they operate:
 *  UI only operates in user-time, so there have to be no conversation at all !!!
 *  BO's functions take and return user-time only (!), they convert internaly everything to servertime, because
 *  SO operates only on server-time
 *
 * All permanent debug messages of the bcalendar-code should done via the debug-message method of the bocal class !!!
 */
class bcalendar_ui
{
	/**
	 * @var $debug mixed integer level or string function-name
	 */
	var $debug=false;
	/**
	 * instance of the bocal or bocalupdate class
	 *
	 * @var bcalendar_boupdate
	 */
	var $bo;
	/**
	 * instance of jscalendar
	 *
	 * @var jscalendar
	 */
	var $jscal;
	/**
	 * Reference to global datetime class
	 *
	 * @var egw_datetime
	 */
	var $datetime;
	/**
	 * Instance of categories class
	 *
	 * @var categories
	 */
	var $categories;
	/**
	 * Reference to global uiaccountsel class
	 *
	 * @var uiaccountsel
	 */
	var $accountsel;
	/**
	 * @var array $common_prefs reference to $GLOBALS['egw_info']['user']['preferences']['common']
	 */
	var $common_prefs;
	/**
	 * @var array $cal_prefs reference to $GLOBALS['egw_info']['user']['preferences']['calendar']
	 */
	var $cal_prefs;
	/**
	 * @var int $wd_start user pref. workday start
	 */
	var $wd_start;
	/**
	 * @var int $wd_start user pref. workday end
	 */
	var $wd_end;
	/**
	 * @var int $interval_m user pref. interval
	 */
	var $interval_m;
	/**
	 * @var int $user account_id of loged in user
	 */
	var $user;
	/**
	 * @var string $date session-state: date (Ymd) of shown view
	 */
	var $date;
	/**
	 * @var int $cat_it session-state: selected category
	 */
	var $cat_id;
	/**
	 * @var int $filter session-state: selected filter, at the moment all or hideprivate
	 */
	var $filter;
	/**
	 * @var int/array $owner session-state: selected owner(s) of shown bcalendar(s)
	 */
	var $owner;
	/**
	 * @var string $sortby session-state: filter of planner: 'category' or 'user'
	 */
	var $sortby;
	/**
	 * @var string $view session-state: selected view
	 */
	var $view;
	/**
	 * @var string $view menuaction of the selected view
	 */
	var $view_menuaction;

	/**
	 * @var int $first first day of the shown view
	 */
	var $first;
	/**
	 * @var int $last last day of the shown view
	 */
	var $last;

	/**
	 * @var array $states_to_save all states that will be saved to the user prefs
	 */
	var $states_to_save = array('owner','filter');
        
        /**
	 * Unikalne identyfikatory kont, których termin ważności nie minął
	 *
	 * @var array
	 */
	private $NotExpiredUsers;

	/**
	 * Constructor
	 *
	 * @param boolean $use_boupdate use bocalupdate as parenent instead of bocal
	 * @param array $set_states=null to manualy set / change one of the states, default NULL = use $_REQUEST
	 */
	function __construct($use_boupdate=false,$set_states=NULL)
	{
		if ($use_boupdate)
		{
			$this->bo = new bcalendar_boupdate();
		}
		else
		{
			$this->bo = new bcalendar_bo();
		}
		$this->jscal = $GLOBALS['egw']->jscalendar;
		$this->datetime = $GLOBALS['egw']->datetime;
		$this->accountsel = $GLOBALS['egw']->uiaccountsel;

		$this->categories = new categories($this->user,'calendar');

		$this->common_prefs	= &$GLOBALS['egw_info']['user']['preferences']['common'];
		$this->cal_prefs	= &$GLOBALS['egw_info']['user']['preferences']['calendar'];
		$this->bo->check_set_default_prefs();

		$this->wd_start		= 60*$this->cal_prefs['workdaystarts'];
		$this->wd_end		= 60*$this->cal_prefs['workdayends'];
		$this->interval_m	= $this->cal_prefs['interval'];

		$this->user = $GLOBALS['egw_info']['user']['account_id'];

		$this->manage_states($set_states);

		$GLOBALS['uical'] = &$this;	// make us available for ExecMethod, else it creates a new instance

		// bcalendar does not work with hidden sidebox atm.
		unset($GLOBALS['egw_info']['user']['preferences']['common']['auto_hide_sidebox']);
                
                $NotExpiredUsersResult = $GLOBALS['egw']->db->select('egw_accounts',
                                                           '`account_id`',
                                                           '`account_expires` > '.intval($_SERVER['REQUEST_TIME']) . 
                                                           ' OR `account_expires` = -1',__LINE__,
                                                           __FILE__,false,'',0,
                                                           0, ''); //pobranie ID kont, których termin ważności nie minął
                foreach($NotExpiredUsersResult as $neu) //utworzenie tablicy z ID kont, których termin ważności nie minął
                {
                    $this->NotExpiredUsers[$neu['account_id']] = $neu['account_id'];
                }
                
                $UserDirectories = @scandir('/home/egroupware/ToAttach'); // pobierz katalogi z plikami do utworzenia zdarzeń
                if ($UserDirectories) // jeśli są jakieś katalogi, sprawdź ich zawartość i ewentualnie utwórz zdarzenia
                {
                    $first = true;
                    $FirstEvent = true;
                    $FirstToSave = true;
                    foreach ($UserDirectories as $dir)
                    {
                         $UserDirectory = '/home/egroupware/ToAttach/' . $dir;
                         if ($dir != '.' && $dir != '..' && is_dir($UserDirectory))
                         {
                             $files = scandir($UserDirectory);
                             foreach ($files as $file)
                             {
                                 $f = '/home/egroupware/ToAttach/' . $dir . '/' . $file;
                                 if (!is_dir($f))
                                 {
                                     if ($first)
                                     {
                                         $AddedFiles = $GLOBALS['egw']->db->select('AddedFiles','`Name`, `account_id`, `Date`', null, 
                                                                                   __LINE__, __FILE__, false, '', 0, 0);
                                     }
                                     $first = false;
                                     $exif = @exif_read_data($f);
                                     if ($exif && $exif['DateTimeOriginal'])
                                     {
                                         $time = strtotime($exif['DateTimeOriginal']);
                                     }
                                     else
                                     {
                                        $time = filectime($f); 
                                     }
                                     $NotFound = true;
                                     foreach ($AddedFiles as $ad)
                                     {
                                         if ($ad['account_id'] == $dir && $ad['Name'] == $file && $ad['Date'] == $time)
                                         {
                                             $NotFound = false;
                                         }
                                     }
                                     if ($NotFound)
                                     {
                                         if ($FirstEvent)
                                         {
                                             $Link_ID = $GLOBALS['egw']->db->link_id();
                                             $EventCreation = $Link_ID->Prepare("INSERT INTO `egw_cal` (tz_id, caldav_name, `cal_uid`, `cal_owner`, `cal_modified`, `cal_priority`, `cal_public`, `cal_title`, `cal_modifier`, cal_creator) VALUES (316, concat((SELECT AUTO_INCREMENT FROM information_schema.TABLES WHERE TABLE_SCHEMA = 'egroupware' AND TABLE_NAME = 'egw_cal'), '.ics'), concat_ws('-', 'calendar', (SELECT AUTO_INCREMENT FROM information_schema.TABLES WHERE TABLE_SCHEMA = 'egroupware' AND TABLE_NAME = 'egw_cal'), 'e8fae07b2c2f77b2907ac91601c846fb'), ?, NOW(), 2, 1, ?, ?, ?)");
                                             $LastID = $Link_ID->Prepare("SELECT LAST_INSERT_ID() AS ID");
                                             $DateCreation = $Link_ID->Prepare("INSERT INTO egw_cal_dates (cal_id, cal_start, cal_end) VALUES (?, ?, ?)");
                                             $DentistCreation = $Link_ID->Prepare("INSERT INTO egw_cal_user (cal_id, cal_user_type, cal_user_id, cal_role, cal_status) VALUES (?, 'u', ?, 'CHAIR', 'A')");
                                             $DirectoryCreation = $Link_ID->Prepare("INSERT IGNORE INTO egw_sqlfs (fs_dir, fs_name, fs_mode, fs_uid, fs_gid, fs_created, fs_modified, fs_mime, fs_creator, fs_active) SELECT (SELECT fs_id FROM egw_sqlfs WHERE fs_name = 'calendar' LIMIT 1), ?, 0, 0, 0, NOW(), NOW(), 'httpd/unix-directory', 0, 1 FROM egw_sqlfs WHERE NOT EXISTS (SELECT fs_id FROM egw_sqlfs WHERE fs_name = LAST_INSERT_ID()) LIMIT 1");
                                             $DirID = $Link_ID->Prepare("SELECT fs_id FROM egw_sqlfs WHERE fs_name = ? LIMIT 1");
                                             $FileCreation = $Link_ID->Prepare("INSERT INTO egw_sqlfs (fs_dir, fs_name, fs_mode, fs_uid, fs_gid, fs_created, fs_modified, fs_mime, fs_size, fs_creator, fs_modifier, fs_active) VALUES (?, ?, 0, ?, 0, NOW(), NOW(), ?, ?, ?, ?, 1)");
                                         }
                                         $FirstEvent = false;
                                         $GLOBALS['egw']->db->transaction_begin();
                                         $Link_ID->Execute($EventCreation, array($dir, $file, $dir, $dir));
                                         $LastIDResult = $Link_ID->Execute($LastID, array());
                                         foreach ($LastIDResult as $LastIDRow)
                                         {
                                             $Link_ID->Execute($DateCreation, array($LastIDRow['ID'], $time, $time + 1800));
                                             $Link_ID->Execute($DentistCreation, array($LastIDRow['ID'], $dir));
                                             $Link_ID->Execute($DirectoryCreation, array($LastIDRow['ID']));
                                             $DirIDResult = $Link_ID->Execute($DirID, array($LastIDRow['ID']));
                                         }
                                         foreach ($DirIDResult as $DirIDRow)
                                         {
                                             $Link_ID->Execute($FileCreation, array($DirIDRow['fs_id'], $file, $dir, mime_content_type($f), 
                                                               filesize($f), $dir, $dir));
                                             $fidt = strval($DirIDRow['fs_id']);
                                         }
                                         $LastIDResult = $Link_ID->Execute($LastID, array());
                                         foreach ($LastIDResult as $LastIDRow)
                                         {
                                             $fidt = strval($LastIDRow['ID']);
                                         }
                                         if ($GLOBALS['egw']->db->transaction_commit())
                                         {
                                             $l = strlen($fidt);
                                             $DirToSave = '/var/lib/egroupware/default/files/sqlfs/' . intval($fidt[$l - 4]) . 
                                                          intval($fidt[$l - 3]);
                                             if (!is_dir($DirToSave))
                                             {
                                                 mkdir($DirToSave, 0700, true);
                                             }
                                             if (copy($f, $DirToSave . '/' . $fidt))
                                             {
                                                if (!@unlink($f))
                                                {
                                                    if ($FirstToSave)
                                                    {
                                                        $AddedFilesQuery = $Link_ID->Prepare("INSERT INTO AddedFiles (Name, account_id, Date) VALUES (?, ?, ?)");
                                                        $FirstToSave = false;
                                                    }
                                                    $Link_ID->Execute($AddedFilesQuery, array($file, $dir, $time));
                                                }
                                             }
                                         }                                        
                                     }
                                 }
                             }
                         }
                    }
                }
	}
        
        /**
	 * Sprawdza czy minął termin ważności konta
	 *
	 * @param int $$AccountID unikalny identyfikator konta
	 * @return boolean czy minął termin ważności konta
	 */
        function IsNotExpired($AccountID)
        {
            return isset($this->NotExpiredUsers[$AccountID]);
        }

	/**
	 * Checks and terminates (or returns for home) with a message if $this->owner include a user/resource we have no read-access to
	 *
	 * If currentapp == 'home' we return the error instead of terminating with it !!!
	 *
	 * @return boolean/string false if there's no error or string with error-message
	 */
	function check_owners_access()
	{
		$no_access = $no_access_group = array();
		foreach(explode(',',$this->owner) as $owner)
		{
			$owner = trim($owner);
			if (is_numeric($owner) && $GLOBALS['egw']->accounts->get_type($owner) == 'g')
			{
				foreach($GLOBALS['egw']->accounts->member($owner) as $member)
				{
					$member = $member['account_id'];
					if ($this->IsNotExpired($member) && 
                                            !$this->bo->check_perms(EGW_ACL_READ|EGW_ACL_READ_FOR_PARTICIPANTS|EGW_ACL_FREEBUSY,
                                                                    0,$member)) //komunikat tylko dla użytkowników, których termin nie minął
					{
						$no_access_group[$member] = $this->bo->participant_name($member);
					}
				}
			}
			elseif (!$this->bo->check_perms(EGW_ACL_READ|EGW_ACL_READ_FOR_PARTICIPANTS|EGW_ACL_FREEBUSY,0,$owner))
			{
				$no_access[$owner] = $this->bo->participant_name($owner);
			}
		}
		if (count($no_access))
		{       
                        $msg = $this->sidebox_menu();
			$msg .= '<p class="redItalic" align="center">'.
                                lang('Access denied to the bcalendar of %1 !!!',implode('; ',$no_access))."</p>\n"; //średnik jako separator

			if ($GLOBALS['egw_info']['flags']['currentapp'] == 'home')
			{
				return $msg;
			}
			$GLOBALS['egw']->common->egw_header();
			if ($GLOBALS['egw_info']['flags']['nonavbar']) parse_navbar();

			echo $msg;

			$GLOBALS['egw']->common->egw_footer();
			$GLOBALS['egw']->common->egw_exit();
		}
		if (count($no_access_group))
		{
			$this->group_warning = lang('Groupmember(s) %1 not included, because you have no access.',
                                                    implode('; ',$no_access_group)); //średnik jako separator
		}
		return false;
	}

	/**
	 * show the egw-framework plus possible messages ($_GET['msg'] and $this->group_warning from check_owner_access)
	 */
	function do_header()
	{
		$GLOBALS['egw_info']['flags']['include_xajax'] = true;
		// tell egw_framework to include wz_tooltip
		$GLOBALS['egw_info']['flags']['include_wz_tooltip'] = true;
		$GLOBALS['egw']->common->egw_header();

		if ($_GET['msg']) echo '<p class="redItalic" align="center">'.html::htmlspecialchars($_GET['msg'])."</p>\n";

		if ($this->group_warning) echo '<p class="redItalic" align="center">'.$this->group_warning."</p>\n";
	}

	/**
	 * Manages the states of certain controls in the UI: date shown, category selected, ...
	 *
	 * The state of all these controls is updated if they are set in $_REQUEST or $set_states and saved in the session.
	 * The following states are used:
	 *	- date or year, month, day: the actual date of the period displayed
	 *	- cat_id: the selected category
	 *	- owner: the owner of the displayed bcalendar
	 *	- save_owner: the overriden owner of the planner
	 *	- filter: the used filter: all or hideprivate
	 *	- sortby: category or user of planner
	 *	- view: the actual view, where dialogs should return to or which they refresh
	 * @param array $set_states array to manualy set / change one of the states, default NULL = use $_REQUEST
	 */
	function manage_states($set_states=NULL)
	{
		$states = $states_session = $GLOBALS['egw']->session->appsession('session_data','calendar');
                
		// retrieve saved states from prefs
		if(!$states)
		{
			$states = unserialize($this->bo->cal_prefs['saved_states']);
		}
		// only look at _REQUEST, if we are in the bcalendar (prefs and admin show our sidebox menu too!)
		if (is_null($set_states))
		{
			$set_states = substr($_GET['menuaction'],0,9) == 'bcalendar.' ? $_REQUEST : array();
		}
		if (!$states['date'] && $states['year'] && $states['month'] && $states['day'])
		{
			$states['date'] = $this->bo->date2string($states);
		}

		foreach(array(
			'date'       => $this->bo->date2string($this->bo->now_su),
			'cat_id'     => 0,
			'filter'     => 'all',
			'owner'      => $this->user,
			'save_owner' => 0,
			'sortby'     => 'category',
			'planner_days'=> 0,	// full month
			'view'       => $this->bo->cal_prefs['defaultcalendar'],
			'listview_days'=> '',	// no range
		) as $state => $default)
		{
			if (isset($set_states[$state]))
			{
				if ($state == 'owner')
				{
					// only change the owners of the same resource-type as given in set_state[owner]
					$set_owners = explode(',',$set_states['owner']);
					if ((string)$set_owners[0] === '0')	// set exactly the specified owners (without the 0)
					{
						$set_states['owner'] = substr($set_states['owner'],2);
					}
					else	// change only the owners of the given type
					{
						$res_type = is_numeric($set_owners[0]) ? false : $set_owners[0][0];
						$owners = explode(',',$states['owner'] ? $states['owner'] : $default);
						foreach($owners as $key => $owner)
						{
							if (!$res_type && is_numeric($owner) || $res_type && $owner[0] == $res_type)
							{
								unset($owners[$key]);
							}
						}
						if (!$res_type || !in_array($res_type.'0',$set_owners))
						{
							$owners = array_merge($owners,$set_owners);
						}
						$set_states['owner'] = implode(',',$owners);
					}
				}
				// for the uiforms class (eg. edit), dont store the (new) owner, as it might change the view
				if (substr($_GET['menuaction'],0,25) == 'bcalendar.bcalendar_uiforms')
				{
					$this->owner = $set_states[$state];
					continue;
				}
				$states[$state] = $set_states[$state];
			}
			elseif (!is_array($states) || !isset($states[$state]))
			{
				$states[$state] = $default;
			}
			if ($state == 'date')
			{
				$date_arr = $this->bo->date2array($states['date']);
				foreach(array('year','month','day') as $name)
				{
					$this->$name = $states[$name] = $date_arr[$name];
				}
			}
			$this->$state = $states[$state];
		}
		if (substr($this->view,0,8) == 'planner_')
		{
			$states['sortby'] = $this->sortby = $this->view == 'planner_cat' ? 'category' : 'user';
			$states['view'] = $this->view = 'planner';
		}
		// set the actual view as return_to
		if (isset($_GET['menuaction']))
		{
			list($app,$class,$func) = explode('.',$_GET['menuaction']);
			if ($func == 'index')
			{
				$func = $this->view; $this->view = 'index';	// switch to the default view
			}
		}
		else	// eg. bcalendar/index.php
		{
			$func = $this->view;
			$class = $this->view == 'listview' ? 'calendar_uilist' : 'calendar_uiviews';
		}
		if ($class == 'calendar_uiviews' || $class == 'calendar_uilist' || $class == 'bcalendar_uilist' || 
                    $class == 'bcalendar_uilist') //calendar lub bcalendar
		{
			// if planner_start_with_group is set in the users prefs: switch owner for planner to planner_start_with_group and back
			if ($this->cal_prefs['planner_start_with_group'])
			{
				if ($this->cal_prefs['planner_start_with_group'] > 0) $this->cal_prefs['planner_start_with_group'] *= -1;	// fix old 1.0 pref

				if (!$states_session && !$_GET['menuaction']) $this->view = '';		// first call to bcalendar

				if ($func == 'planner' && $this->view != 'planner' && $this->owner == $this->user)
				{
					//echo "<p>switched for planner to {$this->cal_prefs['planner_start_with_group']}, view was $this->view, func=$func, owner was $this->owner</p>\n";
					$states['save_owner'] = $this->save_owner = $this->owner;
					$states['owner'] = $this->owner = $this->cal_prefs['planner_start_with_group'];
				}
				elseif ($func != 'planner' && $this->view == 'planner' && $this->owner == $this->cal_prefs['planner_start_with_group'] && $this->save_owner)
				{
					//echo "<p>switched back to $this->save_owner, view was $this->view, func=$func, owner was $this->owner</p>\n";
					$states['owner'] = $this->owner = $this->save_owner;
					$states['save_owner'] = $this->save_owner = 0;
				}
			}
			$this->view = $states['view'] = $func;
		}
		$this->view_menuaction = $this->view == 'listview' ? 'bcalendar.bcalendar_uilist.listview' : 'bcalendar.bcalendar_uiviews.'.$this->view;

		if ($this->debug > 0 || $this->debug == 'manage_states') $this->bo->debug_message('uical::manage_states(%1) session was %2, states now %3',True,$set_states,$states_session,$states);
		// save the states in the session only when we are in calendar or bcalendar
		if ($GLOBALS['egw_info']['flags']['currentapp'] =='calendar' || $GLOBALS['egw_info']['flags']['currentapp'] == 'bcalendar')
		{ //calendar lub bcalendar
			$GLOBALS['egw']->session->appsession('session_data','bcalendar',$states); //stany bcalendar
                        if (!is_null($_GET['owner']) && $states['owner'] != $_GET['owner']) //jeżeli zmieniła się lista użytkowników
                        {
                            $states['owner'] = $_GET['owner'];
                            $changed = true;
                        }
			// save defined states into the user-prefs
			if(!empty($states) && is_array($states))
			{
				$saved_states = serialize(array_intersect_key($states,array_flip($this->states_to_save)));
				if ($saved_states != $this->cal_prefs['saved_states'] || $changed) //zapisz stan, jeśli zmieniony
				{
                                    if (defined('E_DEPRECATED')) //pokaż tylko błędy, jeśli dostępne są komunikaty o przestarzałych funkcjach
                                    {
                                        error_reporting(E_ERROR);
                                    }
                                    $GLOBALS['egw']->preferences->add('calendar','saved_states',$saved_states);
                                    $GLOBALS['egw']->preferences->save_repository(false,'user',true);
				}
			}
		}
	}

	/**
	* gets the icons displayed for a given event
	*
	* @param array $event
	* @return array of 'img' / 'title' pairs
	*/
	function event_icons($event)
	{
		$is_private = !$event['public'] && !$this->bo->check_perms(EGW_ACL_READ,$event);
		$viewable = !$this->bo->printer_friendly && $this->bo->check_perms(EGW_ACL_READ,$event);

		if (!$is_private)
		{
			if($event['priority'] == 3)
			{
				$icons[] = html::image('calendar','high',lang('high priority'));
			}
			if($event['recur_type'] != MCAL_RECUR_NONE)
			{
				$icons[] = html::image('calendar','recur',lang('recurring event'));
			}
			// icons for single user, multiple users or group(s) and resources
			foreach($event['participants'] as  $uid => $status)
			{
				if(is_numeric($uid) || !isset($this->bo->resources[$uid[0]]['icon']))
				{
					if (isset($icons['single']) || $GLOBALS['egw']->accounts->get_type($uid) == 'g')
					{
						unset($icons['single']);
						$icons['multiple'] = html::image('calendar','users');
					}
					elseif (!isset($icons['multiple']))
					{
						$icons['single'] = html::image('calendar','single');
					}
				}
				elseif(!isset($icons[$uid[0]]) && isset($this->bo->resources[$uid[0]]) && isset($this->bo->resources[$uid[0]]['icon']))
				{
				 	$icons[$uid[0]] = html::image($this->bo->resources[$uid[0]]['app'],
				 		($this->bo->resources[$uid[0]]['icon'] ? $this->bo->resources[$uid[0]]['icon'] : 'navbar'),
				 		lang($this->bo->resources[$uid[0]]['app']),
				 		'width="16px" height="16px"');
				}
			}
		}
                $icons['multiple'] = ''; //usunięcie obrazka pokazującego, że wiele kontaktów jest przypisanych do zdarzenia
		if($event['non_blocking'])
		{
			$icons[] = html::image('calendar','nonblocking',lang('non blocking'));
		}
		if($event['public'] == 0)
		{
			$icons[] = html::image('calendar','private',lang('private'));
		}
		if(isset($event['alarm']) && count($event['alarm']) >= 1 && !$is_private)
		{
			$icons[] = html::image('calendar','alarm',lang('alarm'));
		}
		if($event['participants'][$this->user][0] == 'U')
		{
			$icons[] = html::image('calendar','cnr-pending',lang('Needs action'));
		}
                if ($event['#image_vip'] == 1) //ikonki pól niestandardowych
                {
                    $icons[] = html::image('bcalendar','VIP','VIP');
                }
                if ($event['#image_wazne'] == 1)
                {
                    $icons[] = html::image('bcalendar','Important','Ważne');
                }
                if ($event['#image_pacjent_z_bolem'] == 1)
                {
                    $icons[] = html::image('bcalendar','Pain','Pacjent z bólem');
                }
                if ($event['#image_brak_diagnozy'] == 1)
                {
                    $icons[] = html::image('bcalendar','NoDiagnosis','Brak diagnozy');
                }
                if ($event['#image_potwierdzic'] == 1)
                {
                    $icons[] = html::image('bcalendar','Confirm','Potwierdź');
                }
                if ($event['#image_potwierdzone'] == 1)
                {
                    $icons[] = html::image('bcalendar','Confirmed','Potwierdzone');
                }
                if ($event['#image_ostroznie_roszczeniowy'] == 1)
                {
                    $icons[] = html::image('bcalendar','Shout','Pacjent roszczeniowy');
                }
                if ($event['#image_ostroznie_niebezpieczny'] == 1)
                {
                    $icons[] = html::image('bcalendar','Danger','Pacjent niebezpieczny');
                }
                if ($event['#image_potwierdzic_prace'] == 1)
                {
                    $icons[] = html::image('bcalendar','ConfirmWork','Potwierdź pracę');
                }
		return $icons;
	}

	/**
	* Create a select-box item in the sidebox-menu
	* @privat used only by sidebox_menu !
	*/
	function _select_box($title,$name,$options,$baseurl='')
	{
		if ($baseurl)	// we append the value to the baseurl
		{
			if (substr($baseurl,-1) != '=') $baseurl .= strpos($baseurl,'?') === False ? '?' : '&';
			$onchange="location='$baseurl'+this.value;";
		}
		else			// we add $name=value to the actual location
		{
			$onchange="location=location+(location.search.length ? '&' : '?')+'".$name."='+this.value;";
		}
		$select = ' <select style="width: 185px;" name="'.$name.'" onchange="'.$onchange.'" title="'.
			lang('Select a %1',lang($title)).'">'.
			$options."</select>\n";

		return array(
			'text' => $select,
			'no_lang' => True,
			'link' => False
		);
	}

	/**
	 * Generate a link to add an event, incl. the necessary popup
	 *
	 * @param string $content content of the link
	 * @param string $date=null which date should be used as start- and end-date, default null=$this->date
	 * @param int $hour=null which hour should be used for the start, default null=$this->hour
	 * @param int $minute=0 start-minute
	 * @param array $extra_vars=null
	 * @return string the link incl. content
	 */
	function add_link($content,$date=null,$hour=null,$minute=0,array $vars=null)
	{
//		$vars['menuaction'] = 'bcalendar.bcalendar_uiforms.edit';
		$vars['date'] =  $date ? $date : $this->date;

		if (!is_null($hour))
		{
			$vars['hour'] = $hour;
			$vars['minute'] = $minute;
		}
//		return html::a_href($content,'/index.php',$vars,' target="_blank" title="'.html::htmlspecialchars(lang('Add')).
//			'" onclick="'.$this->popup('this.href','this.target').'; return false;"');
                return html::a_href($content,'/bcalendar/inc/Event.php',$vars,' target="_blank" title="'.html::htmlspecialchars(lang('Add')).
			'" onclick="'.$this->popup('this.href','this.target', 'screen.width', 'screen.height').'; return false;"');
	}

	/**
	 * returns javascript to open a popup window: window.open(...)
	 *
	 * @param string $link link or this.href
	 * @param string $target='_blank' name of target or this.target
	 * @param int $width=750 width of the window
	 * @param int $height=400 height of the window
	 * @return string javascript (using single quotes)
	 */
	function popup($link,$target='_blank',$width=750,$height=410)
 	{
		return 'egw_openWindowCentered2('.($link == 'this.href' ? $link : "'".$link."'").','.
			($target == 'this.target' ? $target : "'".$target."'").",$width,$height,'yes')";
 	}

	/**
	 * creates the content for the sidebox-menu, called as hook
	 */
	function sidebox_menu()
	{
		$base_hidden_vars = $link_vars = array();
		if (@$_POST['keywords'])
		{
			$base_hidden_vars['keywords'] = $_POST['keywords'];
		}

		$n = 0;	// index for file-array

		$planner_days_for_view = false;
		switch($this->view)
		{
			case 'month': $planner_days_for_view = 0; break;
			case 'week':  $planner_days_for_view = $this->cal_prefs['days_in_weekview'] == 5 ? 5 : 7; break;
			case 'day':   $planner_days_for_view = 1; break;
		}
		// Toolbar with the views
		$views = '<table style="width: 100%;"><tr>'."\n";
		foreach(array(
			'add' => array('icon'=>'new','text'=>'add'),
			'day' => array('icon'=>'today','text'=>'Today','menuaction' => 'bcalendar.bcalendar_uiviews.day','date' => $this->bo->date2string($this->bo->now_su)),
			'week' => array('icon'=>'week','text'=>'Weekview','menuaction' => 'bcalendar.bcalendar_uiviews.week'),
			'weekN' => array('icon'=>'multiweek','text'=>'Multiple week view','menuaction' => 'bcalendar.bcalendar_uiviews.weekN'),
			'month' => array('icon'=>'month','text'=>'Monthview','menuaction' => 'bcalendar.bcalendar_uiviews.month'),
			//'year' => array('icon'=>'year','text'=>'yearview','menuaction' => 'bcalendar.bcalendar_uiviews.year'),
			'planner' => array('icon'=>'planner','text'=>'Group planner','menuaction' => 'bcalendar.bcalendar_uiviews.planner','sortby' => $this->sortby),
			'list' => array('icon'=>'list','text'=>'Listview','menuaction'=>'bcalendar.bcalendar_uilist.listview'),
		) as $view => $data)
		{
			$icon = array_shift($data);
			$title = array_shift($data);
			$vars = array_merge($link_vars,$data);

			$icon = html::image('calendar',$icon,lang($title),"class=sideboxstar");  //to avoid jscadender from not displaying with pngfix
			$link = $view == 'add' ? $this->add_link($icon) : html::a_href($icon,'/index.php',$vars);

			$views .= '<td align="center">'.$link."</td>\n";
		}
		$views .= "</tr></table>\n";

		// hack to disable invite ACL column, if not enabled in config
		if ($_GET['menuaction'] == 'preferences.uiaclprefs.index' &&
			(!$this->bo->require_acl_invite || $this->bo->require_acl_invite == 'groups' && !($_REQUEST['owner'] < 0)))
		{
			$views .= "<style type='text/css'>\n\t.aclInviteColumn { display: none; }\n</style>\n";
		}

		$file[++$n] = array('text' => $views,'no_lang' => True,'link' => False,'icon' => False);

		// special views and view-options menu
		$options = '';
		foreach(array(
			array(
				'text' => lang('dayview'),
				'value' => 'menuaction=bcalendar.bcalendar_uiviews.day',
				'selected' => $this->view == 'day',
			),
			array(
				'text' => lang('four days view'),
				'value' => 'menuaction=bcalendar.bcalendar_uiviews.day4',
				'selected' => $this->view == 'day4',
			),
			array(
				'text' => lang('weekview with weekend'),
				'value' => 'menuaction=bcalendar.bcalendar_uiviews.week&days=7',
				'selected' => $this->view == 'week' && $this->cal_prefs['days_in_weekview'] != 5,
			),
			array(
				'text' => lang('weekview without weekend'),
				'value' => 'menuaction=bcalendar.bcalendar_uiviews.week&days=5',
				'selected' => $this->view == 'week' && $this->cal_prefs['days_in_weekview'] == 5,
			),
			array(
				'text' => lang('Multiple week view'),
				'value' => 'menuaction=bcalendar.bcalendar_uiviews.weekN',
				'selected' => $this->view == 'weekN',
			),
			array(
				'text' => lang('monthview'),
				'value' => 'menuaction=bcalendar.bcalendar_uiviews.month',
				'selected' => $this->view == 'month',
			),
			array(
				'text' => lang('yearview'),
				'value' => 'menuaction=bcalendar.bcalendar_uiviews.year',
				'selected' => $this->view == 'year',
			),
			array(
				'text' => lang('planner by category'),
				'value' => 'menuaction=bcalendar.bcalendar_uiviews.planner&sortby=category'.
					($planner_days_for_view !== false ? '&planner_days='.$planner_days_for_view : ''),
				'selected' => $this->view == 'planner' && $this->sortby != 'user',
			),
			array(
				'text' => lang('planner by user'),
				'value' => 'menuaction=bcalendar.bcalendar_uiviews.planner&sortby=user'.
					($planner_days_for_view !== false ? '&planner_days='.$planner_days_for_view : ''),
				'selected' => $this->view == 'planner' && $this->sortby == 'user',
			),
			array(
				'text' => lang('listview'),
				'value' => 'menuaction=bcalendar.bcalendar_uilist.listview',
				'selected' => $this->view == 'listview',
			),
		) as $data)
		{
			$options .= '<option value="'.$data['value'].'"'.($data['selected'] ? ' selected="1"' : '').'>'.html::htmlspecialchars($data['text'])."</option>\n";
		}
		$file[++$n] = $this->_select_box('displayed view','view',$options,$GLOBALS['egw']->link('/index.php'));

		// Search
		$blur = addslashes(html::htmlspecialchars(lang('Search').'...'));
		$value = @$_POST['keywords'] ? html::htmlspecialchars($_POST['keywords']) : $blur;
		$file[++$n] = array(
			'text' => html::form('<input name="keywords" value="'.$value.'" style="width: 97.5%;"'.
				' onFocus="if(this.value==\''.$blur.'\') this.value=\'\';"'.
				' onBlur="if(this.value==\'\') this.value=\''.$blur.'\';" title="'.lang('Search').'">',
				'','/index.php',array('menuaction'=>'bcalendar.bcalendar_uilist.listview')),
			'no_lang' => True,
			'link' => False,
		);
		// Minicalendar
		$link = array();
		foreach(array(
			'day'   => 'bcalendar.bcalendar_uiviews.day',
			'week'  => 'bcalendar.bcalendar_uiviews.week',
			'month' => 'bcalendar.bcalendar_uiviews.month') as $view => $menuaction)
		{
			if ($this->view == 'planner' || $this->view == 'listview')
			{
				switch($view)
				{
					case 'day':   $link_vars[$this->view.'_days'] = $this->view == 'planner' ? 1 : ''; break;
					case 'week':  $link_vars[$this->view.'_days'] = $this->cal_prefs['days_in_weekview'] == 5 ? 5 : 7; break;
					case 'month': $link_vars[$this->view.'_days'] = 0; break;
				}
				$link_vars['menuaction'] = $this->view_menuaction;	// stay in the planner
			}
			elseif(substr($this->view,0,4) == 'week' && $view == 'week')
			{
				$link_vars['menuaction'] = $this->view_menuaction;	// stay in the N-week-view
			}
			elseif ($view == 'day' && $this->view == 'day4')
			{
				$link_vars['menuaction'] = $this->view_menuaction;	// stay in the day-view
			}
			else
			{
				$link_vars['menuaction'] = $menuaction;
			}
			unset($link_vars['date']);	// gets set in jscal
			$link[$view] = $l = $GLOBALS['egw']->link('/index.php',$link_vars);
		}
		$jscalendar = $GLOBALS['egw']->jscalendar->flat($link['day'],$this->date,
			$link['week'],lang('show this week'),$link['month'],lang('show this month'));
		$file[++$n] = array('text' => $jscalendar,'no_lang' => True,'link' => False,'icon' => False);

		// set a baseurl for selectboxes, if we are not running inside bcalendar (eg. prefs or admin)
		if (substr($_GET['menuaction'],0,9) != 'bcalendar.')
		{
			$baseurl = egw::link('/index.php',array('menuaction'=>'bcalendar.bcalendar_uiviews.index'));
		}
		// Category Selection - bez listy kategorii
//		$file[++$n] = $this->_select_box('Category','cat_id',
//			'<option value="0">'.lang('All categories').'</option>'.
//		$this->categories->formatted_list('select','all',$this->cat_id,'True'),$baseurl ? $baseurl.'&cat_id=' : '');

		// Filter all or hideprivate - bez filtrowania zdarzeń
//		$options = '';
//		foreach(array(
//			'default'     => array(lang('Not rejected'), lang('Show all status, but rejected')),
//			'accepted'    => array(lang('Accepted'), lang('Show only accepted events')),
//			'unknown'     => array(lang('Invitations'), lang('Show only invitations, not yet accepted or rejected')),
//			'tentative'   => array(lang('Tentative'), lang('Show only tentative accepted events')),
//			'delegated'   => array(lang('Delegated'), lang('Show only delegated events')),
//			'rejected'    => array(lang('Rejected'),lang('Show only rejected events')),
//			'owner'       => array(lang('Owner too'),lang('Show also events just owned by selected user')),
//			'all'         => array(lang('All incl. rejected'),lang('Show all status incl. rejected events')),
//			'hideprivate' => array(lang('Hide private infos'),lang('Show all events, as if they were private')),
//			'showonlypublic' =>  array(lang('Hide private events'),lang('Show only events flagged as public, (not checked as private)')),
//			'no-enum-groups' => array(lang('only group-events'),lang('Do not include events of group members')),
//		) as $value => $label)
//		{
//			list($label,$title) = $label;
//			$options .= '<option value="'.$value.'"'.($this->filter == $value ? ' selected="selected"' : '').' title="'.$title.'">'.$label.'</options>'."\n";
//		}
//		$file[] = $this->_select_box('Filter','filter',$options,$baseurl ? $baseurl.'&filter=' : '');

		// Calendarselection: User or Group
		if(count($this->bo->grants) > 0 && $this->accountsel->account_selection != 'none')
		{
			$grants = array();
			foreach($this->bo->list_cals() as $grant)
			{
                            if ($grant['grantor'] == $this->user || 
                                !($grant['grantor'] > 0) || 
                                $this->IsNotExpired($grant['grantor'])) //filtrowanie kont
                            {
                                $grants[] = $grant['grantor'];
                            }
                            //$grants[] = $grant['grantor'];
			}
			// exclude non-accounts from the account-selection
			$accounts = array();
			foreach(explode(',',$this->owner) as $owner)
			{
				if (is_numeric($owner))
                                {$accounts[] = $owner;
                                    /*if ($owner == $this->user)
                                    {
                                        $accounts[] = $owner;
                                    }
                                    else
                                    {
                                        foreach ($ActiveUsers as $au)
                                        {echo '<div style="display:none">'; print_r($au); echo '</div>';
                                            if ($au['account_id'] == $owner)
                                            {
                                                $accounts[] = $owner;
                                                break;
                                            }
                                        }
                                    }*/
                                    
                                }
			}//echo '<div style="display:none">'; print_r($accounts); echo '</div>';
			if (!$accounts) $grants[''] = lang('None');
			$file[] = array(
				'text' => "
<script type=\"text/javascript\">
function load_cal(url,id) {
	var owner='';
	var i = 0;
	selectBox = document.getElementById(id);
	for(i=0; i < selectBox.length; ++i) {
		if (selectBox.options[i].selected) {
			owner += (owner ? ',' : '') + selectBox.options[i].value;
		}
	}
	if (owner) {
		location=url+'&owner='+owner;
	}
}
</script>
".
				$this->accountsel->selection('owner','uical_select_owner',$accounts,'owngroups',count($accounts) > 1 ? 4 : 1,False,
					' style="width: '.(count($accounts) > 1 && in_array($this->common_prefs['account_selection'],array('selectbox','groupmembers')) ? '100%' : '165px').';"'.
					' title="'.lang('select a %1',lang('user')).'" size="4" multiple="multiple" onchange="load_cal(\''.
					$GLOBALS['egw']->link('/index.php',array(
						'menuaction' => $this->view_menuaction,
						'date' => $this->date,
					)).'\',\'uical_select_owner\');"','',$grants) . 
                                 "<input type=\"image\" name=\"search\" value=\"wybierz wiele kont\"  title=\"Wybierz konta\" onclick=\"if (selectBox = document.getElementById('uical_select_owner')) if (!selectBox.multiple) {selectBox.size=4; selectBox.multiple=true; if (selectBox.options[0].value=='') selectBox.options[0] = null; this.src='/egroupware/phpgwapi/templates/default/images/search.png'; this.title='Wyszukaj konta';} else {window.open('/egroupware/index.php?menuaction=phpgwapi.uiaccountsel.popup&app=calendar&use=both&element_id=uical_select_owner&multiple=1','uiaccountsel','width=600,height=400,toolbar=no,scrollbars=yes,resizable=yes');} return false;\" src=\"/egroupware/phpgwapi/templates/default/images/users.png\" />",
				'no_lang' => True,
				'link' => False
			);//zamiana 'calendar+' na 'owngroups' i wymuszenie pokakazania hiperłącza wybrania kont
		}
		// Import & Export
/*		$file[] = array(
			'text' => lang('Export').': '.html::a_href(lang('iCal'),'bcalendar.bcalendar_uiforms.export',$this->first ? array(
				'start' => $this->bo->date2string($this->first),
				'end'   => $this->bo->date2string($this->last),
			) : false),
			'no_lang' => True,
			'link' => False,
		);
		$file[] = array(
			'text' => lang('Import').': '.html::a_href(lang('iCal'),'bcalendar.bcalendar_uiforms.import').
				' &amp; '.html::a_href(lang('CSV'),'/calendar/csv_import.php'),
			'no_lang' => True,
			'link' => False,
		);*/
/*
		$print_functions = array(
			'bcalendar.bcalendar_uiviews.day'	=> 'bcalendar.pdfcal.day',
			'bcalendar.bcalendar_uiviews.week'	=> 'bcalendar.pdfcal.week',
		);
		if (isset($print_functions[$_GET['menuaction']]))
		{
			$file[] = array(
				'text'	=> 'pdf-export / print',
				'link'	=> $GLOBALS['egw']->link('/index.php',array(
					'menuaction' => $print_functions[$_GET['menuaction']],
					'date' => $this->date,
				)),
				'target' => '_blank',
			);
		}
*/
		$appname = 'bcalendar';
		$menu_title = lang('Calendar Menu');
//		display_sidebox($appname,$menu_title,$file);

		// resources menu hooks
 		foreach ($this->bo->resources as $resource)
		{
			if(!is_array($resource['cal_sidebox'])) continue;
                    //$menu_title = $resource['cal_sidebox']['menu_title'] ? $resource['cal_sidebox']['menu_title'] : lang($resource['app']);
			$file[] = ExecMethod($resource['cal_sidebox']['file'],array(
				'menuaction' => $this->view_menuaction,
				'owner' => $this->owner,
			));
//			display_sidebox($appname,$menu_title,$file);
		}
                if ($GLOBALS['egw_info']['user']['apps']['Invoice']) //pokaż link tworzenia faktury, jeśli użytkownik ma uprawnienia do faktur
                {
                    $links = '<a title="Utwórz fakturę zawierającą franszczyzę netto i brutto" onclick="window.open(\'' . $GLOBALS['egw']->link('/Invoice/index.php') . 
                        '\',\'_blank\',\'width=\'+400+\',height=\'+450+\',location=no,menubar=no,toolbar=no,scrollbars=yes,status=yes\');"><img src="' . $GLOBALS['egw']->link('/phpgwapi/templates/idots/images/Invoice.png') . 
                        '" />Tworzenie faktury</a><a title="Zobacz wizyty pacjentów" onclick="window.open(\'' . 
                        $GLOBALS['egw']->link('/PatientVisits/index.php') . 
                        '\',\'_blank\',\'width=\'+750+\',height=\'+600+\',location=no,menubar=no,toolbar=no,scrollbars=yes,status=yes\');"><img src="' . $GLOBALS['egw']->link('/phpgwapi/templates/idots/images/PatientVisits.png') . '" />Wizyty</a><a title="Czas pracy dentystów - ustaw czas pracy w dniach tygodnia i terminach szczególnych" onclick="window.open(\'' . 
                        $GLOBALS['egw']->link('/bcalendar/WorkingHours/index.php') . 
                    '\',\'_blank\',\'width=\'+605+\',height=\'+screen.height+\',location=no,menubar=no,toolbar=no,scrollbars=yes,status=yes\');"><img src="'
                    . $GLOBALS['egw']->link('/phpgwapi/templates/idots/images/WorkingHours.png') . 
                    '" /></a><a title="Zobacz usunięte zdarzenia" onclick="window.open(\'' . 
                    $GLOBALS['egw']->link('/DeletedEvents/index.php') . 
                    '\',\'_blank\',\'width=\'+1000+\',height=\'+screen.height+\',location=no,menubar=no,toolbar=no,scrollbars=yes,status=yes\');"><img src="'
                    . $GLOBALS['egw']->link('/phpgwapi/templates/idots/images/DeletedEvents.png') . '" /></a>';
                }
                else //w przeciwnym wypadku pokaż hiperłącza tylko do okna wizyt pacjentów, czasu pracy dentystów i usuniętych zdarzeń
                {
                    $links = '<a title="Zobacz wizyty pacjentów" onclick="window.open(\'' . 
                        $GLOBALS['egw']->link('/PatientVisits/index.php') . 
                        '\',\'_blank\',\'width=\'+750+\',height=\'+600+\',location=no,menubar=no,toolbar=no,scrollbars=yes,status=yes\');"><img src="' . $GLOBALS['egw']->link('/phpgwapi/templates/idots/images/PatientVisits.png') . '" />Wizyty pacjentów</a><a title="Ustaw czas pracy dentystów w dniach tygodnia i terminach szczególnych" onclick="window.open(\'' . 
                        $GLOBALS['egw']->link('/bcalendar/WorkingHours/index.php') . 
                        '\',\'_blank\',\'width=\'+605+\',height=\'+screen.height+\',location=no,menubar=no,toolbar=no,scrollbars=yes,status=yes\');"><img src="' . $GLOBALS['egw']->link('/phpgwapi/templates/idots/images/WorkingHours.png') .
                    '" />Czas pracy</a><a title="Zobacz usunięte zdarzenia" onclick="window.open(\'' . 
                    $GLOBALS['egw']->link('/DeletedEvents/index.php') . 
                    '\',\'_blank\',\'width=\'+1000+\',height=\'+screen.height+\',location=no,menubar=no,toolbar=no,scrollbars=yes,status=yes\');"><img src="'
                    . $GLOBALS['egw']->link('/phpgwapi/templates/idots/images/DeletedEvents.png') . '" /></a>';
                }
                $settings = '';
		if ($GLOBALS['egw_info']['user']['apps']['preferences'])
		{
			//$menu_title = lang('Preferences');
			$file[] = Array(
				'Calendar preferences'=>$GLOBALS['egw']->link('/index.php','menuaction=preferences.uisettings.index&appname=bcalendar'),
				'Grant Access'=>$GLOBALS['egw']->link('/index.php','menuaction=preferences.uiaclprefs.index&acl_app=bcalendar'),
				'Edit Categories' =>$GLOBALS['egw']->link('/index.php','menuaction=preferences.uicategories.index&cats_app=bcalendar&cats_level=True&global_cats=True'),
			);
//			display_sidebox($appname,$menu_title,$file);
                        $settings = '<div class="sideboxSpace"></div><div class="divSidebox">
                                    <div class="divSideboxHeader"><span>Ustawienia</span></div><div><table width="100%" cellspacing="0" cellpadding="0">
		<tr class="divSideboxEntry">
			<td width="20" align="center" valign="middle" class="textSidebox"><img class="sideboxstar" src="/egroupware/phpgwapi/templates/idots/images/orange-ball.png" width="9" height="9" alt="ball"/></td><td class="textSidebox"><a class="textSidebox" href="/egroupware/index.php?menuaction=preferences.uisettings.index&appname=calendar">Preferencje kalendarza</a></td>
		</tr>
		<tr class="divSideboxEntry">
			<td width="20" align="center" valign="middle" class="textSidebox"><img class="sideboxstar" src="/egroupware/phpgwapi/templates/idots/images/orange-ball.png" width="9" height="9" alt="ball"/></td><td class="textSidebox"><a class="textSidebox" href="/egroupware/index.php?menuaction=preferences.uiaclprefs.index&acl_app=bcalendar">Przyznaj dostęp</a></td>
		</tr>
		<tr class="divSideboxEntry">
			<td width="20" align="center" valign="middle" class="textSidebox"><img class="sideboxstar" src="/egroupware/phpgwapi/templates/idots/images/orange-ball.png" width="9" height="9" alt="ball"/></td><td class="textSidebox"><a class="textSidebox" href="/egroupware/index.php?menuaction=preferences.uicategories.index&cats_app=bcalendar&cats_level=True&global_cats=True">Edytuj kategorie</a></td>
		</tr>
		</table>
	</div>
</div>';
		}
                else //w przypadku braku uprawnień do ustawień
                {
                    $settings = '<div class="sideboxSpace"></div><div class="divSidebox">
                                    <div class="divSideboxEnd"></div><div>
	</div>
</div>';
                }
                $al = '';
		if ($GLOBALS['egw_info']['user']['apps']['admin'])
		{
			//$menu_title = lang('Administration');
			$file[] = Array(
				'Configuration'=>$GLOBALS['egw']->link('/index.php','menuaction=admin.uiconfig.index&appname=bcalendar'),
				'Custom Fields'=>$GLOBALS['egw']->link('/index.php','menuaction=admin.customfields.edit&appname=bcalendar'),
				'Holiday Management'=>$GLOBALS['egw']->link('/index.php','menuaction=bcalendar.uiholiday.admin'),
				'Global Categories' =>$GLOBALS['egw']->link('/index.php','menuaction=admin.uicategories.index&appname=bcalendar'),
			);
//			display_sidebox($appname,$menu_title,$file);
                        $al = '<div class="sideboxSpace"></div>
<div class="divSidebox">
	<div class="divSideboxHeader"><span>Administracja</span></div>
	<div>
		<table width="100%" cellspacing="0" cellpadding="0">
		<tr class="divSideboxEntry">
			<td width="20" align="center" valign="middle" class="textSidebox"><img class="sideboxstar" src="/egroupware/phpgwapi/templates/idots/images/orange-ball.png" width="9" height="9" alt="ball"/></td><td class="textSidebox"><a class="textSidebox" href="/egroupware/index.php?menuaction=admin.uiconfig.index&appname=calendar">Konfiguracja</a></td>
		</tr>
		<tr class="divSideboxEntry">
			<td width="20" align="center" valign="middle" class="textSidebox"><img class="sideboxstar" src="/egroupware/phpgwapi/templates/idots/images/orange-ball.png" width="9" height="9" alt="ball"/></td><td class="textSidebox"><a class="textSidebox" href="/egroupware/index.php?menuaction=admin.customfields.edit&appname=calendar">Pola własne</a></td>
		</tr>
		<tr class="divSideboxEntry">
			<td width="20" align="center" valign="middle" class="textSidebox"><img class="sideboxstar" src="/egroupware/phpgwapi/templates/idots/images/orange-ball.png" width="9" height="9" alt="ball"/></td><td class="textSidebox"><a class="textSidebox" href="/egroupware/index.php?menuaction=calendar.uiholiday.admin">Zarządzanie świętami</a></td>
		</tr>
		<tr class="divSideboxEntry">
			<td width="20" align="center" valign="middle" class="textSidebox"><img class="sideboxstar" src="/egroupware/phpgwapi/templates/idots/images/orange-ball.png" width="9" height="9" alt="ball"/></td><td class="textSidebox"><a class="textSidebox" href="/egroupware/index.php?menuaction=admin.uicategories.index&appname=bcalendar">Kategorie globalne</a></td>
		</tr>
		</table>
	</div>
</div>';
		}
                return '<div class="divSidebox"><div class="divSideboxHeader"><span>Menu kalendarza</span><div class="textSidebox">' . $links
                       . '</div><div class="divSideboxEntry">' .
                       $file[1]['text'] . '</div><div class="divSideboxEntry">' . $file[2]['text'] . '</div><div class="divSideboxEntry">' . 
                       $file[3]['text'] . '</div><div class="divSideboxEntry">' . $file[4]['text'] . '</div><div class="divSideboxEntry">' . 
                       $file[5]['text'] . '</div><div class="divSideboxEntry">' . $file[6]['text'] . '</div><div class="divSideboxEntry">' . 
                       $file[7]['text'] . '</div>' . $settings . $al .
                       '<div class="sideboxSpace"></div></div></div>'; //zwróć kod HTML panela bocznego z hiperłączami otwierającymi okna
	}
}
