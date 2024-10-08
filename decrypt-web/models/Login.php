<?php

namespace PHPMaker2023\decryptweb23;

use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\FetchMode;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

/**
 * Page class
 */
class Login extends Users
{
    use MessagesTrait;

    // Page ID
    public $PageID = "login";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Page object name
    public $PageObjName = "Login";

    // View file path
    public $View = null;

    // Title
    public $Title = null; // Title for <title> tag

    // Rendering View
    public $RenderingView = false;

    // CSS class/style
    public $CurrentPageName = "login";

    // Page headings
    public $Heading = "";
    public $Subheading = "";
    public $PageHeader;
    public $PageFooter;

    // Page layout
    public $UseLayout = true;

    // Page terminated
    private $terminated = false;

    // Page heading
    public function pageHeading()
    {
        global $Language;
        if ($this->Heading != "") {
            return $this->Heading;
        }
        if (method_exists($this, "tableCaption")) {
            return $this->tableCaption();
        }
        return "";
    }

    // Page subheading
    public function pageSubheading()
    {
        global $Language;
        if ($this->Subheading != "") {
            return $this->Subheading;
        }
        return "";
    }

    // Page name
    public function pageName()
    {
        return CurrentPageName();
    }

    // Page URL
    public function pageUrl($withArgs = true)
    {
        $route = GetRoute();
        $args = RemoveXss($route->getArguments());
        if (!$withArgs) {
            foreach ($args as $key => &$val) {
                $val = "";
            }
            unset($val);
        }
        return rtrim(UrlFor($route->getName(), $args), "/") . "?";
    }

    // Show Page Header
    public function showPageHeader()
    {
        $header = $this->PageHeader;
        $this->pageDataRendering($header);
        if ($header != "") { // Header exists, display
            echo '<p id="ew-page-header">' . $header . '</p>';
        }
    }

    // Show Page Footer
    public function showPageFooter()
    {
        $footer = $this->PageFooter;
        $this->pageDataRendered($footer);
        if ($footer != "") { // Footer exists, display
            echo '<p id="ew-page-footer">' . $footer . '</p>';
        }
    }

    // Constructor
    public function __construct()
    {
        parent::__construct();
        global $Language, $DashboardReport, $DebugTimer, $UserTable;
        $this->TableVar = 'users';
        $this->TableName = 'users';

        // Table CSS class
        $this->TableClass = "table table-striped table-bordered table-hover table-sm ew-view-table";

        // Initialize
        $GLOBALS["Page"] = &$this;

        // Language object
        $Language = Container("language");

        // Table object (users)
        if (!isset($GLOBALS["users"]) || get_class($GLOBALS["users"]) == PROJECT_NAMESPACE . "users") {
            $GLOBALS["users"] = &$this;
        }

        // Start timer
        $DebugTimer = Container("timer");

        // Debug message
        LoadDebugMessage();

        // Open connection
        $GLOBALS["Conn"] ??= $this->getConnection();

        // User table object
        $UserTable = Container("usertable");
    }

    // Get content from stream
    public function getContents(): string
    {
        global $Response;
        return is_object($Response) ? $Response->getBody() : ob_get_clean();
    }

    // Is lookup
    public function isLookup()
    {
        return SameText(Route(0), Config("API_LOOKUP_ACTION"));
    }

    // Is AutoFill
    public function isAutoFill()
    {
        return $this->isLookup() && SameText(Post("ajax"), "autofill");
    }

    // Is AutoSuggest
    public function isAutoSuggest()
    {
        return $this->isLookup() && SameText(Post("ajax"), "autosuggest");
    }

    // Is modal lookup
    public function isModalLookup()
    {
        return $this->isLookup() && SameText(Post("ajax"), "modal");
    }

    // Is terminated
    public function isTerminated()
    {
        return $this->terminated;
    }

    /**
     * Terminate page
     *
     * @param string $url URL for direction
     * @return void
     */
    public function terminate($url = "")
    {
        if ($this->terminated) {
            return;
        }
        global $TempImages, $DashboardReport, $Response;

        // Page is terminated
        $this->terminated = true;

        // Page Unload event
        if (method_exists($this, "pageUnload")) {
            $this->pageUnload();
        }

        // Global Page Unloaded event (in userfn*.php)
        Page_Unloaded();
        if (!IsApi() && method_exists($this, "pageRedirecting")) {
            $this->pageRedirecting($url);
        }

        // Close connection
        CloseConnections();

        // Return for API
        if (IsApi()) {
            $res = $url === true;
            if (!$res) { // Show response for API
                $ar = array_merge($this->getMessages(), $url ? ["url" => GetUrl($url)] : []);
                WriteJson($ar);
            }
            $this->clearMessages(); // Clear messages for API request
            return;
        } else { // Check if response is JSON
            if (StartsString("application/json", $Response->getHeaderLine("Content-type")) && $Response->getBody()->getSize()) { // With JSON response
                $this->clearMessages();
                return;
            }
        }

        // Go to URL if specified
        if ($url != "") {
            if (!Config("DEBUG") && ob_get_length()) {
                ob_end_clean();
            }

            // Handle modal response
            if ($this->IsModal) { // Show as modal
                WriteJson(["url" => $url]);
            } else {
                SaveDebugMessage();
                Redirect(GetUrl($url));
            }
        }
        return; // Return to controller
    }

    // Properties
    public $Username;
    public $Password;
    public $LoginType;
    public $IsModal = false;

    /**
     * Page run
     *
     * @return void
     */
    public function run()
    {
        global $ExportType, $UserProfile, $Language, $Security, $CurrentForm, $Breadcrumb, $SkipHeaderFooter;
        $this->OffsetColumnClass = ""; // Override user table

        // Create Username/Password field object (used by validation only)
        $this->Username = new DbField("users", "username", "username", "username", "", 202, 255, -1, false, "", false, false, false);
        $this->Username->EditAttrs->appendClass("form-control ew-form-control");
        $this->Password = new DbField("users", "password", "password", "password", "", 202, 255, -1, false, "", false, false, false);
        $this->Password->EditAttrs->appendClass("form-control ew-form-control");
        if (Config("ENCRYPTED_PASSWORD")) {
            $this->Password->Raw = true;
        }
        $this->LoginType = new DbField("users", "type", "logintype", "logintype", "", 202, 255, -1, false, "", false, false, false);

        // Is modal
        $this->IsModal = ConvertToBool(Param("modal"));
        $this->UseLayout = $this->UseLayout && !$this->IsModal;

        // Use layout
        $this->UseLayout = $this->UseLayout && ConvertToBool(Param(Config("PAGE_LAYOUT"), true));

        // View
        $this->View = Get(Config("VIEW"));
        $this->CurrentAction = Param("action"); // Set up current action

        // Global Page Loading event (in userfn*.php)
        Page_Loading();

        // Page Load event
        if (method_exists($this, "pageLoad")) {
            $this->pageLoad();
        }

        // Check modal
        if ($this->IsModal) {
            $SkipHeaderFooter = true;
        }
        $Breadcrumb = new Breadcrumb("RecordsList");
        $Breadcrumb->add("login", "LoginPage", CurrentUrl(), "", "", true);
        $this->Heading = $Language->phrase("LoginPage");
        $this->Username->setFormValue(""); // Initialize
        $this->Password->setFormValue("");
        $this->LoginType->setFormValue("");
        $lastUrl = $Security->lastUrl(); // Get last URL
        if ($lastUrl == "") {
            $lastUrl = "index";
        }

        // Show messages
        $flash = Container("flash");
        if ($heading = $flash->getFirstMessage("heading")) {
            $this->setMessageHeading($heading);
        }
        if ($failure = $flash->getFirstMessage("failure")) {
            $this->setFailureMessage($failure);
        }
        if ($success = $flash->getFirstMessage("success")) {
            $this->setSuccessMessage($success);
        }
        if ($warning = $flash->getFirstMessage("warning")) {
            $this->setWarningMessage(warning);
        }

        // Login
        $provider = trim(Get("provider") ?? Route("provider") ?? ""); // Get provider
        if (IsLoggingIn()) { // After changing password or authorized by 2FA
            $this->Username->setFormValue(Session(SESSION_USER_PROFILE_USER_NAME));
            $this->Password->setFormValue(Session(SESSION_USER_PROFILE_PASSWORD));
            $this->LoginType->setFormValue(Session(SESSION_USER_PROFILE_LOGIN_TYPE));
            $validPwd = $Security->validateUser($this->Username->CurrentValue, $this->Password->CurrentValue, false);
            if ($validPwd) {
                $_SESSION[SESSION_USER_PROFILE_USER_NAME] = "";
                $_SESSION[SESSION_USER_PROFILE_PASSWORD] = "";
                $_SESSION[SESSION_USER_PROFILE_LOGIN_TYPE] = "";
                $this->terminate($lastUrl); // Redirect to last page
                return;
            }
        } elseif (Config("USE_TWO_FACTOR_AUTHENTICATION") && IsLoggingIn2FA()) { // Logging in via 2FA, redirect
            $this->terminate("login2fa");
            return;
        } elseif (!EmptyValue($provider)) { // OAuth provider
            $provider = ucfirst(strtolower($provider)); // e.g. Google, Facebook
            $validate = $Security->validateUser($this->Username->CurrentValue, $this->Password->CurrentValue, false, $provider); // Authenticate by provider
            $validPwd = $validate;
            if ($validate) {
                $this->Username->setFormValue($UserProfile->get("email") ?? $UserProfile->get("emailaddress"));
                if (Config("DEBUG") && !$Security->isLoggedIn()) {
                    $validPwd = false;
                    $this->setFailureMessage(str_replace("%u", $this->Username->CurrentValue ?? "", $Language->phrase("UserNotFound"))); // Show debug message
                }
            } else {
                $this->setFailureMessage(str_replace("%p", $provider, $Language->phrase("LoginFailed")));
            }
        } else { // Normal login
            if (!$Security->isLoggedIn()) {
                $Security->autoLogin();
            }
            $Security->loadUserLevel(); // Load user level
            if ($Security->isLoggedIn()) {
                $this->terminate($lastUrl); // Redirect to last page
                return;
                if ($this->getFailureMessage() != "") { // Throw error
                    $error = new \ErrorException($this->getFailureMessage(), 0, E_USER_WARNING);
                    $this->clearFailureMessage();
                    throw $error;
                }
            }
            $validate = false;
            if (Post($this->Username->FieldVar) !== null) {
                $this->Username->setFormValue(Post($this->Username->FieldVar));
                $this->Password->setFormValue(Post($this->Password->FieldVar));
                $this->LoginType->setFormValue(strtolower(Post($this->LoginType->FieldVar, "")));
                $validate = $this->validateForm();
            } elseif (Config("ALLOW_LOGIN_BY_URL") && Get($this->Username->FieldVar) !== null) {
                $this->Username->setQueryStringValue(Get($this->Username->FieldVar));
                $this->Password->setQueryStringValue(Get($this->Password->FieldVar));
                $this->LoginType->setQueryStringValue(strtolower(Get($this->LoginType->FieldVar, "")));
                $validate = $this->validateForm();
            } else { // Restore settings
                if (ReadCookie("Checksum") == strval(crc32(md5(Config("RANDOM_KEY"))))) {
                    $this->Username->setFormValue(Decrypt(ReadCookie("Username")));
                }
                if (ReadCookie("AutoLogin") == "autologin") {
                    $this->LoginType->setFormValue("a");
                } else { // Restore settings
                    $this->LoginType->setFormValue("");
                }
            }
            if (!EmptyValue($this->Username->CurrentValue)) {
                $_SESSION[SESSION_USER_LOGIN_TYPE] = $this->LoginType->CurrentValue; // Save user login type
                $_SESSION[SESSION_USER_PROFILE_USER_NAME] = $this->Username->CurrentValue; // Save login user name
                $_SESSION[SESSION_USER_PROFILE_LOGIN_TYPE] = $this->LoginType->CurrentValue; // Save login type
            }
            $validPwd = false;
            if ($validate) {
                // Call Logging In event
                $validate = $this->userLoggingIn($this->Username->CurrentValue, $this->Password->CurrentValue);
                if ($validate) {
                    $validPwd = $Security->validateUser($this->Username->CurrentValue, $this->Password->CurrentValue, false); // Manual login
                    if (!$validPwd) {
                        $this->Username->setFormValue(""); // Clear login name
                        $this->Username->addErrorMessage($Language->phrase("InvalidUidPwd")); // Invalid user name or password
                        $this->Password->addErrorMessage($Language->phrase("InvalidUidPwd")); // Invalid user name or password

                    // Two factor authentication enabled (go to 2fa page)
                    } elseif (!IsSysAdmin() && Config("USE_TWO_FACTOR_AUTHENTICATION") && (Config("FORCE_TWO_FACTOR_AUTHENTICATION") || $UserProfile->hasUserSecret($this->Username->CurrentValue, true))) {
                        $_SESSION[SESSION_STATUS] = "loggingin2fa";
                        $_SESSION[SESSION_USER_PROFILE_USER_NAME] = $this->Username->CurrentValue;
                        $_SESSION[SESSION_USER_PROFILE_PASSWORD] = $this->Password->CurrentValue;
                        $_SESSION[SESSION_USER_PROFILE_LOGIN_TYPE] = $this->LoginType->CurrentValue;
                        $this->IsModal = false; // Redirect
                        if (in_array(strtolower(Config("TWO_FACTOR_AUTHENTICATION_TYPE")), ["email", "sms"]) && $UserProfile->hasUserSecret($this->Username->CurrentValue, true)) { // Send one time password if verified
                            TwoFactorAuthenticationClass()::sendOneTimePassword($this->Username->CurrentValue);
                        }
                        $this->terminate("login2fa?" . Config("PAGE_LAYOUT") . "=false");
                    }
                } else {
                    if ($this->getFailureMessage() == "") {
                        $this->setFailureMessage($Language->phrase("LoginCancelled")); // Login cancelled
                    }
                }
            }
        }

        // After login
        if ($validPwd) {
            // Write cookies
            if ($this->LoginType->CurrentValue == "a") { // Auto login
                WriteCookie("AutoLogin", "autologin"); // Set autologin cookie
                WriteCookie("Username", Encrypt($this->Username->CurrentValue)); // Set user name cookie
                WriteCookie("Password", Encrypt($this->Password->CurrentValue)); // Set password cookie
                WriteCookie('Checksum', crc32(md5(Config("RANDOM_KEY"))));
            } else {
                WriteCookie("AutoLogin", ""); // Clear auto login cookie
            }
            $this->writeAuditTrailOnLogin();

            // Call loggedin event
            $this->userLoggedIn($this->Username->CurrentValue);

            // OAuth provider, just redirect
            if (!EmptyValue($provider)) {
                $this->IsModal = false;
            // Two factor authentication enabled (login directly), return JSON
            } elseif (Config("USE_TWO_FACTOR_AUTHENTICATION")) {
                $this->IsModal = true;
            }
            $this->terminate($lastUrl); // Return to last accessed URL
            return;
        } elseif (!EmptyValue($this->Username->CurrentValue) && !EmptyValue($this->Password->CurrentValue)) {
            // Call user login error event
            $this->userLoginError($this->Username->CurrentValue, $this->Password->CurrentValue);
        }

        // Set up error message
        if (EmptyValue($this->Username->ErrorMessage)) {
            $this->Username->ErrorMessage = $Language->phrase("EnterUserName");
        }
        if (EmptyValue($this->Password->ErrorMessage)) {
            $this->Password->ErrorMessage = $Language->phrase("EnterPassword");
        }

        // Set LoginStatus / Page_Rendering / Page_Render
        if (!IsApi() && !$this->isTerminated()) {
            // Setup login status
            SetupLoginStatus();

            // Pass login status to client side
            SetClientVar("login", LoginStatus());

            // Global Page Rendering event (in userfn*.php)
            Page_Rendering();

            // Page Render event
            if (method_exists($this, "pageRender")) {
                $this->pageRender();
            }

            // Render search option
            if (method_exists($this, "renderSearchOptions")) {
                $this->renderSearchOptions();
            }
        }
    }

    // Validate form
    protected function validateForm()
    {
        global $Language;

        // Check if validation required
        if (!Config("SERVER_VALIDATE")) {
            return true;
        }
        $validateForm = true;
        if (EmptyValue($this->Username->CurrentValue)) {
            $this->Username->addErrorMessage($Language->phrase("EnterUserName"));
            $validateForm = false;
        }
        if (EmptyValue($this->Password->CurrentValue)) {
            $this->Password->addErrorMessage($Language->phrase("EnterPassword"));
            $validateForm = false;
        }

        // Call Form Custom Validate event
        $formCustomError = "";
        $validateForm = $validateForm && $this->formCustomValidate($formCustomError);
        if ($formCustomError != "") {
            $this->setFailureMessage($formCustomError);
        }
        return $validateForm;
    }

    // Write audit trail on login
    protected function writeAuditTrailOnLogin()
    {
        global $Language;
        $usr = CurrentUser();
        WriteAuditLog($usr, $Language->phrase("AuditTrailLogin"), CurrentUserIP(), "", "", "", "");
    }

    // Page Load event
    public function pageLoad()
    {
        //Log("Page Load");
    }

    // Page Unload event
    public function pageUnload()
    {
        //Log("Page Unload");
    }

    // Page Redirecting event
    public function pageRedirecting(&$url)
    {
        // Example:
        //$url = "your URL";
    }

    // Message Showing event
    // $type = ''|'success'|'failure'
    public function messageShowing(&$msg, $type)
    {
        // Example:
        //if ($type == 'success') $msg = "your success message";
    }

    // Page Render event
    public function pageRender()
    {
        //Log("Page Render");
    }

    // Page Data Rendering event
    public function pageDataRendering(&$header)
    {
        // Example:
        //$header = "your header";
    }

    // Page Data Rendered event
    public function pageDataRendered(&$footer)
    {
        // Example:
        //$footer = "your footer";
    }

    // User Logging In event
    public function userLoggingIn($usr, &$pwd)
    {
        // Enter your code here
        // To cancel, set return value to false
        return true;
    }

    // User Logged In event
    public function userLoggedIn($usr)
    {
        //Log("User Logged In");
    }

    // User Login Error event
    public function userLoginError($usr, $pwd)
    {
        //Log("User Login Error");
    }

    // Form Custom Validate event
    public function formCustomValidate(&$customError)
    {
        // Return error message in $customError
        return true;
    }
}
