<?php

class Validation
{
    // ���顼��å��������Ǽ����
    var $validationErrors;
    
    // �Х�ǡ����������Ƥ��Ǽ����
    var $validate;

    // �ǡ������Ǽ����
    var $data;

    /**
     * ���顼�Υ��ơ��������Ǽ���� 
     * ���顼����true, ����ʳ���false
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
     * validateion��¹Ԥ��ޤ�
     * ����ʤ����true. ���顼�ξ���false���֤��ޤ�
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
            // validate���������Ƥ��ʤ����ϡ����⤷�ʤ�
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
     * ɬ�ܥ����å���Ԥ��ޤ���
     * ������������true���֤��ޤ���
     *
     * @param mixed $data Value to check
     * @return boolean 
     */
    function notEmpty($data, $key)
    {
        if (empty($data) && $data != '0') {
            $this->validationErrors[$key] = 'ɬ�ܹ��ܤ����Ϥ���Ƥ��ޤ���';
            $this->error = true;
            return false;
        }
        return true;
    }

    /**
     * �����ֹ�����å���Ԥ��ޤ���
     * ������������true���֤��ޤ���
     *
     * @param mixed $data Value to check
     * @return boolean 
     */
    function phone($data, $key)
    {
        if ( !ereg("^([0-9-]+$)", $data)) {
            $this->validationErrors[$key] = '�����ֹ�˻��ѤǤ��ʤ�ʸ�����ޤޤ�Ƥ��ޤ�';
            $this->error = true;
            return false;
        }
        return true;
    }

    /**
     * ͹���ֹ�Υ����å���Ԥ��ޤ���
     * ������������true���֤��ޤ���
     *
     * @param mixed $data Value to check
     * @return boolean 
     */
    function postCode($data, $key)
    {
        if (is_array($data) || !preg_match("/^\d{3}-\d{4}$/", $data) ) {
            $this->validationErrors[$key] = '͹���ֹ�η���������������ޤ���';
            $this->error = true;
            return false;
        }
        return true;
    }

    /**
     * �᡼�륢�ɥ쥹�Υ����å���Ԥ��ޤ���
     * ������������true���֤��ޤ���
     *
     * @param mixed $data Value to check
     * @return boolean 
     */
    function mail($data, $key)
    {
        if (is_array($data) || !preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\.\+_-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $data)) 
        {
            $this->validationErrors[$key] = '�᡼�륢�ɥ쥹�η���������������ޤ���';
            $this->error = true;
            return false;
        }
        return true;
    }

    /**
     * �᡼�륢�ɥ쥹�ȳ�ǧ�ѥ᡼�륢�ɥ쥹��Ʊ�����Υ����å���Ԥ��ޤ���
     * ������������true���֤��ޤ���
     *
     * @param mixed $data Value to check
     * @return boolean 
     */
    function comparedMail($data, $key)
    {
        if ($this->data['mailAddress'] !== $this->data['mailAddressConfirm'])
        {
            $this->validationErrors[$key] = '��ǧ�ѤΥ᡼�륢�ɥ쥹�Ȱ��פ��ޤ���';
            $this->error = true;
            return false;
        }
        return true;
    }

    function numeric($data, $key) {
        if (!is_numeric($data)) {
            $this->validationErrors[$key] = '���Ͱʳ������ϤǤ��ޤ���';
            $this->error = true;
            return false;
        }
        return true;
    }

    function maxLength30($data, $key)
    {
        if (mb_strlen($data) > 30)
        {
            $this->validationErrors[$key] = '30ʸ����������Ϥ��Ƥ�������';
            $this->error = true;
            return false;
        }
        return true;
    }
}
?>
