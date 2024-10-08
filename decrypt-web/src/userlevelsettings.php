<?php
/**
 * PHPMaker 2023 user level settings
 */
namespace PHPMaker2023\decryptweb23;

// User level info
$USER_LEVELS = [["-2","Anonymous"],
    ["0","Default"],
    ["1","Manager"],
    ["2","Reader"]];
// User level priv info
$USER_LEVEL_PRIVS = [["{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}core_lang","-2","0"],
    ["{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}core_lang","0","0"],
    ["{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}core_lang","1","0"],
    ["{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}core_lang","2","0"],
    ["{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}core_content","-2","0"],
    ["{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}core_content","0","0"],
    ["{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}core_content","1","0"],
    ["{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}core_content","2","0"],
    ["{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}project","-2","0"],
    ["{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}project","0","0"],
    ["{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}project","1","367"],
    ["{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}project","2","301"],
    ["{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}users","-2","0"],
    ["{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}users","0","0"],
    ["{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}users","1","256"],
    ["{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}users","2","0"],
    ["{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}audit_trail","-2","0"],
    ["{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}audit_trail","0","0"],
    ["{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}audit_trail","1","0"],
    ["{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}audit_trail","2","0"],
    ["{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}images","-2","0"],
    ["{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}images","0","0"],
    ["{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}images","1","367"],
    ["{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}images","2","360"],
    ["{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}tool","-2","0"],
    ["{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}tool","0","0"],
    ["{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}tool","1","0"],
    ["{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}tool","2","360"],
    ["{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}toolconfig","-2","0"],
    ["{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}toolconfig","0","0"],
    ["{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}toolconfig","1","0"],
    ["{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}toolconfig","2","360"],
    ["{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}toolconfigkey","-2","0"],
    ["{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}toolconfigkey","0","0"],
    ["{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}toolconfigkey","1","0"],
    ["{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}toolconfigkey","2","360"],
    ["{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}transcriptions","-2","0"],
    ["{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}transcriptions","0","0"],
    ["{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}transcriptions","1","0"],
    ["{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}transcriptions","2","360"],
    ["{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}run_transcribe.php","-2","0"],
    ["{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}run_transcribe.php","0","0"],
    ["{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}run_transcribe.php","1","360"],
    ["{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}run_transcribe.php","2","360"],
    ["{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}run_cryptool.php","-2","0"],
    ["{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}run_cryptool.php","0","0"],
    ["{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}run_cryptool.php","1","360"],
    ["{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}run_cryptool.php","2","360"],
    ["{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}documents","-2","0"],
    ["{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}documents","0","264"],
    ["{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}documents","1","367"],
    ["{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}documents","2","360"],
    ["{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}records","-2","104"],
    ["{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}records","0","360"],
    ["{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}records","1","493"],
    ["{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}records","2","360"],
    ["{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}associated_records","-2","0"],
    ["{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}associated_records","0","360"],
    ["{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}associated_records","1","367"],
    ["{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}associated_records","2","360"],
    ["{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}record_group","-2","0"],
    ["{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}record_group","0","360"],
    ["{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}record_group","1","367"],
    ["{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}record_group","2","360"],
    ["{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}run_decoder.php","-2","0"],
    ["{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}run_decoder.php","0","0"],
    ["{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}run_decoder.php","1","360"],
    ["{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}run_decoder.php","2","360"],
    ["{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}record_in_project","-2","0"],
    ["{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}record_in_project","0","0"],
    ["{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}record_in_project","1","367"],
    ["{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}record_in_project","2","367"],
    ["{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}cleartext_langs","-2","0"],
    ["{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}cleartext_langs","0","264"],
    ["{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}cleartext_langs","1","367"],
    ["{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}cleartext_langs","2","360"],
    ["{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}plaintext_langs","-2","0"],
    ["{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}plaintext_langs","0","264"],
    ["{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}plaintext_langs","1","367"],
    ["{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}plaintext_langs","2","360"],
    ["{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}file_in_project","-2","0"],
    ["{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}file_in_project","0","0"],
    ["{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}file_in_project","1","367"],
    ["{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}file_in_project","2","367"],
    ["{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}Documents by century","-2","72"],
    ["{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}Documents by century","0","72"],
    ["{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}Documents by century","1","72"],
    ["{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}Documents by century","2","72"],
    ["{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}recordstat","-2","0"],
    ["{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}recordstat","0","104"],
    ["{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}recordstat","1","1128"],
    ["{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}recordstat","2","360"],
    ["{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}recordview","-2","0"],
    ["{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}recordview","0","104"],
    ["{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}recordview","1","1128"],
    ["{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}recordview","2","360"],
    ["{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}data_in_project","-2","0"],
    ["{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}data_in_project","0","0"],
    ["{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}data_in_project","1","367"],
    ["{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}data_in_project","2","111"]];
// User level table info
$USER_LEVEL_TABLES = [["core_lang","core_lang","Web Text Elements",true,"{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}","CoreLangList"],
    ["core_content","core_content","Web Pages",true,"{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}","CoreContentList"],
    ["project","project","Projects",true,"{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}","ProjectList"],
    ["users","users","Users",true,"{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}","UsersList"],
    ["audit_trail","audit_trail","Audit Trail",true,"{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}","AuditTrailList"],
    ["images","images","Images",true,"{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}","ImagesList"],
    ["tool","tool","Tools",true,"{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}",""],
    ["toolconfig","toolconfig","Tool Configurations",true,"{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}",""],
    ["toolconfigkey","toolconfigkey","Tool Configuration Keys",true,"{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}","ToolconfigkeyList"],
    ["transcriptions","transcriptions","Transcriptions",true,"{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}","TranscriptionsList"],
    ["run_transcribe.php","run_transcribe","Transcribe",true,"{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}","RunTranscribe"],
    ["run_cryptool.php","run_cryptool","Run Cryptool",true,"{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}","RunCryptool"],
    ["documents","documents","Documents",true,"{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}","DocumentsList"],
    ["records","records","DECODE Records",true,"{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}","RecordsList"],
    ["associated_records","associated_records","Associated<br/>Records",true,"{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}","AssociatedRecordsList"],
    ["record_group","record_group","record group",true,"{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}","RecordGroupList"],
    ["run_decoder.php","run_decoder","Decoder v2",true,"{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}",""],
    ["record_in_project","record_in_project","Associated Records",true,"{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}","RecordInProjectList"],
    ["cleartext_langs","cleartext_langs","cleartext langs",true,"{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}","CleartextLangsList"],
    ["plaintext_langs","plaintext_langs","plaintext langs",true,"{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}","PlaintextLangsList"],
    ["file_in_project","file_in_project","Project files",true,"{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}","FileInProjectList"],
    ["Documents by century","Documents_by_century","Documents by century",true,"{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}","DocumentsByCentury"],
    ["recordstat","recordstat","recordstat",true,"{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}","RecordstatList"],
    ["recordview","recordview","Expert View",true,"{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}","RecordviewList"],
    ["data_in_project","data_in_project","Project Data",true,"{2B52C73A-BC7B-41A1-8D8F-163D871E4FCA}","DataInProjectList"]];
