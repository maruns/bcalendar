<?php
/**
 * eGroupWare Calendar
 *
 * @link http://www.egroupware.org
 * @author Ralf Becker <RalfBecker-AT-outdoor-training.de>
 * @package bcalendar
 * @copyright (c) 2005-8 by Ralf Becker <RalfBecker-AT-outdoor-training.de>
 * @license http://opensource.org/licenses/gpl-license.php GPL - GNU General Public License
 * @version $Id: index.php 25898 2008-08-14 13:53:17Z ralfbecker $
 */



header('Location: ../index.php?menuaction=bcalendar.bcalendar_uiviews.index'.
	(isset($_GET['sessionid']) ? '&sessionid='.$_GET['sessionid'].'&kp3='.$_GET['kp3'] : ''));
