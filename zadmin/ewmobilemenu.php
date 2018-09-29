<!-- Begin Main Menu -->
<?php

// Generate all menu items
$RootMenu->IsRoot = TRUE;
$RootMenu->AddMenuItem(5, "mmi_plans", $Language->MenuPhrase("5", "MenuText"), "planslist.php", -1, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(3, "mmi_news", $Language->MenuPhrase("3", "MenuText"), "newslist.php", -1, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(2, "mmi_gallery", $Language->MenuPhrase("2", "MenuText"), "gallerylist.php", -1, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(7, "mmi_progress", $Language->MenuPhrase("7", "MenuText"), "progresslist.php", -1, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(10, "mmi_slide", $Language->MenuPhrase("10", "MenuText"), "slidelist.php", -1, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(-1, "mmi_logout", $Language->Phrase("Logout"), "logout.php", -1, "", IsLoggedIn());
$RootMenu->AddMenuItem(-1, "mmi_login", $Language->Phrase("Login"), "login.php", -1, "", !IsLoggedIn() && substr(@$_SERVER["URL"], -1 * strlen("login.php")) <> "login.php");
$RootMenu->Render();
?>
<!-- End Main Menu -->
