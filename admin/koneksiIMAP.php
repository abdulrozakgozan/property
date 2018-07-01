<?Php 
$hostname = '{mail.andalusia-website.com:993/imap/ssl}';
$username = 'admin@andalusia-website.com';
$password = 'wearethebest4web';

/* try to connect */
$connection = imap_open($hostname,$username,$password) or die('Cannot connect to Webmail: ' . imap_last_error());
$mailboxesList = imap_list($connection, $hostname, '*');
$MC = imap_check($connection);

// Fetch an overview for all messages in INBOX
$result = imap_fetch_overview($connection,"1:{$MC->Nmsgs}",0);
foreach ($result as $overview) {
    echo "#{$overview->msgno} ({$overview->date}) - From: {$overview->from}
    {$overview->subject}\n";
}

//contoh hasil outputnya :#1 (Wed, 13 Jun 2018 02:37:12 GMT) - From: "cPanel on andalusia-website.com" =?UTF-8?B?W2FuZGFsdXNpYS13ZWJzaXRlLmNvbV0gRW1haWwgY29uZmlndXJh?= =?UTF-8?B?dGlvbiBzZXR0aW5ncyBmb3Ig4oCcYWRtaW5AYW5kYWx1c2lhLXdlYnNpdGUu?= =?UTF-8?B?Y29t4oCdLg==?= 
imap_close($connection);

?>