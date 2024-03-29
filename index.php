<?php
date_default_timezone_set('America/New_York');
//set default value
$message = '';

//get value from POST array
$action = filter_input(INPUT_POST, 'action');
if ($action === NULL) {
    $action = 'start_app';
}

//process
switch ($action) {
    case 'start_app':
        
        // set default invoice date 1 month prior to current date
        $interval = new DateInterval('P1M');
        $default_date = new DateTime();
        $default_date->sub($interval);
        $invoice_date_s = $default_date->format('n/j/Y');

        // set default due date 2 months after current date
        $interval = new DateInterval('P2M');
        $default_date = new DateTime();
        $default_date->add($interval);
        $due_date_s = $default_date->format('n/j/Y');
    
        $message = 'Enter two dates and click on the Submit button.';
        break;
    case 'process_data':
        $invoice_date_s = filter_input(INPUT_POST, 'invoice_date');
        $due_date_s = filter_input(INPUT_POST, 'due_date');

        // make sure the user enters both dates
        if(empty(trim($invoice_date_s)) || empty(trim($due_date_s))){
            $message = "Please fill both Invoice and Due Date.";
            break;
        }
        // convert date strings to DateTime objects
        // and use a try/catch to make sure the dates are valid
        try {
            $invoice = new DateTime($invoice_date_s);
            $due = new DateTime($due_date_s);
        } catch (Exception $e) {
            $message = "Please enter valid date formats.";
            break;
        }
        // make sure the due date is after the invoice date
        if ($invoice > $due) {
            $message = "Please make sure the Due Date is after the Invoice Date.";
            break;
        }
        // format both dates
        $invoice_date_f = $invoice->format('F d\, Y');
        $due_date_f = $due->format('F d\, Y');
        
        // get the current date and time and format it
        $current = new DateTime();
        $current_date_f = $current->format('F d\, Y');
        $current_time_f = $current->format('g:i:s a');
        
        // get the amount of time between the current date and the due date
        // and format the due date message
        if($current < $due){
             $interval = $current->diff($due);
             $due_date_message = 'This invoice is due in ' . $interval->format('%Y years, %m months, and %d days.');
        }else{
            $interval = $due->diff($current);
            $due_date_message = 'This invoice is ' . $interval->format('%Y years, %m months, and %d days overdue.');
        }
        //$due_date_message = 'not implemented yet';

        break;
}
include 'date_tester.php';
?>