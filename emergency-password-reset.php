<?php
/**
 * 一次性管理员密码重置 — 用完后务必删除本文件。
 *
 * 1. 修改下面 $emergency_secret 为一串随机字符（仅你自己知道）。
 * 2. 浏览器访问：https://你的本地域名/emergency-password-reset.php
 * 3. 在表单里填写：密钥、管理员用户名、新密码，提交。
 * 4. 成功后立刻删除本文件。
 */
define( 'WP_USE_THEMES', false );

$emergency_secret = 'qq19960820';

require __DIR__ . '/wp-load.php';

header( 'Content-Type: text/html; charset=utf-8' );

if ( ! isset( $_POST['emergency_secret'], $_POST['user_login'], $_POST['new_password'] ) ) {
	?>
	<!DOCTYPE html>
	<html lang="zh-CN">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>紧急重置密码</title>
		<style>
			body { font-family: system-ui, sans-serif; max-width: 28rem; margin: 2rem auto; padding: 0 1rem; }
			label { display: block; margin-top: 1rem; font-weight: 600; }
			input { width: 100%; box-sizing: border-box; padding: 0.5rem; margin-top: 0.25rem; }
			button { margin-top: 1.25rem; padding: 0.6rem 1.2rem; cursor: pointer; }
			p.note { color: #666; font-size: 0.9rem; margin-top: 1.5rem; }
		</style>
	</head>
	<body>
		<h1>紧急重置管理员密码</h1>
		<form method="post" autocomplete="off">
			<label for="emergency_secret">密钥（与文件内 $emergency_secret 一致）</label>
			<input type="password" id="emergency_secret" name="emergency_secret" required>
			<label for="user_login">管理员用户名</label>
			<input type="text" id="user_login" name="user_login" required>
			<label for="new_password">新密码（建议至少 12 位）</label>
			<input type="password" id="new_password" name="new_password" required minlength="8">
			<button type="submit">重置密码</button>
		</form>
		<p class="note">成功后请立即删除 <code>emergency-password-reset.php</code>，否则任何人拿到密钥都能改密码。</p>
	</body>
	</html>
	<?php
	exit;
}

if ( ! hash_equals( (string) $emergency_secret, (string) wp_unslash( $_POST['emergency_secret'] ) ) ) {
	wp_die( '密钥错误。', '403', array( 'response' => 403 ) );
}

$user_login  = sanitize_user( wp_unslash( $_POST['user_login'] ), true );
$new_password = wp_unslash( $_POST['new_password'] );

if ( '' === $user_login || strlen( $new_password ) < 8 ) {
	wp_die( '用户名无效或密码过短（至少 8 位）。', '400', array( 'response' => 400 ) );
}

$user = get_user_by( 'login', $user_login );

if ( ! $user ) {
	wp_die( '找不到该用户。', '404', array( 'response' => 404 ) );
}

if ( ! user_can( $user, 'manage_options' ) ) {
	wp_die( '该用户不是管理员（无 manage_options 权限）。', '403', array( 'response' => 403 ) );
}

wp_set_password( $new_password, $user->ID );

status_header( 200 );
echo '<!DOCTYPE html><meta charset="utf-8"><title>完成</title><p>密码已更新：<strong>' . esc_html( $user_login ) . '</strong>。请立即删除站点根目录下的 <code>emergency-password-reset.php</code>，然后登录后台。</p>';
exit;
