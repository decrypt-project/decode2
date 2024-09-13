<?php

namespace PHPMaker2023\decryptweb23;

use Slim\Views\PhpRenderer;
use Slim\Csrf\Guard;
use Slim\HttpCache\CacheProvider;
use Slim\Flash\Messages;
use Psr\Container\ContainerInterface;
use Monolog\Logger;
use Monolog\Handler\RotatingFileHandler;
use Doctrine\DBAL\Logging\LoggerChain;
use Doctrine\DBAL\Logging\DebugStack;

return [
    "cache" => \DI\create(CacheProvider::class),
    "flash" => fn(ContainerInterface $c) => new Messages(),
    "view" => fn(ContainerInterface $c) => new PhpRenderer($GLOBALS["RELATIVE_PATH"] . "views/"),
    "audit" => fn(ContainerInterface $c) => (new Logger("audit"))->pushHandler(new AuditTrailHandler("/data/decrypt-logs/audit.log")), // For audit trail
    "log" => fn(ContainerInterface $c) => (new Logger("log"))->pushHandler(new RotatingFileHandler($GLOBALS["RELATIVE_PATH"] . "/data/decrypt-logs/log.log")),
    "sqllogger" => function (ContainerInterface $c) {
        $loggers = [];
        if (Config("DEBUG")) {
            $loggers[] = $c->get("debugstack");
        }
        return (count($loggers) > 0) ? new LoggerChain($loggers) : null;
    },
    "csrf" => fn(ContainerInterface $c) => new Guard($GLOBALS["ResponseFactory"], Config("CSRF_PREFIX")),
    "debugstack" => \DI\create(DebugStack::class),
    "debugsqllogger" => \DI\create(DebugSqlLogger::class),
    "security" => \DI\create(AdvancedSecurity::class),
    "profile" => \DI\create(UserProfile::class),
    "language" => \DI\create(Language::class),
    "timer" => \DI\create(Timer::class),
    "session" => \DI\create(HttpSession::class),

    // Tables
    "core_lang" => \DI\create(CoreLang::class),
    "core_content" => \DI\create(CoreContent::class),
    "project" => \DI\create(Project::class),
    "users" => \DI\create(Users::class),
    "audit_trail" => \DI\create(AuditTrail::class),
    "images" => \DI\create(Images::class),
    "tool" => \DI\create(Tool::class),
    "toolconfigkey" => \DI\create(Toolconfigkey::class),
    "transcriptions" => \DI\create(Transcriptions::class),
    "run_transcribe" => \DI\create(RunTranscribe::class),
    "run_cryptool" => \DI\create(RunCryptool::class),
    "documents" => \DI\create(Documents::class),
    "records" => \DI\create(Records::class),
    "associated_records" => \DI\create(AssociatedRecords::class),
    "record_group" => \DI\create(RecordGroup::class),
    "record_in_project" => \DI\create(RecordInProject::class),
    "cleartext_langs" => \DI\create(CleartextLangs::class),
    "plaintext_langs" => \DI\create(PlaintextLangs::class),
    "file_in_project" => \DI\create(FileInProject::class),
    "Documents_by_century" => \DI\create(DocumentsByCentury::class),
    "recordstat" => \DI\create(Recordstat::class),
    "recordview" => \DI\create(Recordview::class),
    "data_in_project" => \DI\create(DataInProject::class),

    // User table
    "usertable" => \DI\get("users"),
];
