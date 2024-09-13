<?php

namespace PHPMaker2023\decryptweb23;

// Menu Language
if ($Language && function_exists(PROJECT_NAMESPACE . "Config") && $Language->LanguageFolder == Config("LANGUAGE_FOLDER")) {
    $MenuRelativePath = "";
    $MenuLanguage = &$Language;
} else { // Compat reports
    $LANGUAGE_FOLDER = "../lang/";
    $MenuRelativePath = "../";
    $MenuLanguage = Container("language");
}

// Navbar menu
$topMenu = new Menu("navbar", true, true);
$topMenu->addMenuItem(21, "mi_records", $MenuLanguage->MenuPhrase("21", "MenuText"), $MenuRelativePath . "RecordsList", -1, "", AllowListMenu('{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}records'), false, false, "", "", true, false);
$topMenu->addMenuItem(74, "mci_List/Search_Records", $MenuLanguage->MenuPhrase("74", "MenuText"), $MenuRelativePath . "RecordsList", 21, "", true, false, true, "", "", true, false);
$topMenu->addMenuItem(58, "mci_Create_New_Record", $MenuLanguage->MenuPhrase("58", "MenuText"), $MenuRelativePath . "RecordsAdd", 21, "", IsLoggedIn(), false, true, "", "", true, false);
$topMenu->addMenuItem(20, "mi_documents", $MenuLanguage->MenuPhrase("20", "MenuText"), $MenuRelativePath . "DocumentsList?cmd=resetall", 21, "", AllowListMenu('{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}documents'), false, false, "", "", true, false);
$topMenu->addMenuItem(9, "mi_images", $MenuLanguage->MenuPhrase("9", "MenuText"), $MenuRelativePath . "ImagesList?cmd=resetall", 21, "", AllowListMenu('{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}images'), false, false, "", "", true, false);
$topMenu->addMenuItem(78, "mi_recordview", $MenuLanguage->MenuPhrase("78", "MenuText"), $MenuRelativePath . "RecordviewList", -1, "", AllowListMenu('{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}recordview'), false, false, "", "", true, false);
$topMenu->addMenuItem(76, "mi_Documents_by_century", $MenuLanguage->MenuPhrase("76", "MenuText"), $MenuRelativePath . "DocumentsByCentury", -1, "", AllowListMenu('{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}Documents by century'), false, false, "", "", true, false);
$topMenu->addMenuItem(3, "mi_project", $MenuLanguage->MenuPhrase("3", "MenuText"), $MenuRelativePath . "ProjectList", -1, "", AllowListMenu('{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}project'), false, false, "", "", true, false);
$topMenu->addMenuItem(15, "mci_Administration", $MenuLanguage->MenuPhrase("15", "MenuText"), "", -1, "", IsLoggedIn(), false, true, "", "", true, false);
$topMenu->addMenuItem(5, "mi_audit_trail", $MenuLanguage->MenuPhrase("5", "MenuText"), $MenuRelativePath . "AuditTrailList", 15, "", AllowListMenu('{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}audit_trail'), false, false, "", "", true, false);
$topMenu->addMenuItem(4, "mi_users", $MenuLanguage->MenuPhrase("4", "MenuText"), $MenuRelativePath . "UsersList", 15, "", AllowListMenu('{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}users'), false, false, "", "", true, false);
$topMenu->addMenuItem(2, "mi_core_content", $MenuLanguage->MenuPhrase("2", "MenuText"), $MenuRelativePath . "CoreContentList", 15, "", AllowListMenu('{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}core_content'), false, false, "", "", true, false);
$topMenu->addMenuItem(1, "mi_core_lang", $MenuLanguage->MenuPhrase("1", "MenuText"), $MenuRelativePath . "CoreLangList", 15, "", AllowListMenu('{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}core_lang'), false, false, "", "", true, false);
$topMenu->addMenuItem(26, "mi_record_group", $MenuLanguage->MenuPhrase("26", "MenuText"), $MenuRelativePath . "RecordGroupList", 15, "", AllowListMenu('{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}record_group'), false, false, "", "", true, false);
echo $topMenu->toScript();

// Sidebar menu
$sideMenu = new Menu("menu", true, false);
$sideMenu->addMenuItem(21, "mi_records", $MenuLanguage->MenuPhrase("21", "MenuText"), $MenuRelativePath . "RecordsList", -1, "", AllowListMenu('{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}records'), false, false, "", "", true, true);
$sideMenu->addMenuItem(74, "mci_List/Search_Records", $MenuLanguage->MenuPhrase("74", "MenuText"), $MenuRelativePath . "RecordsList", 21, "", true, false, true, "", "", true, true);
$sideMenu->addMenuItem(58, "mci_Create_New_Record", $MenuLanguage->MenuPhrase("58", "MenuText"), $MenuRelativePath . "RecordsAdd", 21, "", IsLoggedIn(), false, true, "", "", true, true);
$sideMenu->addMenuItem(20, "mi_documents", $MenuLanguage->MenuPhrase("20", "MenuText"), $MenuRelativePath . "DocumentsList?cmd=resetall", 21, "", AllowListMenu('{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}documents'), false, false, "", "", true, true);
$sideMenu->addMenuItem(9, "mi_images", $MenuLanguage->MenuPhrase("9", "MenuText"), $MenuRelativePath . "ImagesList?cmd=resetall", 21, "", AllowListMenu('{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}images'), false, false, "", "", true, true);
$sideMenu->addMenuItem(78, "mi_recordview", $MenuLanguage->MenuPhrase("78", "MenuText"), $MenuRelativePath . "RecordviewList", -1, "", AllowListMenu('{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}recordview'), false, false, "", "", true, true);
$sideMenu->addMenuItem(76, "mi_Documents_by_century", $MenuLanguage->MenuPhrase("76", "MenuText"), $MenuRelativePath . "DocumentsByCentury", -1, "", AllowListMenu('{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}Documents by century'), false, false, "", "", true, true);
$sideMenu->addMenuItem(3, "mi_project", $MenuLanguage->MenuPhrase("3", "MenuText"), $MenuRelativePath . "ProjectList", -1, "", AllowListMenu('{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}project'), false, false, "", "", true, true);
$sideMenu->addMenuItem(15, "mci_Administration", $MenuLanguage->MenuPhrase("15", "MenuText"), "", -1, "", IsLoggedIn(), false, true, "", "", true, true);
$sideMenu->addMenuItem(5, "mi_audit_trail", $MenuLanguage->MenuPhrase("5", "MenuText"), $MenuRelativePath . "AuditTrailList", 15, "", AllowListMenu('{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}audit_trail'), false, false, "", "", true, true);
$sideMenu->addMenuItem(4, "mi_users", $MenuLanguage->MenuPhrase("4", "MenuText"), $MenuRelativePath . "UsersList", 15, "", AllowListMenu('{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}users'), false, false, "", "", true, true);
$sideMenu->addMenuItem(2, "mi_core_content", $MenuLanguage->MenuPhrase("2", "MenuText"), $MenuRelativePath . "CoreContentList", 15, "", AllowListMenu('{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}core_content'), false, false, "", "", true, true);
$sideMenu->addMenuItem(1, "mi_core_lang", $MenuLanguage->MenuPhrase("1", "MenuText"), $MenuRelativePath . "CoreLangList", 15, "", AllowListMenu('{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}core_lang'), false, false, "", "", true, true);
$sideMenu->addMenuItem(26, "mi_record_group", $MenuLanguage->MenuPhrase("26", "MenuText"), $MenuRelativePath . "RecordGroupList", 15, "", AllowListMenu('{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}record_group'), false, false, "", "", true, true);
echo $sideMenu->toScript();
