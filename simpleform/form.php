<?php
/**
 * Simple form
 */
require_once dirname(__FILE__) . '/validation.php';

class Form 
{
    // validationの内容を格納する
    var $validate;

    // データを格納する
    var $data;

    /**
     * PHP4 Constructor
     */    
    function Form()
    {
        $this->__construct();
    }

    /*
     * PHP5 Constructor
     */
    function __construct()
    {
        $this->validation = new Validation($this->validate);
    }

    /**
     * データのsetを行います
     *
     * @param mixed $data required
     * @return void
     */
    function Set($data)
    {
        $this->data = $data;
    }

    /**
     * バリデーションのエラーメッセージを表示します
     * 
     * @param string $fieldName
     * @return string
     */
    function showMessage($fieldName)
    {
        if (!empty($this->validation->validationErrors[$fieldName])) {
            return $this->validation->validationErrors[$fieldName];
        } else {
            return '';
        }
    }
    
    /**
     * フォームに入力されたデータを表示します
     *
     * @param string $fieldName
     * @option array $option
     */
    function showData($fieldName, $option=null)
    {
        if (!empty($this->data[$fieldName])) {
            $val = $this->data[$fieldName];
        } else {
            $val = '';
        }

        if ($option['type'] == 'select' && !empty($val)) {
            $script = <<< EOF
            <script type="text/javascript">
               $(document).ready(function(){
                   $("select[name={$fieldName}]").val($val);
               });
            </script>
EOF;
            return $script;
        } elseif ($option['type'] == 'radio' && !empty($val)) {
            $script = <<< EOF
            <script type="text/javascript">
               $(document).ready(function(){
                   $("input:radio[name={$fieldName}]").removeAttr('checked');
                   $("input:radio[name={$fieldName}]").val(["{$val}"])
               });
            </script>
EOF;
            return $script;
        }
        return $val;
    }

    /**
     * validateionを実行します
     * 問題なければtrue. エラーの場合はfalseを返します
     *
     * @return boolean
     */
    function validate()
    {
        return $this->validation->validate($this->data);
    }
}
?>
