<?php
/**
 * Calendar - ajax class
 *
 * @link http://www.egroupware.org
 * @author Christian Binder <christian.binder@freakmail.de>
 * @package bcalendar
 * @copyright (c) 2006 by Christian Binder <christian.binder@freakmail.de>
 * @license http://opensource.org/licenses/gpl-license.php GPL - GNU General Public License
 * @version $Id: class.bcalendar_ajax.inc.php 27222 2009-06-08 16:21:14Z ralfbecker $
 */

/**
 * General object of the bcalendar ajax class
 */
class bcalendar_ajax {

	/**
	 * bcalendar object to handle events
	 *
	 * @var bcalendar_boupdate
	 */
	var $calendar;

	function __construct()
	{
		$this->calendar = new bcalendar_boupdate();
	}

	/**
	 * moves an event to another date/time
	 *
	 * @param string $eventID id of the event which has to be moved
	 * @param string $calendarOwner the owner of the bcalendar the event is in
	 * @param string $targetDateTime the datetime where the event should be moved to, format: YYYYMMDD
	 * @param string $targetOwner the owner of the target bcalendar
	 * @return string XML response if no error occurs
	 */
	function moveEvent($eventId,$calendarOwner,$targetDateTime,$targetOwner)
	{
		// we do not allow dragging into another users bcalendar ATM
		if(!$calendarOwner == $targetOwner)
		{
			return false;
		}

		$event=$this->calendar->read($eventId);
		$duration=$event['end']-$event['start'];

		$event['start'] = $this->calendar->date2ts($targetDateTime);
		$event['end'] = $event['start']+$duration;

		$conflicts=$this->calendar->update($event);

		$response = new xajaxResponse();
		if(!is_array($conflicts))
		{
			$response->addRedirect('');
		}
		else
		{
			$response->addScriptCall(
				'egw_openWindowCentered2',
				$GLOBALS['egw_info']['server']['webserver_url'].'/index.php?menuaction=bcalendar.bcalendar_uiforms.edit
					&cal_id='.$event['id']
					.'&start='.$event['start']
					.'&end='.$event['end']
					.'&non_interactive=true'
					.'&cancel_needs_refresh=true',
				'',750,410);
		}

		return $response->getXML();
	}
}
