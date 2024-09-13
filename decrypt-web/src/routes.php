<?php

namespace PHPMaker2023\decryptweb23;

use Slim\App;
use Slim\Routing\RouteCollectorProxy;
use Slim\Exception\HttpNotFoundException;

// Handle Routes
return function (App $app) {
    // core_lang
    $app->map(["GET","POST","OPTIONS"], '/CoreLangList[/{id}]', CoreLangController::class . ':list')->add(PermissionMiddleware::class)->setName('CoreLangList-core_lang-list'); // list
    $app->map(["GET","POST","OPTIONS"], '/CoreLangAdd[/{id}]', CoreLangController::class . ':add')->add(PermissionMiddleware::class)->setName('CoreLangAdd-core_lang-add'); // add
    $app->map(["GET","POST","OPTIONS"], '/CoreLangView[/{id}]', CoreLangController::class . ':view')->add(PermissionMiddleware::class)->setName('CoreLangView-core_lang-view'); // view
    $app->map(["GET","POST","OPTIONS"], '/CoreLangEdit[/{id}]', CoreLangController::class . ':edit')->add(PermissionMiddleware::class)->setName('CoreLangEdit-core_lang-edit'); // edit
    $app->map(["GET","POST","OPTIONS"], '/CoreLangDelete[/{id}]', CoreLangController::class . ':delete')->add(PermissionMiddleware::class)->setName('CoreLangDelete-core_lang-delete'); // delete
    $app->group(
        '/core_lang',
        function (RouteCollectorProxy $group) {
            $group->map(["GET","POST","OPTIONS"], '/' . Config('LIST_ACTION') . '[/{id}]', CoreLangController::class . ':list')->add(PermissionMiddleware::class)->setName('core_lang/list-core_lang-list-2'); // list
            $group->map(["GET","POST","OPTIONS"], '/' . Config('ADD_ACTION') . '[/{id}]', CoreLangController::class . ':add')->add(PermissionMiddleware::class)->setName('core_lang/add-core_lang-add-2'); // add
            $group->map(["GET","POST","OPTIONS"], '/' . Config('VIEW_ACTION') . '[/{id}]', CoreLangController::class . ':view')->add(PermissionMiddleware::class)->setName('core_lang/view-core_lang-view-2'); // view
            $group->map(["GET","POST","OPTIONS"], '/' . Config('EDIT_ACTION') . '[/{id}]', CoreLangController::class . ':edit')->add(PermissionMiddleware::class)->setName('core_lang/edit-core_lang-edit-2'); // edit
            $group->map(["GET","POST","OPTIONS"], '/' . Config('DELETE_ACTION') . '[/{id}]', CoreLangController::class . ':delete')->add(PermissionMiddleware::class)->setName('core_lang/delete-core_lang-delete-2'); // delete
        }
    );

    // core_content
    $app->map(["GET","POST","OPTIONS"], '/CoreContentList[/{id}]', CoreContentController::class . ':list')->add(PermissionMiddleware::class)->setName('CoreContentList-core_content-list'); // list
    $app->map(["GET","POST","OPTIONS"], '/CoreContentAdd[/{id}]', CoreContentController::class . ':add')->add(PermissionMiddleware::class)->setName('CoreContentAdd-core_content-add'); // add
    $app->map(["GET","POST","OPTIONS"], '/CoreContentView[/{id}]', CoreContentController::class . ':view')->add(PermissionMiddleware::class)->setName('CoreContentView-core_content-view'); // view
    $app->map(["GET","POST","OPTIONS"], '/CoreContentEdit[/{id}]', CoreContentController::class . ':edit')->add(PermissionMiddleware::class)->setName('CoreContentEdit-core_content-edit'); // edit
    $app->map(["GET","POST","OPTIONS"], '/CoreContentDelete[/{id}]', CoreContentController::class . ':delete')->add(PermissionMiddleware::class)->setName('CoreContentDelete-core_content-delete'); // delete
    $app->group(
        '/core_content',
        function (RouteCollectorProxy $group) {
            $group->map(["GET","POST","OPTIONS"], '/' . Config('LIST_ACTION') . '[/{id}]', CoreContentController::class . ':list')->add(PermissionMiddleware::class)->setName('core_content/list-core_content-list-2'); // list
            $group->map(["GET","POST","OPTIONS"], '/' . Config('ADD_ACTION') . '[/{id}]', CoreContentController::class . ':add')->add(PermissionMiddleware::class)->setName('core_content/add-core_content-add-2'); // add
            $group->map(["GET","POST","OPTIONS"], '/' . Config('VIEW_ACTION') . '[/{id}]', CoreContentController::class . ':view')->add(PermissionMiddleware::class)->setName('core_content/view-core_content-view-2'); // view
            $group->map(["GET","POST","OPTIONS"], '/' . Config('EDIT_ACTION') . '[/{id}]', CoreContentController::class . ':edit')->add(PermissionMiddleware::class)->setName('core_content/edit-core_content-edit-2'); // edit
            $group->map(["GET","POST","OPTIONS"], '/' . Config('DELETE_ACTION') . '[/{id}]', CoreContentController::class . ':delete')->add(PermissionMiddleware::class)->setName('core_content/delete-core_content-delete-2'); // delete
        }
    );

    // project
    $app->map(["GET","POST","OPTIONS"], '/ProjectList[/{id}]', ProjectController::class . ':list')->add(PermissionMiddleware::class)->setName('ProjectList-project-list'); // list
    $app->map(["GET","POST","OPTIONS"], '/ProjectAdd[/{id}]', ProjectController::class . ':add')->add(PermissionMiddleware::class)->setName('ProjectAdd-project-add'); // add
    $app->map(["GET","POST","OPTIONS"], '/ProjectView[/{id}]', ProjectController::class . ':view')->add(PermissionMiddleware::class)->setName('ProjectView-project-view'); // view
    $app->map(["GET","POST","OPTIONS"], '/ProjectEdit[/{id}]', ProjectController::class . ':edit')->add(PermissionMiddleware::class)->setName('ProjectEdit-project-edit'); // edit
    $app->map(["GET","POST","OPTIONS"], '/ProjectDelete[/{id}]', ProjectController::class . ':delete')->add(PermissionMiddleware::class)->setName('ProjectDelete-project-delete'); // delete
    $app->group(
        '/project',
        function (RouteCollectorProxy $group) {
            $group->map(["GET","POST","OPTIONS"], '/' . Config('LIST_ACTION') . '[/{id}]', ProjectController::class . ':list')->add(PermissionMiddleware::class)->setName('project/list-project-list-2'); // list
            $group->map(["GET","POST","OPTIONS"], '/' . Config('ADD_ACTION') . '[/{id}]', ProjectController::class . ':add')->add(PermissionMiddleware::class)->setName('project/add-project-add-2'); // add
            $group->map(["GET","POST","OPTIONS"], '/' . Config('VIEW_ACTION') . '[/{id}]', ProjectController::class . ':view')->add(PermissionMiddleware::class)->setName('project/view-project-view-2'); // view
            $group->map(["GET","POST","OPTIONS"], '/' . Config('EDIT_ACTION') . '[/{id}]', ProjectController::class . ':edit')->add(PermissionMiddleware::class)->setName('project/edit-project-edit-2'); // edit
            $group->map(["GET","POST","OPTIONS"], '/' . Config('DELETE_ACTION') . '[/{id}]', ProjectController::class . ':delete')->add(PermissionMiddleware::class)->setName('project/delete-project-delete-2'); // delete
        }
    );

    // users
    $app->map(["GET","POST","OPTIONS"], '/UsersList[/{id}]', UsersController::class . ':list')->add(PermissionMiddleware::class)->setName('UsersList-users-list'); // list
    $app->map(["GET","POST","OPTIONS"], '/UsersAdd[/{id}]', UsersController::class . ':add')->add(PermissionMiddleware::class)->setName('UsersAdd-users-add'); // add
    $app->map(["GET","POST","OPTIONS"], '/UsersView[/{id}]', UsersController::class . ':view')->add(PermissionMiddleware::class)->setName('UsersView-users-view'); // view
    $app->map(["GET","POST","OPTIONS"], '/UsersEdit[/{id}]', UsersController::class . ':edit')->add(PermissionMiddleware::class)->setName('UsersEdit-users-edit'); // edit
    $app->map(["GET","POST","OPTIONS"], '/UsersDelete[/{id}]', UsersController::class . ':delete')->add(PermissionMiddleware::class)->setName('UsersDelete-users-delete'); // delete
    $app->group(
        '/users',
        function (RouteCollectorProxy $group) {
            $group->map(["GET","POST","OPTIONS"], '/' . Config('LIST_ACTION') . '[/{id}]', UsersController::class . ':list')->add(PermissionMiddleware::class)->setName('users/list-users-list-2'); // list
            $group->map(["GET","POST","OPTIONS"], '/' . Config('ADD_ACTION') . '[/{id}]', UsersController::class . ':add')->add(PermissionMiddleware::class)->setName('users/add-users-add-2'); // add
            $group->map(["GET","POST","OPTIONS"], '/' . Config('VIEW_ACTION') . '[/{id}]', UsersController::class . ':view')->add(PermissionMiddleware::class)->setName('users/view-users-view-2'); // view
            $group->map(["GET","POST","OPTIONS"], '/' . Config('EDIT_ACTION') . '[/{id}]', UsersController::class . ':edit')->add(PermissionMiddleware::class)->setName('users/edit-users-edit-2'); // edit
            $group->map(["GET","POST","OPTIONS"], '/' . Config('DELETE_ACTION') . '[/{id}]', UsersController::class . ':delete')->add(PermissionMiddleware::class)->setName('users/delete-users-delete-2'); // delete
        }
    );

    // audit_trail
    $app->map(["GET","POST","OPTIONS"], '/AuditTrailList[/{id}]', AuditTrailController::class . ':list')->add(PermissionMiddleware::class)->setName('AuditTrailList-audit_trail-list'); // list
    $app->map(["GET","POST","OPTIONS"], '/AuditTrailAdd[/{id}]', AuditTrailController::class . ':add')->add(PermissionMiddleware::class)->setName('AuditTrailAdd-audit_trail-add'); // add
    $app->map(["GET","POST","OPTIONS"], '/AuditTrailView[/{id}]', AuditTrailController::class . ':view')->add(PermissionMiddleware::class)->setName('AuditTrailView-audit_trail-view'); // view
    $app->map(["GET","POST","OPTIONS"], '/AuditTrailEdit[/{id}]', AuditTrailController::class . ':edit')->add(PermissionMiddleware::class)->setName('AuditTrailEdit-audit_trail-edit'); // edit
    $app->map(["GET","POST","OPTIONS"], '/AuditTrailDelete[/{id}]', AuditTrailController::class . ':delete')->add(PermissionMiddleware::class)->setName('AuditTrailDelete-audit_trail-delete'); // delete
    $app->group(
        '/audit_trail',
        function (RouteCollectorProxy $group) {
            $group->map(["GET","POST","OPTIONS"], '/' . Config('LIST_ACTION') . '[/{id}]', AuditTrailController::class . ':list')->add(PermissionMiddleware::class)->setName('audit_trail/list-audit_trail-list-2'); // list
            $group->map(["GET","POST","OPTIONS"], '/' . Config('ADD_ACTION') . '[/{id}]', AuditTrailController::class . ':add')->add(PermissionMiddleware::class)->setName('audit_trail/add-audit_trail-add-2'); // add
            $group->map(["GET","POST","OPTIONS"], '/' . Config('VIEW_ACTION') . '[/{id}]', AuditTrailController::class . ':view')->add(PermissionMiddleware::class)->setName('audit_trail/view-audit_trail-view-2'); // view
            $group->map(["GET","POST","OPTIONS"], '/' . Config('EDIT_ACTION') . '[/{id}]', AuditTrailController::class . ':edit')->add(PermissionMiddleware::class)->setName('audit_trail/edit-audit_trail-edit-2'); // edit
            $group->map(["GET","POST","OPTIONS"], '/' . Config('DELETE_ACTION') . '[/{id}]', AuditTrailController::class . ':delete')->add(PermissionMiddleware::class)->setName('audit_trail/delete-audit_trail-delete-2'); // delete
        }
    );

    // images
    $app->map(["GET","POST","OPTIONS"], '/ImagesList[/{id}]', ImagesController::class . ':list')->add(PermissionMiddleware::class)->setName('ImagesList-images-list'); // list
    $app->map(["GET","POST","OPTIONS"], '/ImagesAdd[/{id}]', ImagesController::class . ':add')->add(PermissionMiddleware::class)->setName('ImagesAdd-images-add'); // add
    $app->map(["GET","POST","OPTIONS"], '/ImagesView[/{id}]', ImagesController::class . ':view')->add(PermissionMiddleware::class)->setName('ImagesView-images-view'); // view
    $app->map(["GET","POST","OPTIONS"], '/ImagesEdit[/{id}]', ImagesController::class . ':edit')->add(PermissionMiddleware::class)->setName('ImagesEdit-images-edit'); // edit
    $app->map(["GET","POST","OPTIONS"], '/ImagesDelete[/{id}]', ImagesController::class . ':delete')->add(PermissionMiddleware::class)->setName('ImagesDelete-images-delete'); // delete
    $app->group(
        '/images',
        function (RouteCollectorProxy $group) {
            $group->map(["GET","POST","OPTIONS"], '/' . Config('LIST_ACTION') . '[/{id}]', ImagesController::class . ':list')->add(PermissionMiddleware::class)->setName('images/list-images-list-2'); // list
            $group->map(["GET","POST","OPTIONS"], '/' . Config('ADD_ACTION') . '[/{id}]', ImagesController::class . ':add')->add(PermissionMiddleware::class)->setName('images/add-images-add-2'); // add
            $group->map(["GET","POST","OPTIONS"], '/' . Config('VIEW_ACTION') . '[/{id}]', ImagesController::class . ':view')->add(PermissionMiddleware::class)->setName('images/view-images-view-2'); // view
            $group->map(["GET","POST","OPTIONS"], '/' . Config('EDIT_ACTION') . '[/{id}]', ImagesController::class . ':edit')->add(PermissionMiddleware::class)->setName('images/edit-images-edit-2'); // edit
            $group->map(["GET","POST","OPTIONS"], '/' . Config('DELETE_ACTION') . '[/{id}]', ImagesController::class . ':delete')->add(PermissionMiddleware::class)->setName('images/delete-images-delete-2'); // delete
        }
    );

    // toolconfigkey
    $app->map(["GET","POST","OPTIONS"], '/ToolconfigkeyList[/{id}]', ToolconfigkeyController::class . ':list')->add(PermissionMiddleware::class)->setName('ToolconfigkeyList-toolconfigkey-list'); // list
    $app->map(["GET","POST","OPTIONS"], '/ToolconfigkeyAdd[/{id}]', ToolconfigkeyController::class . ':add')->add(PermissionMiddleware::class)->setName('ToolconfigkeyAdd-toolconfigkey-add'); // add
    $app->map(["GET","POST","OPTIONS"], '/ToolconfigkeyView[/{id}]', ToolconfigkeyController::class . ':view')->add(PermissionMiddleware::class)->setName('ToolconfigkeyView-toolconfigkey-view'); // view
    $app->map(["GET","POST","OPTIONS"], '/ToolconfigkeyEdit[/{id}]', ToolconfigkeyController::class . ':edit')->add(PermissionMiddleware::class)->setName('ToolconfigkeyEdit-toolconfigkey-edit'); // edit
    $app->map(["GET","POST","OPTIONS"], '/ToolconfigkeyDelete[/{id}]', ToolconfigkeyController::class . ':delete')->add(PermissionMiddleware::class)->setName('ToolconfigkeyDelete-toolconfigkey-delete'); // delete
    $app->group(
        '/toolconfigkey',
        function (RouteCollectorProxy $group) {
            $group->map(["GET","POST","OPTIONS"], '/' . Config('LIST_ACTION') . '[/{id}]', ToolconfigkeyController::class . ':list')->add(PermissionMiddleware::class)->setName('toolconfigkey/list-toolconfigkey-list-2'); // list
            $group->map(["GET","POST","OPTIONS"], '/' . Config('ADD_ACTION') . '[/{id}]', ToolconfigkeyController::class . ':add')->add(PermissionMiddleware::class)->setName('toolconfigkey/add-toolconfigkey-add-2'); // add
            $group->map(["GET","POST","OPTIONS"], '/' . Config('VIEW_ACTION') . '[/{id}]', ToolconfigkeyController::class . ':view')->add(PermissionMiddleware::class)->setName('toolconfigkey/view-toolconfigkey-view-2'); // view
            $group->map(["GET","POST","OPTIONS"], '/' . Config('EDIT_ACTION') . '[/{id}]', ToolconfigkeyController::class . ':edit')->add(PermissionMiddleware::class)->setName('toolconfigkey/edit-toolconfigkey-edit-2'); // edit
            $group->map(["GET","POST","OPTIONS"], '/' . Config('DELETE_ACTION') . '[/{id}]', ToolconfigkeyController::class . ':delete')->add(PermissionMiddleware::class)->setName('toolconfigkey/delete-toolconfigkey-delete-2'); // delete
        }
    );

    // transcriptions
    $app->map(["GET","POST","OPTIONS"], '/TranscriptionsList[/{id}]', TranscriptionsController::class . ':list')->add(PermissionMiddleware::class)->setName('TranscriptionsList-transcriptions-list'); // list
    $app->map(["GET","POST","OPTIONS"], '/TranscriptionsAdd[/{id}]', TranscriptionsController::class . ':add')->add(PermissionMiddleware::class)->setName('TranscriptionsAdd-transcriptions-add'); // add
    $app->map(["GET","POST","OPTIONS"], '/TranscriptionsView[/{id}]', TranscriptionsController::class . ':view')->add(PermissionMiddleware::class)->setName('TranscriptionsView-transcriptions-view'); // view
    $app->map(["GET","POST","OPTIONS"], '/TranscriptionsEdit[/{id}]', TranscriptionsController::class . ':edit')->add(PermissionMiddleware::class)->setName('TranscriptionsEdit-transcriptions-edit'); // edit
    $app->map(["GET","POST","OPTIONS"], '/TranscriptionsDelete[/{id}]', TranscriptionsController::class . ':delete')->add(PermissionMiddleware::class)->setName('TranscriptionsDelete-transcriptions-delete'); // delete
    $app->group(
        '/transcriptions',
        function (RouteCollectorProxy $group) {
            $group->map(["GET","POST","OPTIONS"], '/' . Config('LIST_ACTION') . '[/{id}]', TranscriptionsController::class . ':list')->add(PermissionMiddleware::class)->setName('transcriptions/list-transcriptions-list-2'); // list
            $group->map(["GET","POST","OPTIONS"], '/' . Config('ADD_ACTION') . '[/{id}]', TranscriptionsController::class . ':add')->add(PermissionMiddleware::class)->setName('transcriptions/add-transcriptions-add-2'); // add
            $group->map(["GET","POST","OPTIONS"], '/' . Config('VIEW_ACTION') . '[/{id}]', TranscriptionsController::class . ':view')->add(PermissionMiddleware::class)->setName('transcriptions/view-transcriptions-view-2'); // view
            $group->map(["GET","POST","OPTIONS"], '/' . Config('EDIT_ACTION') . '[/{id}]', TranscriptionsController::class . ':edit')->add(PermissionMiddleware::class)->setName('transcriptions/edit-transcriptions-edit-2'); // edit
            $group->map(["GET","POST","OPTIONS"], '/' . Config('DELETE_ACTION') . '[/{id}]', TranscriptionsController::class . ':delete')->add(PermissionMiddleware::class)->setName('transcriptions/delete-transcriptions-delete-2'); // delete
        }
    );

    // run_transcribe
    $app->map(["GET", "POST", "OPTIONS"], '/RunTranscribe[/{params:.*}]', RunTranscribeController::class . ':custom')->add(PermissionMiddleware::class)->setName('RunTranscribe-run_transcribe-custom'); // custom

    // run_cryptool
    $app->map(["GET", "POST", "OPTIONS"], '/RunCryptool[/{params:.*}]', RunCryptoolController::class . ':custom')->add(PermissionMiddleware::class)->setName('RunCryptool-run_cryptool-custom'); // custom

    // documents
    $app->map(["GET","POST","OPTIONS"], '/DocumentsList[/{id}]', DocumentsController::class . ':list')->add(PermissionMiddleware::class)->setName('DocumentsList-documents-list'); // list
    $app->map(["GET","POST","OPTIONS"], '/DocumentsAdd[/{id}]', DocumentsController::class . ':add')->add(PermissionMiddleware::class)->setName('DocumentsAdd-documents-add'); // add
    $app->map(["GET","POST","OPTIONS"], '/DocumentsView[/{id}]', DocumentsController::class . ':view')->add(PermissionMiddleware::class)->setName('DocumentsView-documents-view'); // view
    $app->map(["GET","POST","OPTIONS"], '/DocumentsEdit[/{id}]', DocumentsController::class . ':edit')->add(PermissionMiddleware::class)->setName('DocumentsEdit-documents-edit'); // edit
    $app->map(["GET","POST","OPTIONS"], '/DocumentsDelete[/{id}]', DocumentsController::class . ':delete')->add(PermissionMiddleware::class)->setName('DocumentsDelete-documents-delete'); // delete
    $app->map(["GET","POST","OPTIONS"], '/DocumentsSearch', DocumentsController::class . ':search')->add(PermissionMiddleware::class)->setName('DocumentsSearch-documents-search'); // search
    $app->group(
        '/documents',
        function (RouteCollectorProxy $group) {
            $group->map(["GET","POST","OPTIONS"], '/' . Config('LIST_ACTION') . '[/{id}]', DocumentsController::class . ':list')->add(PermissionMiddleware::class)->setName('documents/list-documents-list-2'); // list
            $group->map(["GET","POST","OPTIONS"], '/' . Config('ADD_ACTION') . '[/{id}]', DocumentsController::class . ':add')->add(PermissionMiddleware::class)->setName('documents/add-documents-add-2'); // add
            $group->map(["GET","POST","OPTIONS"], '/' . Config('VIEW_ACTION') . '[/{id}]', DocumentsController::class . ':view')->add(PermissionMiddleware::class)->setName('documents/view-documents-view-2'); // view
            $group->map(["GET","POST","OPTIONS"], '/' . Config('EDIT_ACTION') . '[/{id}]', DocumentsController::class . ':edit')->add(PermissionMiddleware::class)->setName('documents/edit-documents-edit-2'); // edit
            $group->map(["GET","POST","OPTIONS"], '/' . Config('DELETE_ACTION') . '[/{id}]', DocumentsController::class . ':delete')->add(PermissionMiddleware::class)->setName('documents/delete-documents-delete-2'); // delete
            $group->map(["GET","POST","OPTIONS"], '/' . Config('SEARCH_ACTION') . '', DocumentsController::class . ':search')->add(PermissionMiddleware::class)->setName('documents/search-documents-search-2'); // search
        }
    );

    // records
    $app->map(["GET","POST","OPTIONS"], '/RecordsList[/{id}]', RecordsController::class . ':list')->add(PermissionMiddleware::class)->setName('RecordsList-records-list'); // list
    $app->map(["GET","POST","OPTIONS"], '/RecordsAdd[/{id}]', RecordsController::class . ':add')->add(PermissionMiddleware::class)->setName('RecordsAdd-records-add'); // add
    $app->map(["GET","POST","OPTIONS"], '/RecordsView[/{id}]', RecordsController::class . ':view')->add(PermissionMiddleware::class)->setName('RecordsView-records-view'); // view
    $app->map(["GET","POST","OPTIONS"], '/RecordsEdit[/{id}]', RecordsController::class . ':edit')->add(PermissionMiddleware::class)->setName('RecordsEdit-records-edit'); // edit
    $app->map(["GET","POST","OPTIONS"], '/RecordsDelete[/{id}]', RecordsController::class . ':delete')->add(PermissionMiddleware::class)->setName('RecordsDelete-records-delete'); // delete
    $app->map(["GET","POST","OPTIONS"], '/RecordsSearch', RecordsController::class . ':search')->add(PermissionMiddleware::class)->setName('RecordsSearch-records-search'); // search
    $app->group(
        '/records',
        function (RouteCollectorProxy $group) {
            $group->map(["GET","POST","OPTIONS"], '/' . Config('LIST_ACTION') . '[/{id}]', RecordsController::class . ':list')->add(PermissionMiddleware::class)->setName('records/list-records-list-2'); // list
            $group->map(["GET","POST","OPTIONS"], '/' . Config('ADD_ACTION') . '[/{id}]', RecordsController::class . ':add')->add(PermissionMiddleware::class)->setName('records/add-records-add-2'); // add
            $group->map(["GET","POST","OPTIONS"], '/' . Config('VIEW_ACTION') . '[/{id}]', RecordsController::class . ':view')->add(PermissionMiddleware::class)->setName('records/view-records-view-2'); // view
            $group->map(["GET","POST","OPTIONS"], '/' . Config('EDIT_ACTION') . '[/{id}]', RecordsController::class . ':edit')->add(PermissionMiddleware::class)->setName('records/edit-records-edit-2'); // edit
            $group->map(["GET","POST","OPTIONS"], '/' . Config('DELETE_ACTION') . '[/{id}]', RecordsController::class . ':delete')->add(PermissionMiddleware::class)->setName('records/delete-records-delete-2'); // delete
            $group->map(["GET","POST","OPTIONS"], '/' . Config('SEARCH_ACTION') . '', RecordsController::class . ':search')->add(PermissionMiddleware::class)->setName('records/search-records-search-2'); // search
        }
    );

    // associated_records
    $app->map(["GET","POST","OPTIONS"], '/AssociatedRecordsList', AssociatedRecordsController::class . ':list')->add(PermissionMiddleware::class)->setName('AssociatedRecordsList-associated_records-list'); // list
    $app->map(["GET","POST","OPTIONS"], '/AssociatedRecordsAdd', AssociatedRecordsController::class . ':add')->add(PermissionMiddleware::class)->setName('AssociatedRecordsAdd-associated_records-add'); // add
    $app->group(
        '/associated_records',
        function (RouteCollectorProxy $group) {
            $group->map(["GET","POST","OPTIONS"], '/' . Config('LIST_ACTION') . '', AssociatedRecordsController::class . ':list')->add(PermissionMiddleware::class)->setName('associated_records/list-associated_records-list-2'); // list
            $group->map(["GET","POST","OPTIONS"], '/' . Config('ADD_ACTION') . '', AssociatedRecordsController::class . ':add')->add(PermissionMiddleware::class)->setName('associated_records/add-associated_records-add-2'); // add
        }
    );

    // record_group
    $app->map(["GET","POST","OPTIONS"], '/RecordGroupList[/{id}]', RecordGroupController::class . ':list')->add(PermissionMiddleware::class)->setName('RecordGroupList-record_group-list'); // list
    $app->map(["GET","POST","OPTIONS"], '/RecordGroupAdd[/{id}]', RecordGroupController::class . ':add')->add(PermissionMiddleware::class)->setName('RecordGroupAdd-record_group-add'); // add
    $app->map(["GET","POST","OPTIONS"], '/RecordGroupAddopt', RecordGroupController::class . ':addopt')->add(PermissionMiddleware::class)->setName('RecordGroupAddopt-record_group-addopt'); // addopt
    $app->group(
        '/record_group',
        function (RouteCollectorProxy $group) {
            $group->map(["GET","POST","OPTIONS"], '/' . Config('LIST_ACTION') . '[/{id}]', RecordGroupController::class . ':list')->add(PermissionMiddleware::class)->setName('record_group/list-record_group-list-2'); // list
            $group->map(["GET","POST","OPTIONS"], '/' . Config('ADD_ACTION') . '[/{id}]', RecordGroupController::class . ':add')->add(PermissionMiddleware::class)->setName('record_group/add-record_group-add-2'); // add
            $group->map(["GET","POST","OPTIONS"], '/' . Config('ADDOPT_ACTION') . '', RecordGroupController::class . ':addopt')->add(PermissionMiddleware::class)->setName('record_group/addopt-record_group-addopt-2'); // addopt
        }
    );

    // record_in_project
    $app->map(["GET","POST","OPTIONS"], '/RecordInProjectList[/{id}]', RecordInProjectController::class . ':list')->add(PermissionMiddleware::class)->setName('RecordInProjectList-record_in_project-list'); // list
    $app->map(["GET","POST","OPTIONS"], '/RecordInProjectAdd[/{id}]', RecordInProjectController::class . ':add')->add(PermissionMiddleware::class)->setName('RecordInProjectAdd-record_in_project-add'); // add
    $app->map(["GET","POST","OPTIONS"], '/RecordInProjectDelete[/{id}]', RecordInProjectController::class . ':delete')->add(PermissionMiddleware::class)->setName('RecordInProjectDelete-record_in_project-delete'); // delete
    $app->group(
        '/record_in_project',
        function (RouteCollectorProxy $group) {
            $group->map(["GET","POST","OPTIONS"], '/' . Config('LIST_ACTION') . '[/{id}]', RecordInProjectController::class . ':list')->add(PermissionMiddleware::class)->setName('record_in_project/list-record_in_project-list-2'); // list
            $group->map(["GET","POST","OPTIONS"], '/' . Config('ADD_ACTION') . '[/{id}]', RecordInProjectController::class . ':add')->add(PermissionMiddleware::class)->setName('record_in_project/add-record_in_project-add-2'); // add
            $group->map(["GET","POST","OPTIONS"], '/' . Config('DELETE_ACTION') . '[/{id}]', RecordInProjectController::class . ':delete')->add(PermissionMiddleware::class)->setName('record_in_project/delete-record_in_project-delete-2'); // delete
        }
    );

    // cleartext_langs
    $app->map(["GET","POST","OPTIONS"], '/CleartextLangsList[/{id}]', CleartextLangsController::class . ':list')->add(PermissionMiddleware::class)->setName('CleartextLangsList-cleartext_langs-list'); // list
    $app->map(["GET","POST","OPTIONS"], '/CleartextLangsAdd[/{id}]', CleartextLangsController::class . ':add')->add(PermissionMiddleware::class)->setName('CleartextLangsAdd-cleartext_langs-add'); // add
    $app->map(["GET","POST","OPTIONS"], '/CleartextLangsAddopt', CleartextLangsController::class . ':addopt')->add(PermissionMiddleware::class)->setName('CleartextLangsAddopt-cleartext_langs-addopt'); // addopt
    $app->map(["GET","POST","OPTIONS"], '/CleartextLangsView[/{id}]', CleartextLangsController::class . ':view')->add(PermissionMiddleware::class)->setName('CleartextLangsView-cleartext_langs-view'); // view
    $app->map(["GET","POST","OPTIONS"], '/CleartextLangsEdit[/{id}]', CleartextLangsController::class . ':edit')->add(PermissionMiddleware::class)->setName('CleartextLangsEdit-cleartext_langs-edit'); // edit
    $app->map(["GET","POST","OPTIONS"], '/CleartextLangsDelete[/{id}]', CleartextLangsController::class . ':delete')->add(PermissionMiddleware::class)->setName('CleartextLangsDelete-cleartext_langs-delete'); // delete
    $app->group(
        '/cleartext_langs',
        function (RouteCollectorProxy $group) {
            $group->map(["GET","POST","OPTIONS"], '/' . Config('LIST_ACTION') . '[/{id}]', CleartextLangsController::class . ':list')->add(PermissionMiddleware::class)->setName('cleartext_langs/list-cleartext_langs-list-2'); // list
            $group->map(["GET","POST","OPTIONS"], '/' . Config('ADD_ACTION') . '[/{id}]', CleartextLangsController::class . ':add')->add(PermissionMiddleware::class)->setName('cleartext_langs/add-cleartext_langs-add-2'); // add
            $group->map(["GET","POST","OPTIONS"], '/' . Config('ADDOPT_ACTION') . '', CleartextLangsController::class . ':addopt')->add(PermissionMiddleware::class)->setName('cleartext_langs/addopt-cleartext_langs-addopt-2'); // addopt
            $group->map(["GET","POST","OPTIONS"], '/' . Config('VIEW_ACTION') . '[/{id}]', CleartextLangsController::class . ':view')->add(PermissionMiddleware::class)->setName('cleartext_langs/view-cleartext_langs-view-2'); // view
            $group->map(["GET","POST","OPTIONS"], '/' . Config('EDIT_ACTION') . '[/{id}]', CleartextLangsController::class . ':edit')->add(PermissionMiddleware::class)->setName('cleartext_langs/edit-cleartext_langs-edit-2'); // edit
            $group->map(["GET","POST","OPTIONS"], '/' . Config('DELETE_ACTION') . '[/{id}]', CleartextLangsController::class . ':delete')->add(PermissionMiddleware::class)->setName('cleartext_langs/delete-cleartext_langs-delete-2'); // delete
        }
    );

    // plaintext_langs
    $app->map(["GET","POST","OPTIONS"], '/PlaintextLangsList[/{id}]', PlaintextLangsController::class . ':list')->add(PermissionMiddleware::class)->setName('PlaintextLangsList-plaintext_langs-list'); // list
    $app->map(["GET","POST","OPTIONS"], '/PlaintextLangsAdd[/{id}]', PlaintextLangsController::class . ':add')->add(PermissionMiddleware::class)->setName('PlaintextLangsAdd-plaintext_langs-add'); // add
    $app->map(["GET","POST","OPTIONS"], '/PlaintextLangsAddopt', PlaintextLangsController::class . ':addopt')->add(PermissionMiddleware::class)->setName('PlaintextLangsAddopt-plaintext_langs-addopt'); // addopt
    $app->group(
        '/plaintext_langs',
        function (RouteCollectorProxy $group) {
            $group->map(["GET","POST","OPTIONS"], '/' . Config('LIST_ACTION') . '[/{id}]', PlaintextLangsController::class . ':list')->add(PermissionMiddleware::class)->setName('plaintext_langs/list-plaintext_langs-list-2'); // list
            $group->map(["GET","POST","OPTIONS"], '/' . Config('ADD_ACTION') . '[/{id}]', PlaintextLangsController::class . ':add')->add(PermissionMiddleware::class)->setName('plaintext_langs/add-plaintext_langs-add-2'); // add
            $group->map(["GET","POST","OPTIONS"], '/' . Config('ADDOPT_ACTION') . '', PlaintextLangsController::class . ':addopt')->add(PermissionMiddleware::class)->setName('plaintext_langs/addopt-plaintext_langs-addopt-2'); // addopt
        }
    );

    // file_in_project
    $app->map(["GET","POST","OPTIONS"], '/FileInProjectList[/{id}]', FileInProjectController::class . ':list')->add(PermissionMiddleware::class)->setName('FileInProjectList-file_in_project-list'); // list
    $app->map(["GET","POST","OPTIONS"], '/FileInProjectAdd[/{id}]', FileInProjectController::class . ':add')->add(PermissionMiddleware::class)->setName('FileInProjectAdd-file_in_project-add'); // add
    $app->map(["GET","POST","OPTIONS"], '/FileInProjectView[/{id}]', FileInProjectController::class . ':view')->add(PermissionMiddleware::class)->setName('FileInProjectView-file_in_project-view'); // view
    $app->map(["GET","POST","OPTIONS"], '/FileInProjectEdit[/{id}]', FileInProjectController::class . ':edit')->add(PermissionMiddleware::class)->setName('FileInProjectEdit-file_in_project-edit'); // edit
    $app->map(["GET","POST","OPTIONS"], '/FileInProjectDelete[/{id}]', FileInProjectController::class . ':delete')->add(PermissionMiddleware::class)->setName('FileInProjectDelete-file_in_project-delete'); // delete
    $app->group(
        '/file_in_project',
        function (RouteCollectorProxy $group) {
            $group->map(["GET","POST","OPTIONS"], '/' . Config('LIST_ACTION') . '[/{id}]', FileInProjectController::class . ':list')->add(PermissionMiddleware::class)->setName('file_in_project/list-file_in_project-list-2'); // list
            $group->map(["GET","POST","OPTIONS"], '/' . Config('ADD_ACTION') . '[/{id}]', FileInProjectController::class . ':add')->add(PermissionMiddleware::class)->setName('file_in_project/add-file_in_project-add-2'); // add
            $group->map(["GET","POST","OPTIONS"], '/' . Config('VIEW_ACTION') . '[/{id}]', FileInProjectController::class . ':view')->add(PermissionMiddleware::class)->setName('file_in_project/view-file_in_project-view-2'); // view
            $group->map(["GET","POST","OPTIONS"], '/' . Config('EDIT_ACTION') . '[/{id}]', FileInProjectController::class . ':edit')->add(PermissionMiddleware::class)->setName('file_in_project/edit-file_in_project-edit-2'); // edit
            $group->map(["GET","POST","OPTIONS"], '/' . Config('DELETE_ACTION') . '[/{id}]', FileInProjectController::class . ':delete')->add(PermissionMiddleware::class)->setName('file_in_project/delete-file_in_project-delete-2'); // delete
        }
    );

    // Documents_by_century
    $app->map(["GET", "POST", "OPTIONS"], '/DocumentsByCentury/Documentsbycentury', DocumentsByCenturyController::class . ':Documentsbycentury')->add(PermissionMiddleware::class)->setName('DocumentsByCentury-Documents_by_century-crosstab-Documentsbycentury'); // Documentsbycentury
    $app->map(["GET", "POST", "OPTIONS"], '/DocumentsByCentury', DocumentsByCenturyController::class . ':crosstab')->add(PermissionMiddleware::class)->setName('DocumentsByCentury-Documents_by_century-crosstab'); // crosstab

    // recordstat
    $app->map(["GET","POST","OPTIONS"], '/RecordstatList[/{id}]', RecordstatController::class . ':list')->add(PermissionMiddleware::class)->setName('RecordstatList-recordstat-list'); // list
    $app->group(
        '/recordstat',
        function (RouteCollectorProxy $group) {
            $group->map(["GET","POST","OPTIONS"], '/' . Config('LIST_ACTION') . '[/{id}]', RecordstatController::class . ':list')->add(PermissionMiddleware::class)->setName('recordstat/list-recordstat-list-2'); // list
        }
    );

    // recordview
    $app->map(["GET","POST","OPTIONS"], '/RecordviewList[/{id}]', RecordviewController::class . ':list')->add(PermissionMiddleware::class)->setName('RecordviewList-recordview-list'); // list
    $app->group(
        '/recordview',
        function (RouteCollectorProxy $group) {
            $group->map(["GET","POST","OPTIONS"], '/' . Config('LIST_ACTION') . '[/{id}]', RecordviewController::class . ':list')->add(PermissionMiddleware::class)->setName('recordview/list-recordview-list-2'); // list
        }
    );

    // data_in_project
    $app->map(["GET","POST","OPTIONS"], '/DataInProjectList', DataInProjectController::class . ':list')->add(PermissionMiddleware::class)->setName('DataInProjectList-data_in_project-list'); // list
    $app->map(["GET","POST","OPTIONS"], '/DataInProjectAdd', DataInProjectController::class . ':add')->add(PermissionMiddleware::class)->setName('DataInProjectAdd-data_in_project-add'); // add
    $app->group(
        '/data_in_project',
        function (RouteCollectorProxy $group) {
            $group->map(["GET","POST","OPTIONS"], '/' . Config('LIST_ACTION') . '', DataInProjectController::class . ':list')->add(PermissionMiddleware::class)->setName('data_in_project/list-data_in_project-list-2'); // list
            $group->map(["GET","POST","OPTIONS"], '/' . Config('ADD_ACTION') . '', DataInProjectController::class . ':add')->add(PermissionMiddleware::class)->setName('data_in_project/add-data_in_project-add-2'); // add
        }
    );

    // personal_data
    $app->map(["GET","POST","OPTIONS"], '/personaldata', OthersController::class . ':personaldata')->add(PermissionMiddleware::class)->setName('personaldata');

    // login
    $app->map(["GET","POST","OPTIONS"], '/login[/{provider}]', OthersController::class . ':login')->add(PermissionMiddleware::class)->setName('login');

    // reset_password
    $app->map(["GET","POST","OPTIONS"], '/resetpassword', OthersController::class . ':resetpassword')->add(PermissionMiddleware::class)->setName('resetpassword');

    // change_password
    $app->map(["GET","POST","OPTIONS"], '/changepassword', OthersController::class . ':changepassword')->add(PermissionMiddleware::class)->setName('changepassword');

    // register
    $app->map(["GET","POST","OPTIONS"], '/register', OthersController::class . ':register')->add(PermissionMiddleware::class)->setName('register');

    // logout
    $app->map(["GET","POST","OPTIONS"], '/logout', OthersController::class . ':logout')->add(PermissionMiddleware::class)->setName('logout');

    // captcha
    $app->map(["GET","OPTIONS"], '/captcha[/{page}]', OthersController::class . ':captcha')->add(PermissionMiddleware::class)->setName('captcha');

    // Swagger
    $app->get('/' . Config("SWAGGER_ACTION"), OthersController::class . ':swagger')->setName(Config("SWAGGER_ACTION")); // Swagger

    // Index
    $app->get('/[index]', OthersController::class . ':index')->add(PermissionMiddleware::class)->setName('index');

    // Route Action event
    if (function_exists(PROJECT_NAMESPACE . "Route_Action")) {
        if (Route_Action($app) === false) {
            return;
        }
    }

    /**
     * Catch-all route to serve a 404 Not Found page if none of the routes match
     * NOTE: Make sure this route is defined last.
     */
    $app->map(
        ['GET', 'POST', 'PUT', 'DELETE', 'PATCH'],
        '/{routes:.+}',
        function ($request, $response, $params) {
            throw new HttpNotFoundException($request, str_replace("%p", $params["routes"], Container("language")->phrase("PageNotFound")));
        }
    );
};
