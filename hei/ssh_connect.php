<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include phpseclib (you should install it using Composer)
require 'vendor/autoload.php';

use phpseclib3\Net\SSH2;
use phpseclib3\Crypt\PublicKeyLoader;

// SSH server details
$host = '10.196.243.25';  // SSH server IP
$port = 22;                // Standard SSH port
$username = 'ubuntu';      // SSH username
$private_key_path = '/Users/davidvalderhaug/.ssh/id_ed25519';  // Path to private key

// Load the private key for authentication
$private_key = PublicKeyLoader::load(file_get_contents($private_key_path));

// Create an SSH connection
$ssh = new SSH2($host, $port);

// Attempt to login using the provided username and private key
if (!$ssh->login($username, $private_key)) {
    // If login fails, display the error
    die('❌ SSH-tilkobling feilet. Feil: ' . $ssh->getLastError());
}

// If login is successful, execute the `ls -la` command to list files in the home directory
echo "✅ Tilkoblet til $host som $username!<br>";
echo "📂 Innhold i hjemmemappen:<br>";
echo "<pre>" . $ssh->exec('ls -la') . "</pre>";

// Disconnect from the SSH server
$ssh->disconnect();
?>
