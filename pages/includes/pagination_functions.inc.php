<?php 

require_once('../../mysqli_connect.inc.php');


function getPagesValue($columnname, $tablename, $where = '') {
	global $dbc;


	// Determine number of pages there are
	if (isset($_GET['p']) && is_numeric($_GET['p'])) { // Already determined
		$pages = $_GET['p'];
	} else { // Need to be determined

		// Count the number of records
		$query = "SELECT COUNT($columnname) FROM $tablename $where";
		$result = mysqli_query($dbc, $query);
		$row = mysqli_fetch_array($result, MYSQLI_NUM);
		$records = $row[0];
		// Calculate the number of pages
		if($records > DISPLAY) { // More than 1 Page
			$pages = ceil($records / DISPLAY);
		} else { // 1 Page
			$pages = 1;
		}

	}
	return $pages;

}

function getStartValue() {
	// Determine where in the database to start returning results
	if (isset($_GET['s']) && is_numeric($_GET['s'])) { // Set in the url
		$start = $_GET['s']; // Start at s
	} else { // Not set in the url
		$start = 0; // Start from the beginning
	}

	return $start;

}

function getSortValue() {
	$sort = isset($_GET['sort']) ? $_GET['sort'] : 'rd'; // Define a sort variable to determine how query results are to be ordered

	// Determine how the results should be ordered
	switch ($sort) {
		case 'ln':
			$order_by = 'last_name ASC';
			break;
		case 'fn':
			$order_by = 'first_name ASC';
			break;
		case 'rd':
			$order_by = 'registration_date ASC';
			break;
		default: 
			$order_by = 'registration_date ASC';
			$sort = 'rd';
			break;
	}

	return [$sort, $order_by];
}

function setPreviousAndNextLinks($pageName) {
	global $pages;
	global $start; 
	global $sort;

	// Make the links to other pages, if necessary
	if ($pages > 1) {

		// Add some spacing and start a paragraph
		echo '<br><p>';

		// Determine what page the script is on
		$current_page = ($start / DISPLAY) + 1;

		// If it's not the first page, make a Previous button
		if($current_page != 1) {
			echo '<a href = "'. $pageName . '?s=' . ($start - DISPLAY) . '&p=' . $pages . '&sort=' .  $sort . '">Previous</a> ';
		}

		// Make all the numbered pages
		for ($i = 1; $i <= $pages; $i++) {
			if ($i != $current_page) {
				echo '<a href = "'. $pageName . '?s=' . (DISPLAY * ($i - 1)) . '&p=' . $pages . '&sort=' . $sort . '">' . $i . '</a> ';
			} else {
				echo $i . ' ';
			}
		}

		// If i's not the last page, make a Next button
		if ($current_page != $pages) {
			echo '<a href = "' . $pageName . '?s=' . ($start + DISPLAY) . '&p=' . $pages . '&sort=' . $sort . '">Next</a>';
		}

		echo '</p>';
	}
}

function performPaginationQuery($query, $order_by, $start, $dbc) {

	// Define the query
	$query = "$query ORDER BY $order_by LIMIT $start, " . DISPLAY;
	$result = mysqli_query($dbc, $query);

	return $result;
}


?>