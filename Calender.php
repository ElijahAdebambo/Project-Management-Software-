<?php
include 'connection.php';
// session_start();

?>

<!DOCTYPE html>
<html>
<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #c8d5dd;
        color: #333;
    }

    .header {
        background-color: #c8d5dd;
        padding: 10px 0;
        text-align: center;
        color: #fff;
    }

    #calendar {
        max-width: 900px;
        margin: 40px auto;
        padding: 0 10px;
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,.1);
    }

    .fc-header-toolbar {
        background: #4CAF50;
        color: #fff;
        padding: 10px;
        border-top-left-radius: 8px;
        border-top-right-radius: 8px;
    }

    .fc-button {
        background: #f5f5f5;
        color: #333;
        border: 1px solid #ddd;
    }

    .fc-button:hover {
        background: #e8e8e8;
    }

    .fc-today {
        background-color: #eaf6f4 !important;
    }

    .fc-event, .fc-event-dot {
        background-color: #4CAF50 !important;
        border-color: #4CAF50 !important;
    }

    .fc-event .fc-title, .fc-event-dot {
        color: #fff;
    }
</style>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Developer Calendar</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link href='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.css' rel='stylesheet' />
    <script src='https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js'></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js'></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.js'></script>
    <script>
        $(document).ready(function() {
            $('#calendar').fullCalendar({
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month,agendaWeek,agendaDay'
                },
                selectable: true,
                selectHelper: true,
                select: function(start, end) {
                    var title = prompt('Event Title:');
                    if (title) {
                        var eventData = {
                            title: title,
                            start: start,
                            end: end
                        };
                        $('#calendar').fullCalendar('renderEvent', eventData, true);
                    }
                    $('#calendar').fullCalendar('unselect');
                },
                editable: true,
                events: 'fetch_events.php'
            });
        });
    </script>
</head>
<body style="background-color:#c8d5dd;">

<div class="header" style="background-color: #c8d5dd;">
<div class="navcontainer">
        <div class="header-right">  
                      <a href="adminhome.php">Admin Dashboard</a>  
           <a href="userhome.php">User Dashboard</a>    
            <a href="workitemanalytics.php">Analytics</a>
		<a href="developerpipeline.php">Pipeline</a>
		
            <a href="registeredaccounts.php">Registered Accounts</a>
            <a href="logout.php">Logout</a>
        </div>
        <div class="header-bottom">
            <h2>Developer Calendar</h2>
        </div>
    </div>
</div>

<center><h3><b><?php echo htmlspecialchars($_SESSION["username"]); ?> </b>is logged in</h3></center>

<div id='calendar' style="margin-top: 20px;"></div>

<?php include('footer.php'); ?>

</body>
</html>
