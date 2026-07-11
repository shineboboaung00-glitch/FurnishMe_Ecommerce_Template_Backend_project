<?php
$servername = "localhost";
$username = "root";
$password = ""; // WAMP မှာ default password ကို ဖန်တီးထားလျှင် ထည့်ပါ။
$database = "final_db"; // သင့် database name အရင်ဖန်တီးထားဖို့လိုပါတယ်

// Create connection
$connection = new mysqli($servername, $username, $password, $database);
