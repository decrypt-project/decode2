<?php

namespace PHPMaker2023\decryptweb23;

use Doctrine\DBAL\FetchMode;

/**
 * Advanced Security class
 */
class AdvancedSecurity
{
    public $UserLevel = []; // All User Levels
    public $UserLevelPriv = []; // All User Level permissions
    public $UserLevelID = []; // User Level ID array
    public $UserID = []; // User ID array
    public $ParentUserID = []; // Parent User ID array
    public $CurrentUserLevelID = -2; // User Level (Anonymous by default)
    public $CurrentUserLevel; // Permissions
    public $CurrentUserID;
    private $isLoggedIn = false;
    private $isSysAdmin = false;
    private $userName;

    // Constructor
    public function __construct()
    {
        global $Security;
        $Security = $this;
        // Init User Level
        if ($this->isLoggedIn()) {
            $this->CurrentUserLevelID = $this->sessionUserLevelID();
            $this->setUserLevelID($this->CurrentUserLevelID);
        } else { // Anonymous user
            $this->CurrentUserLevelID = -2;
            $this->UserLevelID[] = $this->CurrentUserLevelID;
        }
        $_SESSION[SESSION_USER_LEVEL_LIST] = $this->userLevelList();

        // Init User ID
        $this->CurrentUserID = $this->sessionUserID();
        $this->setParentUserID($this->sessionParentUserID());

        // Load user level
        $this->loadUserLevel();
    }

    // Get session User ID
    protected function sessionUserID()
    {
        return isset($_SESSION[SESSION_USER_ID]) ? strval(Session(SESSION_USER_ID)) : $this->CurrentUserID;
    }

    // Set session User ID
    protected function setSessionUserID($v)
    {
        $this->CurrentUserID = trim(strval($v));
        $_SESSION[SESSION_USER_ID] = $this->CurrentUserID;
    }

    // Get session Parent User ID
    protected function sessionParentUserID()
    {
        return isset($_SESSION[SESSION_PARENT_USER_ID]) ? strval(Session(SESSION_PARENT_USER_ID)) : $this->getParentUserID();
    }

    // Set session Parent User ID
    protected function setSessionParentUserID($v)
    {
        $this->setParentUserID($v);
        $_SESSION[SESSION_PARENT_USER_ID] = $this->getParentUserID();
    }

    // Set Parent User ID to array
    private function setParentUserID($v)
    {
        $ids = is_array($v) ? $v : explode(Config("MULTIPLE_OPTION_SEPARATOR"), strval($v));
        $this->ParentUserID = [];
    }

    // Get Parent User ID
    private function getParentUserID()
    {
        return implode(Config("MULTIPLE_OPTION_SEPARATOR"), $this->ParentUserID);
    }

    // Check if Parent User ID in array
    public function hasParentUserID($v)
    {
        $ids = is_array($v) ? $v : explode(Config("MULTIPLE_OPTION_SEPARATOR"), strval($v));
        foreach ($ids as $id) {
            if (in_array($id, $this->ParentUserID)) {
                return true;
            }
        }
        return false;
    }

    // Get session User Level ID
    protected function sessionUserLevelID()
    {
        return $_SESSION[SESSION_USER_LEVEL_ID] ?? $this->CurrentUserLevelID;
    }

    // Set session User Level ID
    protected function setSessionUserLevelID($v)
    {
        $this->CurrentUserLevelID = $v;
        $_SESSION[SESSION_USER_LEVEL_ID] = $this->CurrentUserLevelID;
        $this->setUserLevelID($v);
    }

    // Set User Level ID to array
    private function setUserLevelID($v)
    {
        $ids = is_array($v) ? $v : explode(Config("MULTIPLE_OPTION_SEPARATOR"), strval($v));
        $this->UserLevelID = [];
        foreach ($ids as $id) {
            if ((int)$id >= -2) {
                $this->UserLevelID[] = (int)$id;
            }
        }
    }

    // Check if User Level ID in array
    public function hasUserLevelID($v)
    {
        $ids = is_array($v) ? $v : explode(Config("MULTIPLE_OPTION_SEPARATOR"), strval($v));
        foreach ($ids as $id) {
            if (in_array((int)$id, $this->UserLevelID)) {
                return true;
            }
        }
        return false;
    }

    // Get session User Level
    protected function sessionUserLevel()
    {
        return isset($_SESSION[SESSION_USER_LEVEL]) ? (int)$_SESSION[SESSION_USER_LEVEL] : $this->CurrentUserLevel;
    }

    // Set session User Level
    protected function setSessionUserLevel($v)
    {
        $this->CurrentUserLevel = $v;
        $_SESSION[SESSION_USER_LEVEL] = $this->CurrentUserLevel;
    }

    // Get current user name
    public function getCurrentUserName()
    {
        return isset($_SESSION[SESSION_USER_NAME]) ? strval($_SESSION[SESSION_USER_NAME]) : $this->userName;
    }

    // Set current user name
    public function setCurrentUserName($v)
    {
        $this->userName = $v;
        $_SESSION[SESSION_USER_NAME] = $this->userName;
    }

    // Get current user name (alias)
    public function currentUserName()
    {
        return $this->getCurrentUserName();
    }

    // Current User ID
    public function currentUserID()
    {
        return $this->CurrentUserID;
    }

    // Current Parent User ID
    public function currentParentUserID()
    {
        return $this->getParentUserID();
    }

    // Current User Level ID
    public function currentUserLevelID()
    {
        return $this->CurrentUserLevelID;
    }

    // Current User Level value
    public function currentUserLevel()
    {
        return $this->CurrentUserLevel;
    }

    // Get JWT Token
    public function createJwt($minExpiry = 0, $permission = 0)
    {
        return CreateJwt(
            $this->currentUserName(),
            $this->sessionUserID(),
            $this->sessionParentUserID(),
            $this->sessionUserLevelID(),
            $minExpiry,
            $permission
        );
    }

    // Can add
    public function canAdd()
    {
        return (($this->CurrentUserLevel & ALLOW_ADD) == ALLOW_ADD);
    }

    // Set can add
    public function setCanAdd($b)
    {
        if ($b) {
            $this->CurrentUserLevel |= ALLOW_ADD;
        } else {
            $this->CurrentUserLevel ^= ALLOW_ADD;
        }
    }

    // Can delete
    public function canDelete()
    {
        return (($this->CurrentUserLevel & ALLOW_DELETE) == ALLOW_DELETE);
    }

    // Set can delete
    public function setCanDelete($b)
    {
        if ($b) {
            $this->CurrentUserLevel |= ALLOW_DELETE;
        } else {
            $this->CurrentUserLevel ^= ALLOW_DELETE;
        }
    }

    // Can edit
    public function canEdit()
    {
        return (($this->CurrentUserLevel & ALLOW_EDIT) == ALLOW_EDIT);
    }

    // Set can edit
    public function setCanEdit($b)
    {
        if ($b) {
            $this->CurrentUserLevel |= ALLOW_EDIT;
        } else {
            $this->CurrentUserLevel ^= ALLOW_EDIT;
        }
    }

    // Can view
    public function canView()
    {
        return (($this->CurrentUserLevel & ALLOW_VIEW) == ALLOW_VIEW);
    }

    // Set can view
    public function setCanView($b)
    {
        if ($b) {
            $this->CurrentUserLevel |= ALLOW_VIEW;
        } else {
            $this->CurrentUserLevel ^= ALLOW_VIEW;
        }
    }

    // Can list
    public function canList()
    {
        return (($this->CurrentUserLevel & ALLOW_LIST) == ALLOW_LIST);
    }

    // Set can list
    public function setCanList($b)
    {
        if ($b) {
            $this->CurrentUserLevel |= ALLOW_LIST;
        } else {
            $this->CurrentUserLevel ^= ALLOW_LIST;
        }
    }

    // Can search
    public function canSearch()
    {
        return (($this->CurrentUserLevel & ALLOW_SEARCH) == ALLOW_SEARCH);
    }

    // Set can search
    public function setCanSearch($b)
    {
        if ($b) {
            $this->CurrentUserLevel |= ALLOW_SEARCH;
        } else {
            $this->CurrentUserLevel ^= ALLOW_SEARCH;
        }
    }

    // Can admin
    public function canAdmin()
    {
        return (($this->CurrentUserLevel & ALLOW_ADMIN) == ALLOW_ADMIN);
    }

    // Set can admin
    public function setCanAdmin($b)
    {
        if ($b) {
            $this->CurrentUserLevel |= ALLOW_ADMIN;
        } else {
            $this->CurrentUserLevel ^= ALLOW_ADMIN;
        }
    }

    // Can import
    public function canImport()
    {
        return (($this->CurrentUserLevel & ALLOW_IMPORT) == ALLOW_IMPORT);
    }

    // Set can import
    public function setCanImport($b)
    {
        if ($b) {
            $this->CurrentUserLevel |= ALLOW_IMPORT;
        } else {
            $this->CurrentUserLevel ^= ALLOW_IMPORT;
        }
    }

    // Can lookup
    public function canLookup()
    {
        return (($this->CurrentUserLevel & ALLOW_LOOKUP) == ALLOW_LOOKUP);
    }

    // Set can lookup
    public function setCanLookup($b)
    {
        if ($b) {
            $this->CurrentUserLevel |= ALLOW_LOOKUP;
        } else {
            $this->CurrentUserLevel ^= ALLOW_LOOKUP;
        }
    }

    // Can push
    public function canPush()
    {
        return (($this->CurrentUserLevel & ALLOW_PUSH) == ALLOW_PUSH);
    }

    // Set can push
    public function setCanPush($b)
    {
        if ($b) {
            $this->CurrentUserLevel |= ALLOW_PUSH;
        } else {
            $this->CurrentUserLevel ^= ALLOW_PUSH;
        }
    }

    // Can export
    public function canExport()
    {
        return (($this->CurrentUserLevel & ALLOW_EXPORT) == ALLOW_EXPORT);
    }

    // Set can push
    public function setCanExport($b)
    {
        if ($b) {
            $this->CurrentUserLevel |= ALLOW_EXPORT;
        } else {
            $this->CurrentUserLevel ^= ALLOW_EXPORT;
        }
    }

    // Last URL
    public function lastUrl()
    {
        return ReadCookie("LastUrl");
    }

    // Save last URL
    public function saveLastUrl()
    {
        $s = CurrentUrl();
        $q = ServerVar("QUERY_STRING");
        if ($q != "") {
            $s .= "?" . $q;
        }
        if ($this->lastUrl() == $s) {
            $s = "";
        }
        if (!preg_match('/[?&]modal=1(&|$)/', $s)) { // Query string does not contain "modal=1"
            WriteCookie("LastUrl", $s);
        }
    }

    // Auto login
    public function autoLogin()
    {
        $autologin = false;
        if (!$autologin && ReadCookie("AutoLogin") == "autologin") {
            $usr = Decrypt(ReadCookie("Username"));
            $pwd = Decrypt(ReadCookie("Password"));
            if ($usr !== false && $pwd !== false) {
                $autologin = $this->validateUser($usr, $pwd, true);
            }
        }
        if (!$autologin && Config("ALLOW_LOGIN_BY_URL") && Get("username") !== null) {
            $usr = RemoveXss(Get("username"));
            $pwd = RemoveXss(Get("password"));
            $autologin = $this->validateUser($usr, $pwd, true);
        }
        if ($autologin) {
            WriteAuditLog($usr, $GLOBALS["Language"]->phrase("AuditTrailAutoLogin"), CurrentUserIP(), "", "", "", "");
        }
        return $autologin;
    }

    // Login user
    public function loginUser($userName = null, $userID = null, $parentUserID = null, $userLevel = null, $allowedPermission = 0)
    {
        if ($userName != null) {
            $this->setCurrentUserName($userName);
            $this->isLoggedIn = true;
            $_SESSION[SESSION_STATUS] = "login";
            if ($userName == Container("language")->phrase("UserAdministrator")) { // Handle language phrase as well
                $userName = Config("ADMIN_USER_NAME");
            }
            $this->isSysAdmin = $this->validateSysAdmin($userName);
        }
        if ($userID != null) {
            $this->setSessionUserID($userID);
        }
        if ($parentUserID != null) {
            $this->setSessionParentUserID($parentUserID);
        }
        if ($userLevel != null) {
            $this->setSessionUserLevelID($userLevel);
            $this->setupUserLevel();
        }
        // Set allowed permission
        $this->setAllowedPermissions($allowedPermission);
    }

    // Logout user
    public function logoutUser()
    {
        $this->isLoggedIn = false;
        $_SESSION[SESSION_STATUS] = "";
        $this->setCurrentUserName("");
        $this->setSessionUserID("");
        $this->setSessionParentUserID("");
        $this->setSessionUserLevelID(-2);
        $this->setupUserLevel();
    }

    // Validate user
    public function validateUser(&$usr, &$pwd, $autologin, $provider = "", $securitycode = "")
    {
        global $Language, $UserProfile;
        global $UserTable;
        $valid = false;
        $customValid = false;
        $providerValid = false;

        // OAuth provider
        if ($provider != "") {
            $providers = Config("AUTH_CONFIG.providers");
            if (array_key_exists($provider, $providers) && $providers[$provider]["enabled"]) {
                try {
                    $UserProfile->Provider = $provider;
                    $hybridauth = Container("hybridauth");
                    $adapter = $hybridauth->authenticate($provider); // Authenticate with the selected provider
                    $profile = $adapter->getUserProfile();
                    $UserProfile->assign($profile); // Save profile
                    $usr = $usr ?: ($UserProfile->get("email") ?? $UserProfile->get("emailaddress"));
                    $providerValid = true;
                } catch (\Throwable $e) {
                    if (Config("DEBUG")) {
                        throw new \Exception($e->getMessage());
                    }
                    return false;
                }
            } else {
                if (Config("DEBUG")) {
                    throw new \Exception("Provider for " . $provider . " not found or not enabled.");
                }
                return false;
            }
        }

        // Call User Custom Validate event
        if (Config("USE_CUSTOM_LOGIN")) {
            $customValid = $this->userCustomValidate($usr, $pwd);
        }

        // Handle provider login as custom login
        if ($providerValid) {
            $customValid = true;
        }
        if ($customValid) {
            //$_SESSION[SESSION_STATUS] = "login"; // To be setup below
            $this->setCurrentUserName($usr); // Load user name
        }

        // Check hard coded admin first
        if (!$valid) {
            $valid = $this->validateSysAdmin($usr, $pwd, $customValid);
            if ($valid) {
                $this->isLoggedIn = true;
                $_SESSION[SESSION_STATUS] = "login";
                $this->isSysAdmin = true;
                $_SESSION[SESSION_SYS_ADMIN] = 1; // System Administrator
                $this->setCurrentUserName($Language->phrase("UserAdministrator")); // Load user name
                $this->setSessionUserLevelID(-1); // System Administrator
                $this->setSessionUserID(-1); // System Administrator
                $this->setupUserLevel();
            }
        }

        // Check other users
        if (!$valid) {
            $filter = GetUserFilter(Config("LOGIN_USERNAME_FIELD_NAME"), $usr);
            $filter .= " AND " . Config("USER_ACTIVATE_FILTER");

            // User table object
            $UserTable = Container("usertable");

            // Set up filter (WHERE Clause)
            $sql = $UserTable->getSql($filter);
            if ($row = Conn($UserTable->Dbid)->fetchAssociative($sql)) {
                $valid = $customValid || ComparePassword(GetUserInfo(Config("LOGIN_PASSWORD_FIELD_NAME"), $row), $pwd);
                if ($valid) {
                    // Check two factor authentication
                    if (Config("USE_TWO_FACTOR_AUTHENTICATION")) {
                        // Check API login
                        if (IsApi()) {
                            if (SameText(Config("TWO_FACTOR_AUTHENTICATION_TYPE"), "google") && (Config("FORCE_TWO_FACTOR_AUTHENTICATION") || $UserProfile->hasUserSecret($usr, true))) { // Verify security code for Google Authenticator
                                if (!$UserProfile->verify2FACode($usr, $securitycode)) {
                                    return false;
                                }
                            }
                        } elseif (Config("FORCE_TWO_FACTOR_AUTHENTICATION") && !$UserProfile->hasUserSecret($usr, true)) { // Non API, go to 2fa page
                            return $valid;
                        }
                    }
                    $this->isLoggedIn = true;
                    $_SESSION[SESSION_STATUS] = "login";
                    $this->isSysAdmin = false;
                    $_SESSION[SESSION_SYS_ADMIN] = 0; // Non System Administrator
                    $this->setCurrentUserName(GetUserInfo(Config("LOGIN_USERNAME_FIELD_NAME"), $row)); // Load user name
                    if (GetUserInfo(Config("USER_LEVEL_FIELD_NAME"), $row) === null) {
                        $this->setSessionUserLevelID(0);
                    } else {
                        $this->setSessionUserLevelID(GetUserInfo(Config("USER_LEVEL_FIELD_NAME"), $row)); // Load User Level
                    }
                    $this->setupUserLevel();

                    // Call User Validated event
                    $UserProfile->assign($row);
                    $UserProfile->delete(Config("LOGIN_PASSWORD_FIELD_NAME")); // Delete password
                    $valid = $this->userValidated($row) !== false; // For backward compatibility

                    // Set up User Email field
                    if (!EmptyValue(Config("USER_EMAIL_FIELD_NAME"))) {
                        $UserProfile->set(Config("USER_EMAIL_FIELD_NAME"), $row[Config("USER_EMAIL_FIELD_NAME")]);
                    }

                    // Set up User Image field
                    if (!EmptyValue(Config("USER_IMAGE_FIELD_NAME"))) {
                        $imageField = $UserTable->Fields[Config("USER_IMAGE_FIELD_NAME")];
                        if ($imageField->hasMethod("getUploadPath")) {
                            $imageField->UploadPath = $imageField->getUploadPath();
                        }
                        $image = GetFileImage($imageField, $row[Config("USER_IMAGE_FIELD_NAME")], Config("USER_IMAGE_SIZE"), Config("USER_IMAGE_SIZE"), Config("USER_IMAGE_CROP"));
                        $UserProfile->set(Config("USER_PROFILE_IMAGE"), base64_encode($image)); // Save as base64 encoded
                    }
                }
            } else { // User not found in user table
                if ($customValid) { // Grant default permissions
                    $this->setSessionUserLevelID(-2); // Anonymous User Level
                    $this->setupUserLevel();
                    $row = null;
                    $customValid = $this->userValidated($row) !== false;
                }
            }
        }
        $UserProfile->save();
        if ($customValid) {
            return $customValid;
        }
        if (!$valid && !IsPasswordExpired()) {
            $this->isLoggedIn = false;
            $_SESSION[SESSION_STATUS] = ""; // Clear login status
        }
        return $valid;
    }

    // Valdiate System Administrator
    private function validateSysAdmin($userName, $password = "", $checkUserNameOnly = true)
    {
        $adminUserName = Config("ADMIN_USER_NAME");
        $adminPassword = Config("ADMIN_PASSWORD");
        if (Config("ENCRYPTION_ENABLED")) {
            try {
                $adminUserName = PhpDecrypt(Config("ADMIN_USER_NAME"));
                $adminPassword = PhpDecrypt(Config("ADMIN_PASSWORD"));
            } catch (\Defuse\Crypto\Exception\WrongKeyOrModifiedCiphertextException $e) {
                $adminUserName = Config("ADMIN_USER_NAME");
                $adminPassword = Config("ADMIN_PASSWORD");
            }
        }
        if (Config("CASE_SENSITIVE_PASSWORD")) {
            return !$checkUserNameOnly && $adminUserName === $userName && $adminPassword === $password ||
                $checkUserNameOnly && $adminUserName === $userName;
        } else {
            return !$checkUserNameOnly && SameText($adminUserName, $userName) && SameText($adminPassword, $password) ||
                $checkUserNameOnly && SameText($adminUserName, $userName);
        }
    }

    // Static User Level security
    public function setupUserLevel()
    {
        // Load user level from user level settings
        global $USER_LEVELS, $USER_LEVEL_PRIVS;
        $this->UserLevel = $USER_LEVELS;
        $this->UserLevelPriv = $USER_LEVEL_PRIVS;

        // User Level loaded event
        $this->userLevelLoaded();

        // Check permissions
        $this->checkPermissions();

        // Save the User Level to Session variable
        $this->saveUserLevel();
    }

    // Get all User Level settings from database
    public function setupUserLevelEx()
    {
        return false;
    }

    // Check permissions
    protected function checkPermissions()
    {
    }

    // Set allowed permissions
    protected function setAllowedPermissions($permission = 0)
    {
        if ($permission > 0) {
            if (is_array($this->UserLevelPriv)) {
                foreach ($this->UserLevelPriv as &$row) {
                    $priv = &$row[2];
                    if (is_numeric($priv)) {
                        $priv &= $permission;
                    }
                }
            }
        }
    }

    // Add user permission
    protected function addUserPermissionEx($userLevelName, $tableName, $userPermission)
    {
        // Get User Level ID from user name
        $userLevelID = "";
        if (is_array($this->UserLevel)) {
            foreach ($this->UserLevel as $row) {
                list($levelid, $name) = $row;
                if (SameText($userLevelName, $name)) {
                    $userLevelID = $levelid;
                    break;
                }
            }
        }
        if (is_array($this->UserLevelPriv) && $userLevelID != "") {
            $cnt = count($this->UserLevelPriv);
            for ($i = 0; $i < $cnt; $i++) {
                list($table, $levelid, $priv) = $this->UserLevelPriv[$i];
                if (SameText($table, PROJECT_ID . $tableName) && SameString($levelid, $userLevelID)) {
                    $this->UserLevelPriv[$i][2] = $priv | $userPermission; // Add permission
                    return;
                }
            }
            // Add new entry
            $this->UserLevelPriv[] = [PROJECT_ID . $tableName, $userLevelID, $userPermission];
        }
    }

    // Add user permission
    public function addUserPermission($userLevelName, $tableName, $userPermission)
    {
        $arUserLevelName = is_array($userLevelName) ? $userLevelName : [$userLevelName];
        $arTableName = is_array($tableName) ? $tableName : [$tableName];
        foreach ($arUserLevelName as $userLevelName) {
            foreach ($arTableName as $tableName) {
                $this->addUserPermissionEx($userLevelName, $tableName, $userPermission);
            }
        }
    }

    // Delete user permission
    protected function deleteUserPermissionEx($userLevelName, $tableName, $userPermission)
    {
        // Get User Level ID from user name
        $userLevelID = "";
        if (is_array($this->UserLevel)) {
            foreach ($this->UserLevel as $row) {
                list($levelid, $name) = $row;
                if (SameText($userLevelName, $name)) {
                    $userLevelID = $levelid;
                    break;
                }
            }
        }
        if (is_array($this->UserLevelPriv) && $userLevelID != "") {
            $cnt = count($this->UserLevelPriv);
            for ($i = 0; $i < $cnt; $i++) {
                list($table, $levelid, $priv) = $this->UserLevelPriv[$i];
                if (SameText($table, PROJECT_ID . $tableName) && SameString($levelid, $userLevelID)) {
                    $this->UserLevelPriv[$i][2] = $priv & ~$userPermission; // Remove permission
                    break;
                }
            }
        }
    }

    // Delete user permission
    public function deleteUserPermission($userLevelName, $tableName, $userPermission)
    {
        $arUserLevelName = is_array($userLevelName) ? $userLevelName : [$userLevelName];
        $arTableName = is_array($tableName) ? $tableName : [$tableName];
        foreach ($arUserLevelName as $userLevelName) {
            foreach ($arTableName as $tableName) {
                $this->deleteUserPermissionEx($userLevelName, $tableName, $userPermission);
            }
        }
    }

    // Load table permissions
    public function loadTablePermissions($tblVar)
    {
        $tblName = GetTableName($tblVar);
        if ($this->isLoggedIn() && method_exists($this, "tablePermissionLoading")) {
            $this->tablePermissionLoading();
        }
        $this->loadCurrentUserLevel(PROJECT_ID . $tblName);
        if ($this->isLoggedIn() && method_exists($this, "tablePermissionLoaded")) {
            $this->tablePermissionLoaded();
        }
        if ($this->isLoggedIn()) {
            if (method_exists($this, "userIDLoading")) {
                $this->userIDLoading();
            }
            if (method_exists($this, "loadUserID")) {
                $this->loadUserID();
            }
            if (method_exists($this, "userIDLoaded")) {
                $this->userIDLoaded();
            }
        }
    }

    // Load current User Level
    public function loadCurrentUserLevel($table)
    {
        // Load again if user level list changed
        if (Session(SESSION_USER_LEVEL_LIST_LOADED) != "" && Session(SESSION_USER_LEVEL_LIST_LOADED) != Session(SESSION_USER_LEVEL_LIST)) {
            $_SESSION[SESSION_USER_LEVEL_PRIVS] = "";
        }
        $this->loadUserLevel();
        $this->setSessionUserLevel($this->currentUserLevelPriv($table));
    }

    // Get current user privilege
    protected function currentUserLevelPriv($tableName)
    {
        if ($this->isLoggedIn()) {
            $priv = 0;
            foreach ($this->UserLevelID as $userLevelID) {
                $priv |= $this->getUserLevelPrivEx($tableName, $userLevelID);
            }
            return $priv;
        } else { // Anonymous
            return $this->getUserLevelPrivEx($tableName, -2);
        }
    }

    // Get User Level ID by User Level name
    public function getUserLevelID($userLevelName)
    {
        global $Language;
        if (SameString($userLevelName, "Anonymous")) {
            return -2;
        } elseif ($Language && SameString($userLevelName, $Language->phrase("UserAnonymous"))) {
            return -2;
        } elseif (SameString($userLevelName, "Administrator")) {
            return -1;
        } elseif ($Language && SameString($userLevelName, $Language->phrase("UserAdministrator"))) {
            return -1;
        } elseif (SameString($userLevelName, "Default")) {
            return 0;
        } elseif ($Language && SameString($userLevelName, $Language->phrase("UserDefault"))) {
            return 0;
        } elseif ($userLevelName != "") {
            if (is_array($this->UserLevel)) {
                foreach ($this->UserLevel as $row) {
                    list($levelid, $name) = $row;
                    if (SameString($name, $userLevelName)) {
                        return $levelid;
                    }
                }
            }
        }
        return -2; // Anonymous
    }

    // Add User Level by name
    public function addUserLevel($userLevelName)
    {
        if (strval($userLevelName) == "") {
            return;
        }
        $userLevelID = $this->getUserLevelID($userLevelName);
        $this->addUserLevelID($userLevelID);
    }

    // Add User Level by ID
    public function addUserLevelID($userLevelID)
    {
        if (!is_numeric($userLevelID)) {
            return;
        }
        if ($userLevelID < -1) {
            return;
        }
        if (!in_array($userLevelID, $this->UserLevelID)) {
            $this->UserLevelID[] = $userLevelID;
            $_SESSION[SESSION_USER_LEVEL_LIST] = $this->userLevelList(); // Update session variable
        }
    }

    // Delete User Level by name
    public function deleteUserLevel($userLevelName)
    {
        if (strval($userLevelName) == "") {
            return;
        }
        $userLevelID = $this->getUserLevelID($userLevelName);
        $this->deleteUserLevelID($userLevelID);
    }

    // Delete User Level by ID
    public function deleteUserLevelID($userLevelID)
    {
        if (!is_numeric($userLevelID)) {
            return;
        }
        if ($userLevelID < -1) {
            return;
        }
        $cnt = count($this->UserLevelID);
        for ($i = 0; $i < $cnt; $i++) {
            if ($this->UserLevelID[$i] == $userLevelID) {
                unset($this->UserLevelID[$i]);
                $_SESSION[SESSION_USER_LEVEL_LIST] = $this->userLevelList(); // Update session variable
                break;
            }
        }
    }

    // User Level list
    public function userLevelList()
    {
        return implode(", ", $this->UserLevelID);
    }

    // User level ID exists
    public function userLevelIDExists($id)
    {
        if (is_array($this->UserLevel)) {
            foreach ($this->UserLevel as $row) {
                list($levelid, $name) = $row;
                if (SameString($levelid, $id)) {
                    return true;
                }
            }
        }
        return false;
    }

    // User Level name list
    public function userLevelNameList()
    {
        $list = "";
        foreach ($this->UserLevelID as $userLevelID) {
            if ($list != "") {
                $list .= ", ";
            }
            $list .= QuotedValue($this->getUserLevelName($userLevelID), DATATYPE_STRING, Config("USER_LEVEL_DBID"));
        }
        return $list;
    }

    // Get user privilege based on table name and User Level
    public function getUserLevelPrivEx($tableName, $userLevelID)
    {
        $ids = explode(Config("MULTIPLE_OPTION_SEPARATOR"), strval($userLevelID));
        $userPriv = 0;
        foreach ($ids as $id) {
            if (strval($id) == "-1") { // System Administrator
                return ALLOW_ALL;
            } elseif ((int)$id >= 0 || (int)$id == -2) {
                if (is_array($this->UserLevelPriv)) {
                    foreach ($this->UserLevelPriv as $row) {
                        list($table, $levelid, $priv) = $row;
                        if (SameText($table, $tableName) && SameText($levelid, $id)) {
                            if (is_numeric($priv)) {
                                $userPriv |= (int)$priv;
                            }
                        }
                    }
                }
            }
        }
        return $userPriv;
    }

    // Get current User Level name
    public function currentUserLevelName()
    {
        return $this->getUserLevelName($this->currentUserLevelID());
    }

    // Get User Level name based on User Level
    public function getUserLevelName($userLevelID, $lang = true)
    {
        global $Language;
        if (strval($userLevelID) == "-2") {
            return ($lang) ? $Language->phrase("UserAnonymous") : "Anonymous";
        } elseif (strval($userLevelID) == "-1") {
            return ($lang) ? $Language->phrase("UserAdministrator") : "Administrator";
        } elseif (strval($userLevelID) == "0") {
            return ($lang) ? $Language->phrase("UserDefault") : "Default";
        } elseif ($userLevelID > 0) {
            if (is_array($this->UserLevel)) {
                foreach ($this->UserLevel as $row) {
                    list($levelid, $name) = $row;
                    if (SameString($levelid, $userLevelID)) {
                        $userLevelName = "";
                        if ($lang) {
                            $userLevelName = $Language->phrase($name);
                        }
                        return ($userLevelName != "") ? $userLevelName : $name;
                    }
                }
            }
        }
        return "";
    }

    // Display all the User Level settings (for debug only)
    public function showUserLevelInfo()
    {
        Write("<pre>");
        Write(print_r($this->UserLevel, true));
        Write(print_r($this->UserLevelPriv, true));
        Write("</pre>");
        Write("<p>Current User Level ID = " . $this->currentUserLevelID() . "</p>");
        Write("<p>Current User Level ID List = " . $this->userLevelList() . "</p>");
    }

    // Check privilege for List page (for menu items)
    public function allowList($tableName)
    {
        return ($this->currentUserLevelPriv($tableName) & ALLOW_LIST);
    }

    // Check privilege for View page (for Allow-View / Detail-View)
    public function allowView($tableName)
    {
        return ($this->currentUserLevelPriv($tableName) & ALLOW_VIEW);
    }

    // Check privilege for Add page (for Allow-Add / Detail-Add)
    public function allowAdd($tableName)
    {
        return ($this->currentUserLevelPriv($tableName) & ALLOW_ADD);
    }

    // Check privilege for Edit page (for Detail-Edit)
    public function allowEdit($tableName)
    {
        return ($this->currentUserLevelPriv($tableName) & ALLOW_EDIT);
    }

    // Check privilege for Edit page (for Detail-Edit)
    public function allowDelete($tableName)
    {
        return ($this->currentUserLevelPriv($tableName) & ALLOW_DELETE);
    }

    // Check privilege for lookup
    public function allowLookup($tableName)
    {
        return ($this->currentUserLevelPriv($tableName) & ALLOW_LOOKUP);
    }

    // Check privilege for export
    public function allowExport($tableName)
    {
        return ($this->currentUserLevelPriv($tableName) & ALLOW_EXPORT);
    }

    // Check if user password expired
    public function isPasswordExpired()
    {
        return (Session(SESSION_STATUS) == "passwordexpired");
    }

    // Set session password expired
    public function setSessionPasswordExpired()
    {
        $_SESSION[SESSION_STATUS] = "passwordexpired";
    }

    // Set login status
    public function setLoginStatus($status = "")
    {
        $_SESSION[SESSION_STATUS] = $status;
    }

    // Check if user password reset
    public function isPasswordReset()
    {
        return (Session(SESSION_STATUS) == "passwordreset");
    }

    // Check if user is logging in (after changing password)
    public function isLoggingIn()
    {
        return (Session(SESSION_STATUS) == "loggingin");
    }

    // Check if user is logging in (2FA)
    public function isLoggingIn2FA()
    {
        return (Session(SESSION_STATUS) == "loggingin2fa");
    }

    // Check if user is logged in
    public function isLoggedIn()
    {
        return ($this->isLoggedIn || Session(SESSION_STATUS) == "login");
    }

    // Check if user is system administrator
    public function isSysAdmin()
    {
        return ($this->isSysAdmin || Session(SESSION_SYS_ADMIN) === 1);
    }

    // Check if user is administrator
    public function isAdmin()
    {
        $isAdmin = $this->isSysAdmin();
        if (!$isAdmin) {
            $isAdmin = $this->CurrentUserLevelID == -1 || $this->hasUserLevelID(-1) || $this->canAdmin();
        }
        return $isAdmin;
    }

    // Save User Level to Session
    public function saveUserLevel()
    {
        $_SESSION[SESSION_USER_LEVELS] = $this->UserLevel;
        $_SESSION[SESSION_USER_LEVEL_PRIVS] = $this->UserLevelPriv;
    }

    // Load User Level from Session
    public function loadUserLevel()
    {
        if (empty(Session(SESSION_USER_LEVELS)) || empty(Session(SESSION_USER_LEVEL_PRIVS))) {
            $this->setupUserLevel();
            $this->saveUserLevel();
        } else {
            $this->UserLevel = Session(SESSION_USER_LEVELS);
            $this->UserLevelPriv = Session(SESSION_USER_LEVEL_PRIVS);
        }
    }

    // Get user email
    public function currentUserEmail()
    {
        return $this->currentUserInfo(Config("USER_EMAIL_FIELD_NAME"));
    }

    // Get current user info
    public function currentUserInfo($fldname)
    {
        global $UserTable;
        $info = null;
        if (Config("USER_TABLE") && !$this->isSysAdmin()) {
            $filter = GetUserFilter(Config("LOGIN_USERNAME_FIELD_NAME"), $this->currentUserName());
            if ($filter != "") {
                $sql = $UserTable->getSql($filter);
                if ($row = ExecuteRow($sql, $UserTable->Dbid)) {
                    $info = GetUserInfo($fldname, $row);
                }
            }
        }
        return $info;
    }

    // UserID Loading event
    public function userIdLoading()
    {
        //Log("UserID Loading: " . $this->currentUserID());
    }

    // UserID Loaded event
    public function userIdLoaded()
    {
        //Log("UserID Loaded: " . $this->userIDList());
    }

    // User Level Loaded event
    public function userLevelLoaded() {
    	// there is any other levels than Anonymus (-2)
    	$has_access = false;

        // explicit list for image access 
        // Manager = 1
        // Reader = 2
        // Administrator = -1
        $permitted = array(-1,1,2);
    	foreach ($this->UserLevelID as $userLevelID) {
    		if (is_int($userLevelID) and in_array($userLevelID, $permitted)) {
    			$has_access = true;
    		}
    	}
    	if ($has_access) {
    		$headerstr = "X-Replace-DC-Session: allowed=yes";
    		header("X-Replace-DC-Session: allowed=yes");
    	}
    }

    // Table Permission Loading event
    public function tablePermissionLoading()
    {
        //Log("Table Permission Loading: " . $this->CurrentUserLevelID);
    }

    // Table Permission Loaded event
    public function tablePermissionLoaded()
    {
        if (CurrentUserLevel() == 1 && CurrentTableName() == "records") {
            $this->setCanImport(true);
        }
    }

    // User Custom Validate event
    public function userCustomValidate(&$usr, &$pwd)
    {
        // Enter your custom code to validate user, return true if valid.
        return false;
    }

    // User Validated event
    public function userValidated(&$rs)
    {
        // Example:
        //$_SESSION['UserEmail'] = $rs['Email'];
    }

    // User PasswordExpired event
    public function userPasswordExpired(&$rs)
    {
        //Log("User_PasswordExpired");
    }
}
