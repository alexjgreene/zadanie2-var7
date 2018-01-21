<?php
$dbParams = require (
	'db.php'
);

$db=new PDO(
	"mysql: host=localhost; dbname=".
	$dbParams['database'].
	"; charset=utf8",
	$dbParams['username'],
	$dbParams['password']
); 
$marksSql = '
	SELECT `student`.`lastName`,`student`.`firstName`,`student`.`patronymicName`,`subject`.`name`,`mark`.`value`,`mark`.`markDate` FROM `mark`
	INNER JOIN `student` ON `student`.`id` = `mark`.`studentId`
	INNER JOIN `course` ON `mark`.`courseId` = `course`.`id`
	INNER JOIN `subject` ON `course`.`subjectId` = `subject`.`id`
';

$marksQuery= $db
	-> prepare($marksSql);

$marksQuery
	-> execute();
$marks = $marksQuery
	-> fetchAll (PDO :: FETCH_ASSOC);
?>
<html>
	<body>
		<?php
		$daysOfWeek = require('days.php');
		?>
		<table border=1 cellspacing=0>
			<tr>
				<th>ФИО</th>
				<th>Дисциплина</th>
				<th>Оценка</th>
				<th>Дата выставления</th>
			</tr>
		<?php
			foreach ($marks as $mark) {
				?>
				<tr>
					<td>
				<?php
				echo htmlspecialchars ($mark ['lastName'].' '.$mark ['firstName'].' '.$mark ['patronymicName']);
				?>
					</td>
					<td>
				<?php
				echo htmlspecialchars ($mark ['name']);
				?>
					</td>
					<td>
				<?php
				echo '<center>'.htmlspecialchars ($mark ['value']).'</center>';
				?>
					</td>
					<td>
				<?php
				$date = DateTime::createFromFormat('Y-m-d', $mark['markDate']);
				$dayOfWeek = $date->format('D');
				echo $date->format('d.m.Y').' ('.$daysOfWeek[$dayOfWeek].')';
				?>
					</td>
				</tr>
				<?php
			}
		?>
		</table>
	</body>
</html>

	