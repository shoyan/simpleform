<?php
/**
 * Simple form
 */
require_once dirname(__FILE__) . '/validation.php';

class Form 
{
    // validation�����Ƥ��Ǽ����
    var $validate;

    // �ǡ������Ǽ����
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
     * �ǡ�����set��Ԥ��ޤ�
     *
     * @param mixed $data required
     * @return void
     */
    function Set($data)
    {
        $this->data = $data;
    }

    /**
     * �Х�ǡ������Υ��顼��å�������ɽ�����ޤ�
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
     * �ե���������Ϥ��줿�ǡ�����ɽ�����ޤ�
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
     * validateion��¹Ԥ��ޤ�
     * ����ʤ����true. ���顼�ξ���false���֤��ޤ�
     *
     * @return boolean
     */
    function validate()
    {
        return $this->validation->validate($this->data);
    }
}
?>
