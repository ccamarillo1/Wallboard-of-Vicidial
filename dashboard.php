<?php require_once('Connections/db.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "")
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $wallboard = mysqli_connect($hostname_wallboard, $username_wallboard, $password_wallboard, $database_wallboard);
  if (!$wallboard) {
    die("Error connecting to the database: " . mysqli_connect_error());
  }

  $theValue = function_exists("mysqli_real_escape_string") ? mysqli_real_escape_string($wallboard, $theValue) : mysqli_escape_string($wallboard, $theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

// Live Calls
$query_active = "SELECT COUNT(`status`) AS total FROM vicidial_auto_calls";
$result_active = mysqli_query($wallboard, $query_active);


if ($result_active) {

    $totalRows_active = mysqli_num_rows($result_active);

    $row_active = mysqli_fetch_assoc($result_active);
} else {
    die("Error executing the query: " . mysqli_error($wallboard));
}

mysqli_free_result($result_active);

// Calls in IVR OK
$query_ivr_calls = "SELECT COUNT(`status`) AS total FROM vicidial_auto_calls WHERE `status` = 'LIVE'";
$result_ivr_calls = mysqli_query($wallboard, $query_ivr_calls);


if ($result_ivr_calls) {

    $totalRows_ivr_calls = mysqli_num_rows($result_ivr_calls);

    $row_ivr_calls = mysqli_fetch_assoc($result_ivr_calls);
} else {
    die("Error executing the query: " . mysqli_error($wallboard));
}

mysqli_free_result($result_ivr_calls);

// Calls Waiting OK
$query_waiting_call = "SELECT COUNT(`status`) AS total FROM vicidial_auto_calls WHERE `status` = 'IVR'";
$result_waiting_call = mysqli_query($wallboard, $query_waiting_call);


if ($result_waiting_call) {

    $totalRows_waiting_call = mysqli_num_rows($result_waiting_call);


    $row_waiting_call = mysqli_fetch_assoc($result_waiting_call);
} else {

    die("Error executing the query: " . mysqli_error($wallboard));
}


mysqli_free_result($result_waiting_call);

// Calls Ringing
$query_calling = "SELECT COUNT(`call_type`) AS total FROM vicidial_auto_calls WHERE `status` = 'START'";
$calling = mysqli_query($wallboard, $query_calling);


if ($calling) {

    $totalRow_calling = mysqli_num_rows($calling);


    $row_calling = mysqli_fetch_assoc($calling);
} else {

    die("Error executing the query: " . mysqli_error($wallboard));
}


mysqli_free_result($calling);


// Agents on Call OK
$query_agents_in_call = "SELECT COUNT(`user`) AS total FROM vicidial_live_agents WHERE `status` = 'INCALL'";
$row_agent_in_call = mysqli_query($wallboard, $query_agents_in_call);


if ($row_agent_in_call) {

    $totalRow_agent_in_call = mysqli_num_rows($row_agent_in_call);


    $row_agent_in_call = mysqli_fetch_assoc($row_agent_in_call);
} else {

    die("Error executing the query: " . mysqli_error($wallboard));
}


mysqli_free_result($row_agent_in_call);


// Agents Available OK
$query_agents_wait = "SELECT COUNT(`user`) AS total FROM vicidial_live_agents WHERE `status` IN('READY','CLOSER')";
$row_agent_waiting = mysqli_query($wallboard, $query_agents_wait);


if ($row_agent_waiting) {

    $totalRow_agent_waiting = mysqli_num_rows($row_agent_waiting);


    $row_agent_waiting = mysqli_fetch_assoc($row_agent_waiting);
} else {

    die("Error executing the query: " . mysqli_error($wallboard));
}


mysqli_free_result($row_agent_waiting);

// Agents on Pause OK
$query_agents_paused = "SELECT COUNT(`user`) AS total FROM vicidial_live_agents WHERE `status` = 'PAUSED'";
$row_paused_agents = mysqli_query($wallboard, $query_agents_paused);


if ($row_paused_agents) {

    $totalRow_agent_waiting = mysqli_num_rows($row_paused_agents);


    $row_paused_agents = mysqli_fetch_assoc($row_paused_agents);
} else {

    die("Error executing the query: " . mysqli_error($wallboard));
}


mysqli_free_result($row_paused_agents);


// Inbound Total Calls
$query_inbound_calls = "SELECT COUNT(`status`) AS total FROM vicidial_closer_log WHERE call_date BETWEEN DATE_FORMAT(NOW(), '%Y-%m-%d 00:00:00') AND DATE_FORMAT(NOW(), '%Y-%m-%d 23:59:59')";

$row_total_inbound = mysqli_query($wallboard, $query_inbound_calls);


if ($row_total_inbound) {

    $totalRow_total_inbound = mysqli_num_rows($row_total_inbound);


    $row_total_inbound = mysqli_fetch_assoc($row_total_inbound);
} else {

    die("Error executing the query: " . mysqli_error($wallboard));
}


mysqli_free_result($row_total_inbound);

// Inbound Answered Calls
$query_inbound_answered = "SELECT COUNT(`status`) AS total FROM vicidial_closer_log WHERE call_date BETWEEN DATE_FORMAT(NOW(), '%Y-%m-%d 00:00:00') AND DATE_FORMAT(NOW(), '%Y-%m-%d 23:59:59') AND 
vicidial_closer_log.`status` NOT IN('DROP','XDROP','HXFER','QVMAIL','HOLDTO','LIVE','QUEUE','TIMEOT','AFTHRS','NANQUE','IQNANQ','INBND','MAXCAL')";
$row_answered_inbound = mysqli_query($wallboard, $query_inbound_answered);


if ($row_answered_inbound) {

    $totalRow_total_inbound = mysqli_num_rows($row_answered_inbound);


    $row_answered_inbound = mysqli_fetch_assoc($row_answered_inbound);
} else {

    die("Error executing the query: " . mysqli_error($wallboard));
}


mysqli_free_result($row_answered_inbound);


// Inbound Drop Calls
$query_inbound_drop = "SELECT COUNT(`status`) AS total FROM vicidial_closer_log WHERE vicidial_closer_log.`call_date` BETWEEN DATE_FORMAT(NOW(), '%Y-%m-%d 00:00:00') AND DATE_FORMAT(NOW(), '%Y-%m-%d 23:59:59') 
AND vicidial_closer_log.`status` IN ('DROP','XDROP')";

$row_drop_inbound = mysqli_query($wallboard, $query_inbound_drop);


if ($row_drop_inbound) {

    $totalRow_drop_inbound = mysqli_num_rows($row_drop_inbound);


    $row_drop_inbound = mysqli_fetch_assoc($row_drop_inbound);
} else {

    die("Error executing the query: " . mysqli_error($wallboard));
}


mysqli_free_result($row_drop_inbound);


// Outbound Total Calls OK

$query_calls_today = "SELECT COUNT(uniqueid) AS total FROM vicidial_log WHERE vicidial_log.`call_date` BETWEEN DATE_FORMAT(NOW(), '%Y-%m-%d 00:00:00') AND DATE_FORMAT(NOW(), '%Y-%m-%d 23:59:59')";

$row_calls_result  = mysqli_query($wallboard, $query_calls_today);

if ($row_calls_result) {
    $totalRow_calls_today = mysqli_num_rows($row_calls_today);
    $row_calls_today = mysqli_fetch_assoc($row_calls_result);
} else {

    die("Error executing the query: " . mysqli_error($wallboard));
}


mysqli_free_result($row_calls_result);


// Outbound Answered Calls
$query_answered_calls = "SELECT COUNT(`status`) AS total FROM vicidial_log WHERE vicidial_log.`call_date` BETWEEN DATE_FORMAT(NOW(), '%Y-%m-%d 00:00:00') AND DATE_FORMAT(NOW(), '%Y-%m-%d 23:59:59')";

$row_answered_calls = mysqli_query($wallboard, $query_answered_calls);


if ($row_answered_calls) {

    $totalRow_answered_calls = mysqli_num_rows($row_answered_calls);


    $row_answered_calls = mysqli_fetch_assoc($row_answered_calls);
} else {

    die("Error executing the query: " . mysqli_error($wallboard));
}


mysqli_free_result($row_answered_calls);


// Outbound Drop Calls Today
$query_drop_calls_today = "SELECT COUNT(`uniqueid`) AS total FROM vicidial_log WHERE `call_date` BETWEEN DATE_FORMAT(NOW(), '%Y-%m-%d 00:00:00') AND DATE_FORMAT(NOW(), '%Y-%m-%d 23:59:59') AND `status` = 'DROP'";

$row_drop_calls_today = mysqli_query($wallboard, $query_drop_calls_today);


if ($row_drop_calls_today) {

    $totalRow_drop_calls_today = mysqli_num_rows($row_drop_calls_today);


    $row_drop_calls_today = mysqli_fetch_assoc($row_drop_calls_today);
} else {

    die("Error executing the query: " . mysqli_error($wallboard));
}


mysqli_free_result($row_drop_calls_today);

// Agents on DEAD
$query_agents_paused = "SELECT COUNT(`user`) AS total FROM vicidial_live_agents WHERE `status` = 'DEAD'";
$row_dead_agent = mysqli_query($wallboard, $query_agents_paused);


if ($row_dead_agent) {

    $totalRow_dead_agent = mysqli_num_rows($row_dead_agent);


    $row_dead_agent = mysqli_fetch_assoc($row_dead_agent);
} else {

    die("Error executing the query: " . mysqli_error($wallboard));
}


mysqli_free_result($row_dead_agent);



?>

<style>
  html, body{
      overflow:hidden;
  }
  .col-xs-15 {
    width: 20%;
    float: left;
    }
    @media (min-width: 768px) {
    .col-sm-15 {
            width: 20%;
            float: left;
        }
    }
    @media (min-width: 992px) {
        .col-md-15 {
            width: 20%;
            float: left;
        }
    }
    @media (min-width: 1200px) {
        .col-lg-15 {
            width: 20%;
            float: left;
        }
    }

  .row div{
      height:230px;
      border: solid 2px;
      color: #FFF;
      font-weight:bold;
      text-align:center;
      padding-top: 45px;
      text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.80);
  }

  .counter{
      font-size:80px;
      display:block;
      margin-bottom: -15px;
  }

  .label{
      font-size:28px;
      font-weight:lighter;
  }

  .info{
      background: #0499d1; /*00c2ed*/
      /*background: linear-gradient(141deg, #0fb8ad 0%, #1fc8db 51%, #2cb5e8 75%);*/
  }

  .hold{
      background: #ff6501;
      /*background: linear-gradient(141deg, #B8800F 0%, #DB951F 51%, #E8A02C 75%);*/

  }

  .drop{
      background: #b50005;
      /*background: linear-gradient(141deg, #B80F49 0%, #DB3F1F 51%, #D22525 75%);*/

  }

  .dead{
      background: #000000;
      /*background: linear-gradient(141deg, #B80F49 0%, #DB3F1F 51%, #D22525 75%);*/

  }

  .answer{
      background: #019c10;
      /*background: linear-gradient(141deg, #0FB876 0%, #1FDB81 51%, #2CE87B 75%);*/

  }

  .glyphicon, .wi{
      font-size:40px;
      position: absolute;
      right: 25px;
      top: 15px;
      opacity: 0.3;
  }

  </style>
    <div class="row">
      <div class="col-lg-3 hold">
        <span class="counter"><?php echo date("h:i A"); ?></span>
        <span class="label"><?php echo date("l, m/d/Y"); ?></span>
      </div>
      <div class="col-lg-3 answer">
        <span class="counter"><?php echo $row_active['total']; ?></span>
        <span class="label">Live Calls</span>
        <span class="glyphicon glyphicon-heart" aria-hidden="true"></span>
      </div>
      <div class="col-lg-3 info">
        <span class="counter"><?php echo $row_waiting_call['total']; ?></span>
        <span class="label">Calls in IVR</span>
        <span class="glyphicon glyphicon-th" aria-hidden="true"></span>
      </div>
      <div class="col-lg-3 hold">
        <span class="glyphicon glyphicon-time" aria-hidden="true"></span>
        <span class="counter"><?php echo $row_ivr_calls['total']; ?></span>
        <span class="label">Calls Waiting</span>
      </div>

    </div>

    <div class="row">
      <div class="col-lg-3 answer">
        <span class="glyphicon glyphicon-phone-alt" aria-hidden="true"></span>
        <span class="counter"><?php echo $row_calling['total']; ?></span>
        <span class="label">Calls Ringing</span>
      </div>
      <div class="col-lg-3 answer">
        <span class="glyphicon glyphicon-headphones" aria-hidden="true"></span>
        <span class="counter"><?php echo $row_agent_in_call['total']; ?></span>
        <span class="label">Agents on Call</span>
      </div>
      <div class="col-lg-3 answer">
        <span class="glyphicon glyphicon-time" aria-hidden="true"></span>
        <span class="counter"><?php echo $row_agent_waiting['total']; ?></span>
        <span class="label">Agents Available</span>
      </div>
     <div class="col-lg-3 hold">
        <span class="glyphicon glyphicon-pause" aria-hidden="true"></span>
        <span class="counter"><?php echo $row_paused_agents['total']; ?></span>
        <span class="label">Agents on Pause</span>
      </div>
    </div>

    <div class="row">
      <div class="col-lg-3 answer">
        <span class="glyphicon glyphicon-arrow-right" aria-hidden="true"></span>
        <span class="counter"><?php echo $row_total_inbound['total']; ?></span>
        <span class="label">Inbound Calls</span>
      </div>
      <div class="col-lg-3 answer">
        <span class="glyphicon glyphicon-star" aria-hidden="true"></span>
        <span class="counter"><?php echo $row_answered_inbound['total']; ?></span>
        <span class="label">Inbound Answered Calls</span>
      </div>
      <div class="col-lg-3 drop">
        <span class="glyphicon glyphicon-arrow-down" aria-hidden="true"></span>
        <span class="counter"><?php echo $row_drop_inbound['total']; ?></span>
        <span class="label">Inbound Drop Calls</span>
      </div>
      <div class="col-lg-3 drop">
        <span class="glyphicon glyphicon-stats" aria-hidden="true"></span>
        <span class="counter"><?php echo round(($row_drop_inbound['total'] * 100)/$row_total_inbound['total'], 2); ?>%</span>
        <span class="label">Drop Percent</span>
      </div>
    </div>

    <div class="row">
      <div class="col-lg-3 answer">
        <span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span>
        <span class="counter"><?php echo $row_calls_today['total']; ?></span>
        <span class="label">Outbound Calls</span>
      </div>
      <div class="col-lg-3 answer">
        <span class="glyphicon glyphicon-star" aria-hidden="true"></span>
        <span class="counter"><?php echo $row_answered_calls['total']; ?></span>
        <span class="label">Outbound Answered Calls</span>
      </div>
      <div class="col-lg-3 drop">
        <span class="glyphicon glyphicon-arrow-down" aria-hidden="true"></span>
        <span class="counter"><?php echo $row_drop_calls_today['total']; ?></span>
        <span class="label">Outbound Drop Calls</span>
      </div>
      <div class="col-lg-3 drop">
        <span class="glyphicon glyphicon-stats" aria-hidden="true"></span>
        <span class="counter"><?php echo round(($row_drop_calls_today['total_calls'] * 100)/$row_answered_calls['total'], 2); ?>%</span>
        <span class="label">Drop Percent</span>
      </div>
            <!---<div class="col-lg-3 dead">
        <span class="glyphicon glyphicon-pause" aria-hidden="true"></span>
        <span class="counter"><?php echo $row_dead_agent['total']; ?></span>
        <span class="label">Dead</span>
      </div>--->
    </div>



    <?php
mysqli_close($wallboard);
?>
