<?php
/**
 * 用户登陆
 *
 * @author Ttall <ttall.su@gmail.com>
 * @link http://www.ttall.net/
 * @copyright Copyright © 2012-2015 ttall.net
 * @license http://www.ttall.net/license/
 */
class LoginController extends Controller {
	// 用户登陆
	public function actionIndex() {
		$username = '';
		if ($_POST['submit-login']) {
			$user = new User();
			$errorMsg = '';
			$username = isset($_POST['username']) ? trim($_POST['username']) : '';
			$password = isset($_POST['password']) ? trim($_POST['password']) : '';
			if ($username && $password) {
				$attributes = array('username' => $username);
				$user = User::model() -> findByAttributes($attributes);
				if ($user) {
					$userpasswd = md5($password );
					$loginErrorTimes=(int)Yii::app()->session['login_error_times'];
					if ($loginErrorTimes > 0) {
						$loginErrorTimes++;
					} else {
						$loginErrorTimes = 1;
					}
					// 错误大于3次，需要输入验证码。
					if ($loginErrorTimes >= 3) {
						$verifyCode=isset($_POST['verify_code'])?trim($_POST['verify_code']) : '';
						$captcha = Yii::app() -> session['captcha'];
						if (strtolower($captcha) != strtolower($verifyCode)) {
							//$this -> assign('errorMsg', '验证码错误！');
							$errorMsg = '验证码错误！';
						}
						$this -> assign('login_error_times', $loginErrorTimes);
					}
					// 登陆次数,提交超过三次需要输入验证码
					Yii::app() -> session['login_error_times'] = $loginErrorTimes;

					if (empty($errorMsg)) {
						if ($userpasswd != $user -> password) {
							// 登陆错误次数
							//$this -> assign('errorMsg', '密码错误！');
							$errorMsg = '密码错误！';
						} else {
							// 登陆成功，
							Yii::app()->session['username']=$user['username'];
							Yii::app() -> session['nickname'] = $user['nickname'];
							Yii::app() -> session['user_id'] = $user['user_id'];
							Yii::app() -> session['is_login'] = TRUE;
							Yii::app() -> session['visitor'] = $user;
							// 更新用户记录:登陆时间，最近一次IP，登陆次数等
							$user -> last_login = time();
							$user -> last_ip = $this -> getIp();
							$user -> login_times = $user -> login_times + 1;
							$user -> last_login = time();
							$user -> save();
							// 重置登陆错误次数
							Yii::app() -> session['login_error_times'] = null;
							$this -> redirect("/index.php?r=ucenter/default");
							return;
						}
					}
				} else {
					$errorMsg = '用户名不存在！';
				}
			} else if (empty($username)) {
				$errorMsg = '用户名不可以为空！';
			} else if (empty($password)) {
				$errorMsg = '密码不可以为空！';
			}
		}
		$this -> assign('errorMsg', $errorMsg);
		$this -> assign('username', $username);
		$this->smarty->display('login/login.tpl');
	}
	// 配置类
	public function actions() {
		// return external action classes, e.g.:
		return array();
	}
}
