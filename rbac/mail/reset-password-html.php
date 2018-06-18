<?php
# @Author: Die Coding | www.diecoding.com
# @Date:   10 December 2017
# @Email:  diecoding@gmail.com
# @Last modified by:   Die Coding | www.diecoding.com
# @Last modified time: 11 December 2017
# @License: MIT
# @Copyright: 2017


use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user app\models\User */

$link = Yii::$app->urlManager->createAbsoluteUrl(['site/reset-password', 'token' => $user->password_reset_token]);
?>

<div style="padding:0;margin:0">
  <center>
    <table style="background:#ffffff;max-width:520px" align="center" max-width="520" cellspacing="0" cellpadding="0" border="0" bgcolor="#ffffff">
      <tbody>
        <tr>
          <td style="background:#eeeeee" width="20" bgcolor="#eeeeee"></td>
          <td style="margin: 20px;">
            <table width="100%" cellspacing="0" cellpadding="0" border="0">
              <tbody>
                <tr>
                  <td style="background:#eeeeee" height="20" bgcolor="#eeeeee"></td>
                </tr>
                <tr>
                  <td>
                    <table style="border-bottom:1px solid #eeeeee" align="center" width="100%" cellspacing="0" cellpadding="0" border="0">
                      <tbody>
                        <tr>
                          <td height="49"></td>
                        </tr>
                        <tr>
                          <td style="color:#4285f4;font-family:'Roboto',OpenSans,'Open Sans',Arial,sans-serif;font-size:32px;font-weight:300;line-height:46px;margin:0;padding:0 25px 0 25px;text-align:center" align="center">
                            Halo <?= $user->display_name ?>,
                          </td>
                        </tr>

                        <tr>
                          <td height="20"></td>
                        </tr>

                        <tr>
                          <td style="color:#757575;font-family:'Roboto',OpenSans,'Open Sans',Arial,sans-serif;font-size:17px;font-weight:300;line-height:24px;margin:0;padding:0 25px 0 25px;text-align:center" align="center">
                            Silakan klik tombol di bawah ini untuk reset password akun anda :
                          </td>
                        </tr>
                        <tr>
                          <td height="20"></td>
                        </tr>
                        <tr>
                          <td style="padding-left:8px;padding-right:8px;text-align:center">
                            <?= Html::a('RESET PASSWORD', $link, ['style' => 'font-family:Roboto-Regular,Helvetica,Arial,sans-serif;color:#ffffff;background-color:#4285f4;font-weight:400;line-height:20px;text-decoration:none;font-size:14px;padding: 10px 15px;', 'target' => '_blank']) ?>
                          </td>
                        </tr>
                        <tr style="height:8px"></tr>

                        <tr>
                          <td height="30"></td>
                        </tr>
                      </tbody>
                    </table>
                  </td>
                </tr>
                <tr>
                  <td>
                    <table align="center" width="100%" cellspacing="0" cellpadding="0" border="0">
                      <tbody>
                        <tr>
                          <td style="background:#eeeeee" height="19" bgcolor="#eeeeee"></td>
                        </tr>
                        <tr>
                          <td style="background:#eee;color:#777;font-family:'Roboto',OpenSans,'Open Sans',Arial,sans-serif;font-size:10px;font-weight:300;line-height:14px;margin:0;padding:0 6px 0 6px;text-align:center" align="center" valign="middle">
                            <p style="margin: 0; padding: 0;">© <?= date('Y') ?> Sistem Event Kemahasiswaan Universitas Sebelas Maret</p>
                            <p style="margin: 0; padding: 0;">Dikembangkan oleh <a href="https://www.diecoding.com" target="_blank">Die Coding</a></p>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </td>
                </tr>
                <tr>
                  <td style="background:#eeeeee" height="18" bgcolor="#eeeeee"></td>
                </tr>
              </tbody>
            </table>
          </td>
          <td style="background:#eeeeee" width="20" bgcolor="#eeeeee"></td>
        </tr>
      </tbody>
    </table>
    <div style="display:none;white-space:nowrap;font:15px courier;line-height:0">
      &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
      &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
    </div>
  </center>

</div>
