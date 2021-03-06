<?php

return [
    'error' => [
        '400' => 'Bad Request',
        '401' => 'Unauthorized',
        '403' => 'Forbidden',
        '404' => 'Not Found',
        '405' => 'Method Not Allowed',
        '406' => 'Not Acceptable',
        '408' => 'Request Timeout',
        '412' => 'Precondition Failed',
        '413' => 'Payload Too Large',
        '415' => 'Unsupported Media Type',
        '419' => 'Page Expired',
        '429' => 'Too Many Requests',
        '500' => 'Internal Server Error',
        '501' => 'Not Implemented',
        '503' => 'Service Unavailable',
        '505' => 'Version Check Failed',
        'back' => 'Back',
    ],
    'auth' => [
        'subtitle' => 'Please enter your account password to log in',
        'tips' => 'Test account: admin, password: demon',
        'account' => 'Account',
        'enter_account' => 'Enter Account',
        'error_account' => 'Incorrect Account',
        'password' => 'Password',
        'enter_password' => 'Enter Password',
        'error_password' => 'Incorrect Password',
        'captcha' => 'Captcha',
        'enter_captcha' => 'Enter Captcha',
        'error_captcha' => 'Incorrect Captcha',
        'remember' => 'Remember Account',
        'login' => 'Log in',
        'login_success' => 'Login successful',
        'logout' => 'Log out',
        'logout_confirm' => 'Confirm logout of current account',
        'logout_success' => 'Logout successfully',
        'notifications' => 'Notifications',
        'notifications_none' => 'Without notification',
        'cache' => 'Clear Cache',
        'clear_confirm' => 'Confirm to clean up the cache',
        'clear_success' => 'Cache cleaned successfully',
        'setting' => 'Setting',
        'save' => 'Save',
        'setting_success' => 'Settings saved successfully',
        'locale' => 'Locale',
        'locale_success' => 'Locale switch successfully',
        'submit' => 'Submit',
        'reset' => 'Reset',
    ],
    'access' => [
        'admin' => 'Administrator',
        'view' => 'View',
        'add' => 'Add',
        'new' => 'New',
        'create' => 'Create',
        'insert' => 'Insert',
        'edit' => 'Edit',
        'modify' => 'Modify',
        'change' => 'Change',
        'update' => 'Update',
        'del' => 'Del',
        'leave' => 'Leave',
        'remove' => 'Remove',
        'delete' => 'Delete',
        'info' => 'Info',
        'get' => 'Get',
        'content' => 'Content',
        'detail' => 'Detail',
        'audit' => 'Audit',
        'check' => 'Check',
        'approve' => 'Approve',
        'confirm' => 'Confirm',
        'weight' => 'Weight',
        'reorder' => 'Reorder',
        'action' => 'Action',
        'option_all' => 'All',
        'username' => 'Username',
        'enter_username' => 'Please enter username',
        'error_username_unique' => 'Username must be unique',
        'nickname' => 'Nickname',
        'enter_nickname' => 'Please enter nickname',
        'userRemark' => 'User Remark',
        'password' => 'Password',
        'enter_password' => 'Please enter password',
        'enter_password_edit' => 'Please enter password, otherwise it means no change',
        'status' => 'Status',
        'status_0' => 'Off',
        'status_1' => 'On',
        'avatar' => 'Avatar',
        'user' => 'User',
        'add_user' => 'Add User',
        'add_user_success' => 'User added successfully',
        'user_info' => 'User Info',
        'edit_user' => 'Edit User',
        'edit_user_success' => 'Admin edited successfully',
        'del_user' => 'Delete User',
        'del_user_success' => 'Admin deleted successfully',
        'activeTime' => 'Active Time',
        'loginTime' => 'Login Time',
        'createTime' => 'Create Time',
        'updateTime' => 'Update Time',
        'batch' => 'Batch Adjust',
        'batch_none' => 'No content selected',
        'batch_on' => 'Batch On',
        'batch_off' => 'Batch Off',
        'batch_del' => 'Batch Delete',
        'batch_success' => 'Batch processing is successful',
        'batch_confirm' => 'Whether to :action the selected :length items',
        'menu' => 'Menu',
        'type' => 'Type',
        'title' => 'Title',
        'enter_title' => 'Please enter title, "__" will auto match the lang',
        'name' => 'Name',
        'enter_name' => 'Please enter name, "__" will auto match the lang',
        'path' => 'Path',
        'enter_path' => 'Please enter path',
        'error_path_unique' => 'Path must be unique',
        'icon' => 'Icon',
        'enter_icon' => 'Please enter icon',
        'icon_button' => 'Select Icon',
        'remark' => 'Remark',
        'enter_remark' => 'Please enter remark',
        'parent' => 'Parent',
        'null_parent' => 'No parent menu',
        'enter_parent' => 'Please select the parent menu',
        'error_parent' => 'Incorrect parent menu selection',
        'error_parent_trace' => 'Cannot select self or child as parent',
        'type_menu' => 'Menu',
        'type_page' => 'Page',
        'type_action' => 'Action',
        'add_menu' => 'Add Menu',
        'add_menu_success' => 'Menu added successfully',
        'edit_menu' => 'Edit Menu',
        'edit_menu_success' => 'Menu edited successfully',
        'del_menu' => 'Delete Menu',
        'del_menu_success' => 'Menu deleted successfully',
        'drag_weight' => 'Drag to sort',
        'menu_fold' => 'Fold',
        'role' => 'Role',
        'add_role' => 'Add Role',
        'add_role_success' => 'Role added successfully',
        'edit_role' => 'Edit Role',
        'edit_role_success' => 'Role editing successfully',
        'del_role' => 'Delete Role',
        'del_role_success' => 'Role deleted successfully',
        'role_menu_selected' => 'All Selected',
        'role_menu_canceled' => 'All Canceled',
        'role_menu_unfold' => 'Menu Unfold',
        'role_menu_fold' => 'Menu Fold',
        'log' => 'Log',
        'del_log' => 'Delete Log',
        'del_log_success' => 'Log deleted successfully',
        'clear_log' => 'Clear Log',
        'clear_log_success' => 'Log cleaned successfully ',
        'log_info' => 'Log Info',
        'clear' => 'Clear',
        'tag' => 'Tag',
        'method' => 'Method',
        'ip' => 'IP',
        'soleCode' => 'Sole Code',
        'userAgent' => 'User Agent',
        'arguments' => 'Arguments',
        'export' => 'Export',
        'export_max_length' => 'Export failed, the export quantity is greater than: length items, please reduce the export scope',
    ],
    'menu' => [
        'dashboard' => 'Dashboard',
        'example' => 'Example',
        'example_index' => 'Dashboard',
        'example_form' => 'Form',
        'example_layer' => 'Layer',
        'example_table' => 'Table',
        'example_widget' => 'Widget',
        'example_editor' => 'Rich Editor',
        'example_markdown' => 'MarkDown Editor',
        'example_login' => 'Login',
        'example_setting' => 'Setting',
        'admin' => 'Admin',
        'admin_access' => 'Access',
        'admin_access_user' => 'User',
        'admin_access_menu' => 'Menu',
        'admin_access_role' => 'Role',
        'admin_access_log' => 'Log',
    ]
];
