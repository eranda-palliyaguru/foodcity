<?php

\error_reporting(\E_ALL);
\ini_set('display_errors', 'stdout');

// enable assertions
\ini_set('assert.active', 1);
@\ini_set('zend.assertions', 1);
\ini_set('assert.exception', 1);

\header('Content-type: text/html; charset=utf-8');

require __DIR__.'/../vendor/autoload.php';

 include("connect.php");
// or
// $db = new \PDO('pgsql:dbname=php_auth;host=127.0.0.1;port=5432', 'postgres', 'monkey');
// or
// $db = new \PDO('sqlite:../Databases/php_auth.sqlite');

$auth = new \Delight\Auth\Auth($db);



//\showGeneralForm();
//\showDebugData($auth, $result);
$id = $auth->getUserId();

if ($auth->check()) {

}
else {
header("location:../index.php");
}


function showDebugData(\Delight\Auth\Auth $auth, $result) {
	echo '<pre>';

	echo 'Last operation' . "\t\t\t\t";
	\var_dump($result);
	echo 'Session ID' . "\t\t\t\t";
	\var_dump(\session_id());
	echo "\n";

	echo '$auth->isLoggedIn()' . "\t\t\t";
	\var_dump($auth->isLoggedIn());
	echo '$auth->check()' . "\t\t\t\t";
	\var_dump($auth->check());
	echo "\n";

	echo '$auth->getUserId()' . "\t\t\t";
	\var_dump($auth->getUserId());
	echo '$auth->id()' . "\t\t\t\t";
	\var_dump($auth->id());
	echo "\n";

	echo '$auth->getEmail()' . "\t\t\t";
	\var_dump($auth->getEmail());
	echo '$auth->getUsername()' . "\t\t\t";
	\var_dump($auth->getUsername());

	echo '$auth->getStatus()' . "\t\t\t";
	echo \convertStatusToText($auth);
	echo ' / ';
	\var_dump($auth->getStatus());

	echo "\n";

	echo 'Roles (super moderator)' . "\t\t\t";
	\var_dump($auth->hasRole(\Delight\Auth\Role::SUPER_MODERATOR));

	echo 'Roles (developer *or* manager)' . "\t\t";
	\var_dump($auth->hasAnyRole(\Delight\Auth\Role::DEVELOPER, \Delight\Auth\Role::MANAGER));

	echo 'Roles (developer *and* manager)' . "\t\t";
	\var_dump($auth->hasAllRoles(\Delight\Auth\Role::DEVELOPER, \Delight\Auth\Role::MANAGER));

	echo 'Roles' . "\t\t\t\t\t";
	echo \json_encode($auth->getRoles()) . "\n";

	echo "\n";

	echo '$auth->isRemembered()' . "\t\t\t";
	\var_dump($auth->isRemembered());
	echo '$auth->getIpAddress()' . "\t\t\t";
	\var_dump($auth->getIpAddress());
	echo "\n";

	echo 'Session name' . "\t\t\t\t";
	\var_dump(\session_name());
	echo 'Auth::createRememberCookieName()' . "\t";
	\var_dump(\Delight\Auth\Auth::createRememberCookieName());
	echo "\n";

	echo 'Auth::createCookieName(\'session\')' . "\t";
	\var_dump(\Delight\Auth\Auth::createCookieName('session'));
	echo 'Auth::createRandomString()' . "\t\t";
	\var_dump(\Delight\Auth\Auth::createRandomString());
	echo 'Auth::createUuid()' . "\t\t\t";
	\var_dump(\Delight\Auth\Auth::createUuid());

	echo '</pre>';
}

function convertStatusToText(\Delight\Auth\Auth $auth) {
	if ($auth->isLoggedIn() === true) {
		if ($auth->getStatus() === \Delight\Auth\Status::NORMAL && $auth->isNormal()) {
			return 'normal';
		}
		elseif ($auth->getStatus() === \Delight\Auth\Status::ARCHIVED && $auth->isArchived()) {
			return 'archived';
		}
		elseif ($auth->getStatus() === \Delight\Auth\Status::BANNED && $auth->isBanned()) {
			return 'banned';
		}
		elseif ($auth->getStatus() === \Delight\Auth\Status::LOCKED && $auth->isLocked()) {
			return 'locked';
		}
		elseif ($auth->getStatus() === \Delight\Auth\Status::PENDING_REVIEW && $auth->isPendingReview()) {
			return 'pending review';
		}
		elseif ($auth->getStatus() === \Delight\Auth\Status::SUSPENDED && $auth->isSuspended()) {
			return 'suspended';
		}
	}
	elseif ($auth->isLoggedIn() === false) {
		if ($auth->getStatus() === null) {
			return 'none';
		}
	}

	throw new Exception('Invalid status `' . $auth->getStatus() . '`');
}

function showGeneralForm() {
	echo '<form action="" method="get" accept-charset="utf-8">';
	echo '<button type="submit">Refresh</button>';
	echo '</form>';
}

function showAuthenticatedUserForm(\Delight\Auth\Auth $auth) {
	echo '<form action="" method="post" accept-charset="utf-8">';
	echo '<input type="hidden" name="action" value="reconfirmPassword" />';
	echo '<input type="text" name="password" placeholder="Password" /> ';
	echo '<button type="submit">Reconfirm password</button>';
	echo '</form>';

	echo '<form action="" method="post" accept-charset="utf-8">';
	echo '<input type="hidden" name="action" value="changePassword" />';
	echo '<input type="text" name="oldPassword" placeholder="Old password" /> ';
	echo '<input type="text" name="newPassword" placeholder="New password" /> ';
	echo '<button type="submit">Change password</button>';
	echo '</form>';

	echo '<form action="" method="post" accept-charset="utf-8">';
	echo '<input type="hidden" name="action" value="changePasswordWithoutOldPassword" />';
	echo '<input type="text" name="newPassword" placeholder="New password" /> ';
	echo '<button type="submit">Change password without old password</button>';
	echo '</form>';

	echo '<form action="" method="post" accept-charset="utf-8">';
	echo '<input type="hidden" name="action" value="changeEmail" />';
	echo '<input type="text" name="newEmail" placeholder="New email address" /> ';
	echo '<button type="submit">Change email address</button>';
	echo '</form>';

	\showConfirmEmailForm();

	echo '<form action="" method="post" accept-charset="utf-8">';
	echo '<input type="hidden" name="action" value="setPasswordResetEnabled" />';
	echo '<select name="enabled" size="1">';
	echo '<option value="0"' . ($auth->isPasswordResetEnabled() ? '' : ' selected="selected"') . '>Disabled</option>';
	echo '<option value="1"' . ($auth->isPasswordResetEnabled() ? ' selected="selected"' : '') . '>Enabled</option>';
	echo '</select> ';
	echo '<button type="submit">Control password resets</button>';
	echo '</form>';

	echo '<form action="" method="post" accept-charset="utf-8">';
	echo '<input type="hidden" name="action" value="logOut" />';
	echo '<button type="submit">Log out</button>';
	echo '</form>';

	echo '<form action="" method="post" accept-charset="utf-8">';
	echo '<input type="hidden" name="action" value="logOutEverywhereElse" />';
	echo '<button type="submit">Log out everywhere else</button>';
	echo '</form>';

	echo '<form action="" method="post" accept-charset="utf-8">';
	echo '<input type="hidden" name="action" value="logOutEverywhere" />';
	echo '<button type="submit">Log out everywhere</button>';
	echo '</form>';

	\showDestroySessionForm();
}

function showGuestUserForm() {
	echo '<h1>Public</h1>';

	echo '<form action="" method="post" accept-charset="utf-8">';
	echo '<input type="hidden" name="action" value="login" />';
	echo '<input type="text" name="email" placeholder="Email address" /> ';
	echo '<input type="text" name="password" placeholder="Password" /> ';
	echo '<select name="remember" size="1">';
	echo '<option value="0">Remember (keep logged in)? — No</option>';
	echo '<option value="1">Remember (keep logged in)? — Yes</option>';
	echo '</select> ';
	echo '<button type="submit">Log in with email address</button>';
	echo '</form>';

	echo '<form action="" method="post" accept-charset="utf-8">';
	echo '<input type="hidden" name="action" value="login" />';
	echo '<input type="text" name="username" placeholder="Username" /> ';
	echo '<input type="text" name="password" placeholder="Password" /> ';
	echo '<select name="remember" size="1">';
	echo '<option value="0">Remember (keep logged in)? — No</option>';
	echo '<option value="1">Remember (keep logged in)? — Yes</option>';
	echo '</select> ';
	echo '<button type="submit">Log in with username</button>';
	echo '</form>';

	echo '<form action="" method="post" accept-charset="utf-8">';
	echo '<input type="hidden" name="action" value="register" />';
	echo '<input type="text" name="email" placeholder="Email address" /> ';
	echo '<input type="text" name="password" placeholder="Password" /> ';
	echo '<input type="text" name="username" placeholder="Username (optional)" /> ';
	echo '<select name="require_verification" size="1">';
	echo '<option value="0">Require email confirmation? — No</option>';
	echo '<option value="1">Require email confirmation? — Yes</option>';
	echo '</select> ';
	echo '<select name="require_unique_username" size="1">';
	echo '<option value="0">Username — Any</option>';
	echo '<option value="1">Username — Unique</option>';
	echo '</select> ';
	echo '<button type="submit">Register</button>';
	echo '</form>';

	\showConfirmEmailForm();

	echo '<form action="" method="post" accept-charset="utf-8">';
	echo '<input type="hidden" name="action" value="forgotPassword" />';
	echo '<input type="text" name="email" placeholder="Email address" /> ';
	echo '<button type="submit">Forgot password</button>';
	echo '</form>';

	echo '<form action="" method="post" accept-charset="utf-8">';
	echo '<input type="hidden" name="action" value="resetPassword" />';
	echo '<input type="text" name="selector" placeholder="Selector" /> ';
	echo '<input type="text" name="token" placeholder="Token" /> ';
	echo '<input type="text" name="password" placeholder="New password" /> ';
	echo '<select name="login" size="1">';
	echo '<option value="0">Sign in automatically? — No</option>';
	echo '<option value="1">Sign in automatically? — Yes</option>';
	echo '<option value="2">Sign in automatically? — Yes (and remember)</option>';
	echo '</select> ';
	echo '<button type="submit">Reset password</button>';
	echo '</form>';

	echo '<form action="" method="post" accept-charset="utf-8">';
	echo '<input type="hidden" name="action" value="canResetPassword" />';
	echo '<input type="text" name="selector" placeholder="Selector" /> ';
	echo '<input type="text" name="token" placeholder="Token" /> ';
	echo '<button type="submit">Can reset password?</button>';
	echo '</form>';

	\showDestroySessionForm();

	echo '<h1>Administration</h1>';

	echo '<form action="" method="post" accept-charset="utf-8">';
	echo '<input type="hidden" name="action" value="admin.createUser" />';
	echo '<input type="text" name="email" placeholder="Email address" /> ';
	echo '<input type="text" name="password" placeholder="Password" /> ';
	echo '<input type="text" name="username" placeholder="Username (optional)" /> ';
	echo '<select name="require_unique_username" size="1">';
	echo '<option value="0">Username — Any</option>';
	echo '<option value="1">Username — Unique</option>';
	echo '</select> ';
	echo '<button type="submit">Create user</button>';
	echo '</form>';

	echo '<form action="" method="post" accept-charset="utf-8">';
	echo '<input type="hidden" name="action" value="admin.deleteUser" />';
	echo '<input type="text" name="id" placeholder="ID" /> ';
	echo '<button type="submit">Delete user by ID</button>';
	echo '</form>';

	echo '<form action="" method="post" accept-charset="utf-8">';
	echo '<input type="hidden" name="action" value="admin.deleteUser" />';
	echo '<input type="text" name="email" placeholder="Email address" /> ';
	echo '<button type="submit">Delete user by email</button>';
	echo '</form>';

	echo '<form action="" method="post" accept-charset="utf-8">';
	echo '<input type="hidden" name="action" value="admin.deleteUser" />';
	echo '<input type="text" name="username" placeholder="Username" /> ';
	echo '<button type="submit">Delete user by username</button>';
	echo '</form>';

	echo '<form action="" method="post" accept-charset="utf-8">';
	echo '<input type="hidden" name="action" value="admin.addRole" />';
	echo '<input type="text" name="id" placeholder="ID" /> ';
	echo '<select name="role">' . \createRolesOptions() . '</select>';
	echo '<button type="submit">Add role for user by ID</button>';
	echo '</form>';

	echo '<form action="" method="post" accept-charset="utf-8">';
	echo '<input type="hidden" name="action" value="admin.addRole" />';
	echo '<input type="text" name="email" placeholder="Email address" /> ';
	echo '<select name="role">' . \createRolesOptions() . '</select>';
	echo '<button type="submit">Add role for user by email</button>';
	echo '</form>';

	echo '<form action="" method="post" accept-charset="utf-8">';
	echo '<input type="hidden" name="action" value="admin.addRole" />';
	echo '<input type="text" name="username" placeholder="Username" /> ';
	echo '<select name="role">' . \createRolesOptions() . '</select>';
	echo '<button type="submit">Add role for user by username</button>';
	echo '</form>';

	echo '<form action="" method="post" accept-charset="utf-8">';
	echo '<input type="hidden" name="action" value="admin.removeRole" />';
	echo '<input type="text" name="id" placeholder="ID" /> ';
	echo '<select name="role">' . \createRolesOptions() . '</select>';
	echo '<button type="submit">Remove role for user by ID</button>';
	echo '</form>';

	echo '<form action="" method="post" accept-charset="utf-8">';
	echo '<input type="hidden" name="action" value="admin.removeRole" />';
	echo '<input type="text" name="email" placeholder="Email address" /> ';
	echo '<select name="role">' . \createRolesOptions() . '</select>';
	echo '<button type="submit">Remove role for user by email</button>';
	echo '</form>';

	echo '<form action="" method="post" accept-charset="utf-8">';
	echo '<input type="hidden" name="action" value="admin.removeRole" />';
	echo '<input type="text" name="username" placeholder="Username" /> ';
	echo '<select name="role">' . \createRolesOptions() . '</select>';
	echo '<button type="submit">Remove role for user by username</button>';
	echo '</form>';

	echo '<form action="" method="post" accept-charset="utf-8">';
	echo '<input type="hidden" name="action" value="admin.hasRole" />';
	echo '<input type="text" name="id" placeholder="ID" /> ';
	echo '<select name="role">' . \createRolesOptions() . '</select>';
	echo '<button type="submit">Does user have role?</button>';
	echo '</form>';

	echo '<form action="" method="post" accept-charset="utf-8">';
	echo '<input type="hidden" name="action" value="admin.getRoles" />';
	echo '<input type="text" name="id" placeholder="ID" /> ';
	echo '<button type="submit">Get user\'s roles</button>';
	echo '</form>';

	echo '<form action="" method="post" accept-charset="utf-8">';
	echo '<input type="hidden" name="action" value="admin.logInAsUserById" />';
	echo '<input type="text" name="id" placeholder="ID" /> ';
	echo '<button type="submit">Log in as user by ID</button>';
	echo '</form>';

	echo '<form action="" method="post" accept-charset="utf-8">';
	echo '<input type="hidden" name="action" value="admin.logInAsUserByEmail" />';
	echo '<input type="text" name="email" placeholder="Email address" /> ';
	echo '<button type="submit">Log in as user by email address</button>';
	echo '</form>';

	echo '<form action="" method="post" accept-charset="utf-8">';
	echo '<input type="hidden" name="action" value="admin.logInAsUserByUsername" />';
	echo '<input type="text" name="username" placeholder="Username" /> ';
	echo '<button type="submit">Log in as user by username</button>';
	echo '</form>';

	echo '<form action="" method="post" accept-charset="utf-8">';
	echo '<input type="hidden" name="action" value="admin.changePasswordForUser" />';
	echo '<input type="text" name="id" placeholder="ID" /> ';
	echo '<input type="text" name="newPassword" placeholder="New password" /> ';
	echo '<button type="submit">Change password for user by ID</button>';
	echo '</form>';

	echo '<form action="" method="post" accept-charset="utf-8">';
	echo '<input type="hidden" name="action" value="admin.changePasswordForUser" />';
	echo '<input type="text" name="username" placeholder="Username" /> ';
	echo '<input type="text" name="newPassword" placeholder="New password" /> ';
	echo '<button type="submit">Change password for user by username</button>';
	echo '</form>';
}

function showConfirmEmailForm() {
	echo '<form action="" method="post" accept-charset="utf-8">';
	echo '<input type="hidden" name="action" value="confirmEmail" />';
	echo '<input type="text" name="selector" placeholder="Selector" /> ';
	echo '<input type="text" name="token" placeholder="Token" /> ';
	echo '<select name="login" size="1">';
	echo '<option value="0">Sign in automatically? — No</option>';
	echo '<option value="1">Sign in automatically? — Yes</option>';
	echo '<option value="2">Sign in automatically? — Yes (and remember)</option>';
	echo '</select> ';
	echo '<button type="submit">Confirm email</button>';
	echo '</form>';

	echo '<form action="" method="post" accept-charset="utf-8">';
	echo '<input type="hidden" name="action" value="resendConfirmationForEmail" />';
	echo '<input type="text" name="email" placeholder="Email address" /> ';
	echo '<button type="submit">Re-send confirmation</button>';
	echo '</form>';

	echo '<form action="" method="post" accept-charset="utf-8">';
	echo '<input type="hidden" name="action" value="resendConfirmationForUserId" />';
	echo '<input type="text" name="userId" placeholder="User ID" /> ';
	echo '<button type="submit">Re-send confirmation</button>';
	echo '</form>';
}

function showDestroySessionForm() {
	echo '<form action="" method="post" accept-charset="utf-8">';
	echo '<input type="hidden" name="action" value="destroySession" />';
	echo '<button type="submit">Destroy session</button>';
	echo '</form>';
}

function createRolesOptions() {
	$out = '';

	foreach (\Delight\Auth\Role::getMap() as $roleValue => $roleName) {
		$out .= '<option value="' . $roleValue . '">' . $roleName . '</option>';
	}

	return $out;
}
 ?>
