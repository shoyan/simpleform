<?php

class Validation
{
    // エラーメッセージを格納する
    var $validationErrors;
    
    // バリデーションの内容を格納する
    var $validate;

    // データを格納する
    var $data;

    /**
     * エラーのステータスを格納する 
     * エラー時はtrue, それ以外はfalse
     */ 
    var $error = false;

    /**
     * PHP4 Constructor
     */    
    function Validation($validate)
    {
        $this->__construct($validate);
    }

    /*
     * PHP5 Constructor
     */
    function __construct($validate)
    {
        $this->validationErrors = array();
        $this->validate = $validate;
    }

    /**
     * validateionを実行します
     * 問題なければtrue. エラーの場合はfalseを返します
     *
     * @return boolean
     */
    function validate($data)
    {
        if (empty($data)) {
            return false;
        }

        $this->data= $data;
        $postData = $data;
        foreach ($postData as $key => $data) {
            $this->validationErrors[$key] = '';
        }

        foreach ($postData as $key => $data) {
            // validateが定義されていない場合は、何もしない
            if (!empty($this->validate[$key])) {
                $validateType = $this->validate[$key];
                
                foreach($validateType as $validateFunction) {
                    if (method_exists($this, $validateFunction)) {
                        $this->$validateFunction($data, $key);
                    } else {
                        trigger_error('validate function is not available');
                    }
                }
            }    
        }
        return ($this->error) ? false : true;
    }

    /**
     * 必須チェックを行います。
     * 成功した場合はtrueを返します。
     *
     * @param mixed $data Value to check
     * @return boolean 
     */
    function notEmpty($data, $key)
    {
        if (empty($data) && $data != '0') {
            $this->validationErrors[$key] = '必須項目が入力されていません';
            $this->error = true;
            return false;
        }
        return true;
    }

    /**
     * 電話番号チェックを行います。
     * 成功した場合はtrueを返します。
     *
     * @param mixed $data Value to check
     * @return boolean 
     */
    function phone($data, $key)
    {
        if ( !ereg("^([0-9-]+$)", $data)) {
            $this->validationErrors[$key] = '電話番号に使用できない文字が含まれています';
            $this->error = true;
            return false;
        }
        return true;
    }

    /**
     * 郵便番号のチェックを行います。
     * 成功した場合はtrueを返します。
     *
     * @param mixed $data Value to check
     * @return boolean 
     */
    function postCode($data, $key)
    {
        if (is_array($data) || !preg_match("/^\d{3}-\d{4}$/", $data) ) {
            $this->validationErrors[$key] = '郵便番号の形式が正しくありません';
            $this->error = true;
            return false;
        }
        return true;
    }

    /**
     * メールアドレスのチェックを行います。
     * 成功した場合はtrueを返します。
     *
     * @param mixed $data Value to check
     * @return boolean 
     */
    function mail($data, $key)
    {
        if (is_array($data) || !preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\.\+_-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $data)) 
        {
            $this->validationErrors[$key] = 'メールアドレスの形式が正しくありません';
            $this->error = true;
            return false;
        }
        return true;
    }

    /**
     * メールアドレスと確認用メールアドレスが同じかのチェックを行います。
     * 成功した場合はtrueを返します。
     *
     * @param mixed $data Value to check
     * @return boolean 
     */
    function comparedMail($data, $key)
    {
        if ($this->data['mailAddress'] !== $this->data['mailAddressConfirm'])
        {
            $this->validationErrors[$key] = '確認用のメールアドレスと一致しません';
            $this->error = true;
            return false;
        }
        return true;
    }

    function numeric($data, $key) {
        if (!is_numeric($data)) {
            $this->validationErrors[$key] = '数値以外は入力できません';
            $this->error = true;
            return false;
        }
        return true;
    }

    function maxLength30($data, $key)
    {
        if (mb_strlen($data) > 30)
        {
            $this->validationErrors[$key] = '30文字以内で入力してください';
            $this->error = true;
            return false;
        }
        return true;
    }
}
?>
