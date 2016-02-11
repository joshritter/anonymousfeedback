<?

require 'settings.inc.php';
@require 'lib/database.class.php';

$dbh = new database($settings['host'], $settings['user'], $settings['pwd'], $settings['port'], $settings['name']);

$date_str = date("F j, Y, ");
$date_str = "$date_str". "% PST";
$dbh->sqlQuery("SELECT count(id) as count FROM conversations WHERE time LIKE ?", array($date_str));
$conversations = $dbh->fetchRow();
$conversations = $conversations['count'];
//"March 1, 2011, % PST";
$date = date("m/d/Y");
$date = "$date";
//create table `stats` ( `date` varchar(12) NOT NULL, `conversations` int(11), primary key(`date`));
$dbh->sqlQuery("INSERT INTO stats (date, conversations) VALUES (?, ?)", array($date, $conversations));
?>
